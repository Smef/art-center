<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $users = QueryBuilder::for(User::class)
            ->allowedFilters('name')
            ->paginate(20)->withQueryString();
        $data = [
            'paginatedData' => $users,
        ];

        return Inertia::render('Users/UsersIndex', $data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->edit(new User);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserUpdateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        return $this->edit($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {

        $roles = Role::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Users/UserEdit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        // we need to get the role separately from the rest of the attributes to update
        $validated = $request->safe()->except('role');
        $role = $request->safe()->only('role');

        // update the user record attributes
        $user->update($validated);

        // update the user's role
        $user->syncRoles($role);

        return $this->show($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // not implemented - we probably shouldn't delete users
        // maybe deactivate instead?
    }
}
