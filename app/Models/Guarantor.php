<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guarantor extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'phone',
        'relationship',
        'address'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
