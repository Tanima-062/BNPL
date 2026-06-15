<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sequence' => $this->sequence,
            'amount' => $this->amount,
            'due_date' => $this->due_date->toDateString(),
            'status' => $this->status,
            'paid_at' => $this->paid_at,
        ];
    }
}