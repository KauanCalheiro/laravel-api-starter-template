<?php

namespace App\Exceptions;

use Throwable;

class TelescopeUnauthenticatedHandler
{
    public function handler($request, Throwable $exception)
    {
        if ($request->is('telescope', 'telescope/*')) {
            return redirect(
                route('telescope.login.get'),
            );
        }
    }
}
