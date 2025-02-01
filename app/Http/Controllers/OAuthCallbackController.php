<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthCallbackController extends Controller
{
    public function __invoke($provider)
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();

        // Check if this user exists already by provider and provider ID
        $provider_id_column = $provider.'_id';
        $user = User::where([$provider_id_column => $userSocial->getId()])->first();

        // get the user email from the social provider
        $socialName = $userSocial->getName();
        if ($provider === 'azure') {
            // azure doesn't return the correct email with getEmail() if the user is external
            $socialEmail = $userSocial['mail'];
        } else {
            $socialEmail = $userSocial->getEmail();
        }

        if ($user) {
            // This user has logged in with a social ID before

            // Update info we have in our database
            $user->name = $socialName;
            $user->email = $socialEmail;

            $user->save();

            // Log them in
            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));
        }

        // this users hasn't logged in with this OAuth provider account before
        // try to match them by email
        $user = User::updateOrCreate([
            'email' => $socialEmail,
        ], [
            'name' => $socialName,
            $provider_id_column => $userSocial->getId(),
        ]);

        Auth::login($user);

        return redirect('/dashboard');

        // $user->token;
    }
}
