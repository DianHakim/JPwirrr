<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'prd_name',
        'prd_price',
        'prd_stock',
        'prd_color',
        'prd_size',
        'prd_photo',
        'pdc_id'
    ];

    public function category() 
    { 
        return $this->belongsTo(ProductCategory::class, 'pdc_id'); 
    }
    public function transactionDetails() 
    { 
        return $this->hasMany(TransactionDetail::class, 'product_id'); 
    }
}
