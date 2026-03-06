<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'national_id' => $this->national_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'loans' => LoanResource::collection($this->whenLoaded('loans')),
            'guarantors' => $this->whenLoaded(
                'guarantors',
                fn() =>
                $this->guarantors->map(fn($g) => [
                    'id' => $g->id,
                    'name' => $g->name,
                    'phone' => $g->phone,
                ])
            ),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
