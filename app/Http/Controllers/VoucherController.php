<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherUpdateRequest;
use App\Models\Voucher;
use Inertia\Inertia;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->query('search');

        $query = Voucher::query();
        if ($search) {
            $query->whereFullText(['po_number'], $search.'*', ['mode' => 'boolean']);

        }

        $vouchers = $query->paginate(20)->withQueryString();

        $data = [
            'paginatedData' => $vouchers,
        ];

        return Inertia::render('Vouchers/VoucherIndex', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newVoucher = new Voucher;
        $newVoucher->date = now();

        return $this->edit($newVoucher);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherUpdateRequest $request)
    {
        // create a new company and then send it to the update method to keep them the same

        $validated = $request->validated();
        $voucher = new Voucher($validated);

        // save first to get an ID
        $voucher->save();

        // save the logo to disk if it's present
        $this->updateFileIfPresent($validated, $voucher);
        // save it again after updating the logo
        $voucher->save();

        return redirect()->route('vouchers.show', $voucher->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {

        return $this->edit($voucher);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        $voucher->load('lines');

        return Inertia::render('Vouchers/VoucherEdit', [
            'voucher' => $voucher,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherUpdateRequest $request, Voucher $voucher)
    {
        $validated = $request->validated();

        $this->updateFileIfPresent($validated, $voucher);

        $voucher->update($validated);

        // save the related VoucherLines
        $lines = $validated['lines'];

        // delete any existing lines and save the as new
        // This is a very simple way to handle this, which also prevents having to worry about updating existing lines
        $voucher->lines()->delete();
        foreach ($lines as $key => $line) {
            $voucher->lines()->create($line);
        }

        return $this->show($voucher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('vouchers.index');
    }

    protected function updateFileIfPresent(array $validated, Voucher $voucher)
    {
        // check if we have a logo
        if (array_key_exists('file', $validated)) {
            // we have a logo
            $file = $validated['file'];

            // delete the file if it's been set to null
            if ($file === null) {
                $voucher->deleteFile();
            }

            // update the file if there's a file
            if ($file) {
                $voucher->storeFile($file);

            }

            // also clear our textract parse results
            $voucher->parse_results = null;
        }
    }
}
