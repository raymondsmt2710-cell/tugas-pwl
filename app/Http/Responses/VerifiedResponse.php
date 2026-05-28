<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifiedResponse implements VerifyEmailResponseContract
{
    public function toResponse($request)
    {
        return redirect('/email/verified');
    }
}
