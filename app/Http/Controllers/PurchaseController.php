<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Services\PurchaseService;

class PurchaseController extends Controller
{
    public function __construct(
        private PurchaseService $service
    ) {}

    /**
     * Create purchase
     */
    public function store(CreatePurchaseRequest $request)
    {
        $purchase = $this->service->create($request->validated());

        return new PurchaseResource($purchase);
    }

    /**
     * View purchase with schedule
     */
    public function show(Purchase $purchase)
    {
        return new PurchaseResource(
            $purchase->load('installments')
        );
    }
}