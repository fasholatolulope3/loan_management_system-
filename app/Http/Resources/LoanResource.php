<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'approval_status' => $this->approval_status,
            'amount' => $this->amount,
            'interest_rate' => $this->interest_rate,
            'installment_count' => $this->installment_count,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),

            // Business Info
            'business_name' => $this->business_name,
            'business_location' => $this->business_location,

            // Related
            'client' => new ClientResource($this->whenLoaded('client')),
            'product' => $this->whenLoaded('product', fn() => [
                'id' => $this->product->id,
                'name' => $this->product->name,
            ]),
            'collation_center' => $this->whenLoaded('collationCenter', fn() => [
                'id' => $this->collationCenter->id,
                'name' => $this->collationCenter->name,
            ]),
            'approver' => new UserResource($this->whenLoaded('approver')),
            'schedules' => LoanScheduleResource::collection($this->whenLoaded('schedules')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),

            // Financial summary (only when loaded via show())
            $this->mergeWhen(isset($this->resource->summary), [
                'summary' => $this->resource->summary ?? null,
            ]),

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
