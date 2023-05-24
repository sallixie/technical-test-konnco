<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ["id"];
    protected $append = ["total_harga"];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function getTotalHargaAttribute()
    {
        return $this->item->harga * $this->jumlah_item;
    }
}
