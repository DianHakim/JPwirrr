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

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    // ================= BOOT METHOD =================
    protected static function boot()
    {
        parent::boot();

        // generate trs_code otomatis jika belum ada
        static::creating(function ($trx) {
            if (!$trx->trs_code) {
                $trx->trs_code = 'TRX-' . time() . rand(1000, 9999);
            }

            // pastikan diskon default tersimpan
            if (!$trx->discount_type) $trx->discount_type = 'none';
            if (!$trx->discount_percent) $trx->discount_percent = 0;
            if (!$trx->discount_nominal) $trx->discount_nominal = 0;
        });
    }
}
