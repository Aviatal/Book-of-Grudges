<?php

namespace App\Services;

use App\Models\Hero;
use App\Repositories\FortunePointsSatisfactionRepository;
use Illuminate\Http\Request;

class FortunePointSatisfactionService
{
    private FortunePointsSatisfactionRepository $fortunePointsSatisfactionRepository;
    public function __construct()
    {
        $this->fortunePointsSatisfactionRepository = new FortunePointsSatisfactionRepository();
    }

    public function logSatisfaction(Request $request, Hero $hero): void
    {
        $request->validate(['satisfied' => 'required|boolean']);
        $this->fortunePointsSatisfactionRepository->insertSatisfactionToDatabase($hero->id, $request->input('satisfied'));
    }
}
