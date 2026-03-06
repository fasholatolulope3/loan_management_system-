<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'loan_id' => $this->loan_id,
            'amount' => $this->amount,
            'method' => $this->method,
            'status' => $this->status,
            'paid_at' => $this->paid_at,
            'verified_at' => $this->verified_at,
            'loan' => $this->whenLoaded('loan', fn() => [
                'id' => $this->loan->id,
                'status' => $this->loan->status,
                'amount' => $this->loan->amount,
            ]),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
