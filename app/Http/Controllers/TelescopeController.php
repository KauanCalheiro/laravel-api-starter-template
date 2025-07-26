<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class TelescopeController extends Controller
{
    public function authenticate(LoginRequest $request)
    {
        try {
            $user = User::find($request->input('login'));

            if (!$user) {
                throw new Exception(__('auth.login.user_not_found'));
            }

            if (!$user->hasRole('admin')) {
                throw new Exception(__('authorization.forbidden'));
            }

            (new AuthController())->login($request);

            Auth::login($user);

            return redirect('/telescope');
        } catch (Exception $e) {
            return back()->withErrors([
                'login' => $e->getMessage(),
            ]);
        }
    }

    public function renderLogin()
    {
        if (Auth::check()) {
            return redirect('/telescope');
        }

        return view('telescope-login');
    }
}
