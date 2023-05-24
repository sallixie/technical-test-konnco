<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ["id"];
    protected $append = ["total_biaya"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalBiayaAttribute()
    {
        $total = 0;
        foreach ($this->cartItems as $cartItem) {
            $total += $cartItem->item->harga * $cartItem->jumlah_item;
        }
        return $total;
    }
}
