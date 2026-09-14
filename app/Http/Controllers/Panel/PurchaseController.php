<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Repositories\HeroesRepository;
use App\Repositories\MarketplaceItemsRepository;
use App\Services\PurchaseService;
use App\Support\CurrentCampaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends Controller
{
    public function __construct(
        private readonly HeroesRepository $heroesRepository,
        private readonly MarketplaceItemsRepository $marketplaceItemsRepository,
        private readonly PurchaseService $purchaseService,
    ) {}

    public function index()
    {
        $heroes = $this->heroesRepository->getHeroes($this->currentCampaign()->id(), ['id', 'name']);
        $marketplaceItems = $this->marketplaceItemsRepository->getItemsForSale();
        return view('Panel.purchases.index', compact('heroes', 'marketplaceItems'));
    }
    public function sendPurchase(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->purchaseService->sendPurchaseToPlayer($request, $this->currentCampaign()->id());
            return response()->json('ok', Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Nie znaleziono gracza lub przedmiotu'], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            \Log::error('Error during purchase: ' . $e->getMessage());
            return response()->json(['message' => 'Wystąpił błąd podczas wysyłki transakcji'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
