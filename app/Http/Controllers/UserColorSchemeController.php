<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserColorSchemeRequest;

class UserColorSchemeController extends Controller
{
    public function update(UserColorSchemeRequest $request)
    {
        $validated = $request->validated();
        $colorScheme = $validated['color_scheme'];

        $request->user()->update(['color_scheme' => $colorScheme]);

        return back();
    }
}
