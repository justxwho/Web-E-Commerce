<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'type'
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCart($query)
    {
        return $query->where('type', 'cart');
    }

    public function scopeWishlist($query)
    {
        return $query->where('type', 'wishlist');
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    public function getTotalQtyAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getVatAttribute()
    {
        return $this->subtotal * 0.1;
    }

    public function getFinalTotalAttribute()
    {
        return $this->subtotal + $this->vat;
    }
}