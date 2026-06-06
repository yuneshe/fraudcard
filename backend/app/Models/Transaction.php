<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'Amount',
        'merchant',
        'transaction_time',
        'Time',
        'V1', 'V2', 'V3', 'V4', 'V5', 'V6', 'V7', 'V8', 'V9', 'V10',
        'V11', 'V12', 'V13', 'V14', 'V15', 'V16', 'V17', 'V18', 'V19', 'V20',
        'V21', 'V22', 'V23', 'V24', 'V25', 'V26', 'V27', 'V28',
        'risk_score',
        'fraud_status',
    ];

    protected $casts = [
        'Amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'Time' => 'integer',
        'risk_score' => 'float',
        'fraud_status' => 'boolean',
    ];

    public function fraudReports(): HasMany
    {
        return $this->hasMany(FraudReport::class);
    }

    public function scopeFraudulent($query)
    {
        return $query->where('fraud_status', true);
    }

    public function scopeLegitimate($query)
    {
        return $query->where('fraud_status', false);
    }

    public function scopeHighRisk($query)
    {
        return $query->where('risk_score', '>', 0.7);
    }
}
