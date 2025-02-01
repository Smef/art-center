<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUpdateRequest;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $search = request()->query('search');

        $contacts = Contact::when($search, function ($query, $search) {
            //trim any stars from the search, which can cause errors
            $search = str_replace('*', '', $search);

            // explode, then implode to append a * at the end of each word for wildcard searching
            $searchArray = explode(' ', $search);
            $searchString = implode('* ', $searchArray).'*';

            $query->whereFullText(['name_first', 'name_last', 'email', 'phone'], $searchString, ['mode' => 'boolean'])
                ->orWhereHas('Company', function ($query) use ($searchString) {
                    $query->whereFullText(['name'], $searchString, ['mode' => 'boolean']);
                });
        })
            ->with('company')
            ->paginate(20)->withQueryString();

        $data = [
            'paginatedData' => $contacts,
        ];

        return Inertia::render('Contacts/ContactIndex', $data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return $this->edit(new Contact);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactUpdateRequest $request): RedirectResponse
    {
        $validated = $request->input();
        $contact = Contact::create($validated);

        return redirect()->route('contacts.edit', $contact->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact): Response
    {
        return $this->edit($contact);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact): Response
    {
        $contact->load('company');

        $data = [
            'contact' => $contact,
            // (...) is for callable syntax in PHP 8.1 and is required
            // https://www.php.net/manual/en/functions.first_class_callable_syntax.php
            'companies' => Inertia::lazy($this->getSearchedCompanies(...)),
        ];

        return Inertia::render('Contacts/ContactEdit', $data);
    }

    protected function getSearchedCompanies()
    {
        $companies = QueryBuilder::for(Company::class)
            ->allowedFilters('name')
            ->limit(10)
            ->orderBy('name')
            ->get();

        return $companies;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactUpdateRequest $request, Contact $contact): Response
    {
        $validated = $request->validated();

        $contact->update($validated);

        return $this->show($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Contact::destroy($id);

        return redirect()->route('contacts.index');
    }
}
