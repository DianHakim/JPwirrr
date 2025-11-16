<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trs_code',
        'trs_subtotal',
        'trs_discount',
        'trs_total',
        'payment_method',
        'cash',
        'change',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportTransaction::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($trx) {
            if (!$trx->trs_code) {
                $trx->trs_code = 'TRX-' . time() . rand(1000, 9999);
            }

            if (!$trx->trs_discount) $trx->trs_discount = 0;
        });
    }
}
