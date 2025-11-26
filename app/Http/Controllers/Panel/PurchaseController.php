<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Repositories\HeroesRepository;
use App\Repositories\MarketplaceItemsRepository;
use App\Services\PurchaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends Controller
{
    private HeroesRepository $heroesRepository;
    private MarketplaceItemsRepository $marketplaceItemsRepository;
    private PurchaseService $purchaseService;
    public function __construct()
    {
        $this->heroesRepository = new HeroesRepository();
        $this->marketplaceItemsRepository = new MarketplaceItemsRepository();
        $this->purchaseService = new PurchaseService();
    }
    public function index()
    {
        $heroes = $this->heroesRepository->getHeroes(['id', 'name']);
        $marketplaceItems = $this->marketplaceItemsRepository->getItemsForSale();
        return view('Panel.purchases.index', compact('heroes', 'marketplaceItems'));
    }
    public function sendPurchase(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->purchaseService->sendPurchaseToPlayer($request);
            return response()->json('ok', Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Nie znaleziono gracza lub przedmiotu'], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            \Log::error('Error during purchase: ' . $e->getMessage());
            return response()->json(['message' => 'Wystąpił błąd podczas wysyłki transakcji'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
