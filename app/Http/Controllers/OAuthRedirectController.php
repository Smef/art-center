<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class OAuthRedirectController extends Controller
{
    public function __invoke($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }
}
