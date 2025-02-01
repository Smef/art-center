<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherParseRequest;
use App\Models\Voucher;
use Aws\Textract\Exception\TextractException;
use Aws\Textract\TextractClient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class VoucherParserController extends Controller
{
    public function store(VoucherParseRequest $request)
    {

        $validated = $request->validated();

        if ($validated['id']) {
            $voucher = Voucher::findOrFail($validated['id']);
        } else {
            // make a new voucher record and save it right away so we have an ID for the path
            $voucher = new Voucher;
            $voucher->date = Carbon::now();
            $voucher->save();
        }

        // store the file if one was sent
        $file = $validated['file'] ?? null;
        if ($file) {
            $voucher->storeFile($file);
            $voucher->parse_results = null;
            $voucher->save();
        }

        if ($voucher->parse_results) {
            // use the existing data if it exists
            $resultsArray = $voucher->parse_results;
        } else {
            // There's no existing data, so we need to parse the files

            $textractConfig = [
                'version' => 'latest',
                'region' => config('services.textract.region'),
            ];

            // we only need to set credentials if they are in the config
            // Elastic Beanstalk will use roles, so we shouldn't have a key and secret
            if (config('services.textract.key') && config('services.textract.secret')) {
                $textractConfig['credentials'] = [
                    'key' => config('services.textract.key'),
                    'secret' => config('services.textract.secret'),
                ];
            }
            // make a new textract client using the aws sdk
            $textractClient = new TextractClient($textractConfig);

            $filePath = $voucher->getFilePath();

            // if the file is in S3 we can just path the path to textract
            // otherwise we should get the file and pass the bytes
            $disk = $voucher->getFileStorageDisk();
            if ($disk === 's3') {
                // trim the leading slash from the path, which is invalid for S3
                $filePath = ltrim($filePath, '/');
                $textractCommand = [
                    'Document' => [
                        'S3Object' => [
                            'Bucket' => config('filesystems.disks.s3.bucket'),
                            'Name' => $filePath,
                        ],
                    ],
                ];

            } else {
                $file = Storage::get($filePath);
                $textractCommand = [
                    'Document' => [
                        'Bytes' => $file,
                    ],
                ];
            }

            try {
                $results = $textractClient->analyzeExpense($textractCommand);
            } catch (TextractException $e) {
                // if there was an error parsing the file, we report it and redirect back
                report($e);

                return redirect()->back()->withErrors(['ParseError' => 'There was an error parsing the file.']);
            }

            // save the parsing results in our database in case we need it later to get additional data
            $voucher->parse_results = $results->toArray();
            $voucher->save();

            $resultsArray = $results->toArray();
        }

        $headers = $this->parseHeaderData($resultsArray);
        $voucher->date = Carbon::parse($headers['summary']['date']) ?? Carbon::now();
        $voucher->number = $headers['summary']['number'] ?? null;
        $voucher->po_number = $headers['summary']['po_number'] ?? null;
        $voucher->save();

        $lines = $this->parseLineItemData($resultsArray);

        $voucher->lines()->delete();
        foreach ($lines as $line) {
            $voucher->lines()->create([
                'description' => $line['description'],
                'quantity' => $line['quantity'] ?? 0,
                'each' => $line['each'] ?? 0,
                'code' => $line['code'] ?? null,

            ]);
        }

        return redirect()->route('vouchers.show', $voucher);
    }

    protected function parseHeaderData($results)
    {

        $document = $results['ExpenseDocuments'][0];
        $summaryFields = $document['SummaryFields'];

        $vendor = [];
        $summary = [];

        // search the array of summary fields to find type name
        foreach ($summaryFields as $field) {

            $type = $field['Type']['Text'];
            $value = $field['ValueDetection']['Text'];
            $confidence = $field['ValueDetection']['Confidence'];

            if ($type === 'NAME') {
                $vendor['name'] = $value;

                continue;
            }

            if ($type === 'STREET') {
                $vendor['street'] = $value;

                continue;
            }
            if ($type === 'CITY') {
                if ($confidence < 96) {
                    continue;
                }
                $vendor['city'] = $value;

                continue;
            }
            if ($type === 'STATE') {
                $vendor['state'] = $value;

                continue;
            }
            if ($type === 'ZIP_CODE') {
                $vendor['zip'] = $value;

                continue;
            }

            if ($type === 'VENDOR_PHONE') {
                $vendor['phone'] = $value;

                continue;
            }

            if ($type === 'INVOICE_RECEIPT_ID') {
                $summary['number'] = $value;

                continue;
            }

            if ($type === 'INVOICE_RECEIPT_DATE') {
                $summary['date'] = $value;

                continue;
            }

            if ($type === 'TOTAL') {
                $summary['total'] = $value;

                continue;
            }

            if ($type === 'PO_NUMBER') {
                $summary['po_number'] = $value;

                continue;
            }

        }

        return compact('vendor', 'summary');

    }

    protected function parseLineItemData($results): array
    {
        $document = $results['ExpenseDocuments'][0];

        $lines = [];

        // loop through each page
        $lineGroups = $document['LineItemGroups'];
        foreach ($lineGroups as $lineGroup) {
            $lineItems = $lineGroup['LineItems'];
            // loop through the line items to get the actual data (description, quantity, price, etc.

            foreach ($lineItems as $line) {
                // get the expense fields, which is the actual info
                $line = $line['LineItemExpenseFields'];

                // make an empty line object to fill with data
                $thisLine = [];

                foreach ($line as $lineField) {
                    $type = $lineField['Type']['Text'];
                    $value = $lineField['ValueDetection']['Text'];
                    if ($type === 'ITEM') {
                        $thisLine['description'] = $value;

                        continue;
                    }

                    if ($type === 'QUANTITY' && $value !== '') {
                        $thisLine['quantity'] = $this->textToFloat($value);

                        continue;
                    }

                    if ($type === 'UNIT_PRICE') {
                        $thisLine['each'] = $this->textToFloat($value);

                        continue;
                    }

                    if ($type === 'PRICE') {
                        $thisLine['total'] = $this->textToFloat($value);

                        continue;
                    }

                    if ($type === 'PRODUCT_CODE') {
                        $thisLine['code'] = $value;
                    }

                }

                $lines[] = $thisLine;
            }
        }

        return $lines;

    }

    protected function textToFloat($text): float
    {
        $text = str_replace('$', '', $text);
        $text = str_replace(',', '', $text);

        return floatval($text);
    }
}
