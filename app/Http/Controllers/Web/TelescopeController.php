<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Routing\Controller;
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
