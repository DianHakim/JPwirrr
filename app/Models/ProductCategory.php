<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_categories';
    protected $fillable = ['pdc_name','user_id'];

    public function products() { return $this->hasMany(Product::class, 'pdc_id'); }
    public function user() { return $this->belongsTo(User::class); }
}
