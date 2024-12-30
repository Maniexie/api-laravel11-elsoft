<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterItem;

class Transaction extends Model
{
    use HasFactory;
    // protected $table = 'transactions';
    protected $guarded = [];
    protected $fillable = [
        'master_item_id',
        'transaction_date',
        'account',
        'account_name',
        'note',
        'amount',
    ];

    // Relasi ke MasterItem
    public function masterItem()
    {
        return $this->belongsTo(MasterItem::class, 'master_item_id');
    }
}
