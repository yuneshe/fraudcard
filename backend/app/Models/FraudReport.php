<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraudReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'risk_score',
        'prediction',
        'confidence',
    ];

    protected $casts = [
        'risk_score' => 'float',
        'confidence' => 'float',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
