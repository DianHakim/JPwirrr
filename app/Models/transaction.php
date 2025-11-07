<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = ['user_id','trs_subtotal','trs_total','payment_method'];

    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(TransactionDetail::class, 'transaction_id'); }
}
