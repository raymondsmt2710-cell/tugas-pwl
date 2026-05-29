<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;

class PasswordResetResponse implements PasswordResetResponseContract
{
    public function toResponse($request)
    {
        return redirect('/login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
