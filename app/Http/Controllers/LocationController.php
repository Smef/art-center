<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $search = request()->query('filter')['name'] ?? null;

        $locations = Location::when($search, function ($query, $search) {
            // trim any stars from the search, which can cause errors
            $search = str_replace('*', '', $search);

            // explode, then implode to append a * at the end of each word for wildcard searching
            $searchArray = explode(' ', $search);
            $searchString = implode('* ', $searchArray).'*';

            // Search processing
            return $query->whereFullText('name', $searchString, ['mode' => 'boolean']);
        })->paginate(20)->withQueryString();

        $data = [
            'paginatedData' => $locations,
        ];

        return Inertia::render('Locations/LocationIndex', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return $this->edit(new Location);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $location = new Location($validated);
        $location->save();

        return redirect()->route('locations.show', $location->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location): Response
    {
        return $this->edit($location);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location): Response
    {
        return Inertia::render('Locations/LocationEdit', [
            'location' => $location,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationUpdateRequest $request, Location $location): Response
    {
        $validated = $request->validated();
        $location->update($validated);

        return $this->show($location);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect()->route('locations.index');
    }
}
