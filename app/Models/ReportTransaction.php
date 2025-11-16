<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTransaction extends Model
{
    protected $table = 'report_transactions';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'dtr_subtotal',
        'dtr_period',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
