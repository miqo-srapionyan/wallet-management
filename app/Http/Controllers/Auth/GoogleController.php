<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;

class GoogleController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Google Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users by Google
    |
    */

    /*** Where to redirect users after login.
     ** @var string
     */
    protected $redirectTo = '/';

    /*** Create a new controller instance.
     * * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        // check if there an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            Auth::login($existingUser, true);
        } else {
            // create a new user
            $newUser = User::create(['name' => $user->name, 'email' => $user->email, 'google_id' => $user->id, 'email_verified_at' => date('Y-m-d H:i:s')]);
            Auth::login($newUser, true);
        }
        return redirect($this->redirectTo);
    }
}
