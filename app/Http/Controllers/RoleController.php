<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters('name')
            ->paginate(20)->withQueryString();

        $data = [
            'paginatedData' => $roles,
        ];

        return Inertia::render('Roles/RoleIndex', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return $this->edit(new Role);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $role = new Role;
        $role->name = $validated['name'];
        // we're only using the web guard, so set that as a default here
        $role->guard_name = 'web';

        // do this in a transaction so that users don't get stuck on a create screen with a half-created role due to an
        // error saving permissions
        DB::transaction(function () use ($validated, &$role) {
            $role->save();
            $role->syncPermissions($validated['permissions']);
        });

        return redirect()->route('settings.roles.edit', $role->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): Response
    {
        return $this->edit($role);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): Response
    {
        $role->load('permissions');
        $permissions = Permission::select(['name', 'description'])->orderBy('name')->get();

        return Inertia::render('Roles/RoleEdit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role): Response
    {
        $validated = $request->validated();

        $role->update($validated);
        $role->syncPermissions($validated['permissions']);

        return $this->show($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Role::destroy($id);

        return redirect()->route('settings.roles.index');
    }
}
