<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        // Bypass the session's 'intended' URL and force redirection to the correct dashboard
        if (in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            return redirect('/admin');
        }

        return redirect('/dashboard');
    }
}
