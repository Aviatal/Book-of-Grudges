<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Przerwij żądanie z 403, jeśli bieżący użytkownik nie jest Mistrzem Gry bieżącej kampanii.
     */
    protected function abortUnlessGm(): void
    {
        abort_unless($this->currentCampaign()->isGm(), 403);
    }

    /**
     * Bieżąca kampania — wstrzykiwana przez EnsureCampaignSelected. Rozwiązywana leniwie
     * (nie w konstruktorze!), bo Laravel instancjonuje kontrolery — żeby odczytać ich
     * middleware — zanim jakiekolwiek middleware trasy (w tym to ustawiające kampanię) się wykona.
     */
    protected function currentCampaign(): \App\Support\CurrentCampaign
    {
        return app(\App\Support\CurrentCampaign::class);
    }
}
