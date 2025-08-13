<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->user_type === 'admin') {
            $redirectUrl = route('dashboard');
        } elseif ($user->user_type === 'reception') {
            $redirectUrl = route('reception.dashboard');  // matches your '/get-top-patient' route
        } elseif ($user->user_type === 'patient') {
            $redirectUrl = route('patient.index');  // matches your '/get-patient' route
        } else {
            $redirectUrl = '/';  // fallback redirect
        }

        return redirect()->intended($redirectUrl);
    }
}
