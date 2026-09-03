<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Przerwij żądanie z 403, jeśli bieżący użytkownik nie jest Mistrzem Gry (adminem).
     */
    protected function abortUnlessGm(): void
    {
        abort_unless((bool) auth()->user()?->is_admin, 403);
    }
}
