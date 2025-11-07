<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportTransaction extends Model
{
    use HasFactory;
    protected $table = 'report_transactions';
    protected $fillable = ['transaction_id','product_id','dtr_subtotal','dtr_period'];

    public function transaction() { return $this->belongsTo(Transaction::class, 'transaction_id'); }
    public function product() { return $this->belongsTo(Product::class, 'product_id'); }
}
