<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'installment_no' => $this->installment_no,
            'due_date' => $this->due_date?->toDateString(),
            'principal_due' => $this->principal_due,
            'interest_due' => $this->interest_due,
            'total_due' => $this->total_due,
            'status' => $this->status,
            'accrued_penalty' => $this->accrued_penalty ?? 0,
        ];
    }
}
