<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Redirect all users to the member login panel after logout,
     * regardless of which panel they logged out from.
     *
     * @return RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect('/');
    }
}
