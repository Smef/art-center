<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use Illuminate\Http\File;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $search = request()->query('filter')['name'] ?? null;

        $companies = Company::when($search, function ($query, $search) {
            //trim any stars from the search, which can cause errors
            $search = str_replace('*', '', $search);

            // explode, then implode to append a * at the end of each word for wildcard searching
            $searchArray = explode(' ', $search);
            $searchString = implode('* ', $searchArray).'*';

            // Search processing
            return $query->whereFullText('name', $searchString, ['mode' => 'boolean']);
        })->paginate(20)->withQueryString();

        $data = [
            'paginatedData' => $companies,
        ];

        return Inertia::render('Companies/CompanyIndex', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return $this->edit(new Company);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyUpdateRequest $request): RedirectResponse
    {
        // create a new company and then send it to the update method to keep them the same

        $validated = $request->validated();
        $company = new Company($validated);

        // save first to get an ID
        $company->save();

        // save the logo to disk if it's present
        $this->updateLogoIfPresent($validated, $company);
        // save it again after updating the logo
        $company->save();

        return redirect()->route('companies.show', $company->id);

    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): Response
    {
        $company->load('contacts');

        return $this->edit($company);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company): Response
    {
        return Inertia::render('Companies/CompanyEdit', [
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, Company $company): Response
    {

        $validated = $request->validated();

        $this->updateLogoIfPresent($validated, $company);

        $company->update($validated);

        return $this->show($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Company::destroy($id);

        return redirect()->route('companies.index');
    }

    protected function updateLogoIfPresent(array $validated, Company $company)
    {
        // check if we have a logo
        if (array_key_exists('logo', $validated)) {
            // we have a logo
            $logo = $validated['logo'];

            // delete the logo if it's been set to null
            if ($logo === null) {
                $company->deleteLogo();
            }

            // update the logo if there's a file
            if ($logo) {
                $company->storeLogo($logo);
            }
        }
    }
}
