<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $array = parent::share($request);

        // add user permissions if the user is logged in
        $user = $request->user();
        if ($user) {
            $jetstreamAuthUserCallback = Inertia::getShared('auth.user');
            // when running tests this seems to sometimes return an array and sometimes a closure, so we'll call it
            // if it's a closure
            $jetstreamAuthUser = is_callable($jetstreamAuthUserCallback) ? $jetstreamAuthUserCallback() : [];
            $array['auth.user'] = array_merge($jetstreamAuthUser, [
                'permissions' => $user->getPermissionsViaRoles()->pluck('name'),
            ]);
        }

        return $array;
    }
}
