<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Transaction;

class MasterItem extends Model
{
    //
    use HasFactory;

    // protected $table = 'master_item';

    protected $fillable = [
        'company',
        "item_type",
        "code",
        "label",
        "type",
        "isActive",
    ];

    // Relasi ke Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'master_item_id');
    }
}
