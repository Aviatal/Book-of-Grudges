<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\UserAdminService;
use Illuminate\View\View;

/**
 * Podgląd bohatera (tylko do odczytu) niezależny od bieżącej kampanii superadmina —
 * dlatego przyjmujemy surowe id, a nie {hero}, którego binding zawęża się do kampanii z sesji.
 */
class SuperadminHeroesController extends Controller
{
    public function __construct(private readonly UserAdminService $userAdminService) {}

    public function show(int $heroId): View
    {
        $hero = $this->userAdminService->hero($heroId);

        return view('Panel.superadmin.heroes.show', compact('hero'));
    }
}
