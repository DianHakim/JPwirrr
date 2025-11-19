<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_details';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_name',
        'qty',
        'price_at_sale',
        'subtotal',
    ];

    // Detail ke transaksinya
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    // Detail ke produknya
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'prd_name' => $this->product_name
        ]);
    }
}
