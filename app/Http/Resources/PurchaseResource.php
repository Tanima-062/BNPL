<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'outstanding_amount' => $this->outstanding_amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'installments' => InstallmentResource::collection(
                $this->whenLoaded('installments')
            ),
        ];
    }
}