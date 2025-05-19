<?php

namespace App\Http\Controllers;

use App\Models\FooterText;

class HomepageController extends Controller
{
    public function getFooterText(): \Illuminate\Http\JsonResponse
    {
        return response()->json(FooterText::query()->inRandomOrder()->first()->text);
    }
}
