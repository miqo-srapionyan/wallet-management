<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Facebook Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users by Facebook
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
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleFacebookCallback() {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect($this->redirectTo);
            } else {
                $newUser = User::create(['name' => $user->name, 'email' => $user->email, 'facebook_id' => $user->id, 'email_verified_at' => date('Y-m-d H:i:s')]);
                Auth::login($newUser);
                return redirect($this->redirectTo);
            }
        }
        catch(Exception $e) {
            return redirect('/login');
        }
    }
}
