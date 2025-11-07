<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockLog extends Model
{
    use HasFactory;
    protected $table = 'stock_logs';
    protected $fillable = ['product_id','before','after','description'];

    public function product() { return $this->belongsTo(Product::class, 'product_id'); }
}
