<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'produk_id', // Pastikan konsisten dengan database
        'quantity',
        'total_price',
        'status',
        'payment',
        'transfer_poto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id'); // Sesuaikan dengan foreign key
    }

    // public function getTotalPriceAttribute()
    // {
    //     return $this->attributes['total_price'] ?? ($this->produk ? $this->produk->harga * $this->quantity : 0);
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Generate order_id jika belum ada
            if (!$transaction->order_id) {
                $transaction->order_id = 'ORD-' . Str::upper(Str::random(10));
            }

            // Perbaiki nama field `produk_id` & `harga`
            if (!$transaction->total_price) {
                $product = Produk::find($transaction->produk_id); // Perbaiki dari `product_id` ke `produk_id`
                $transaction->total_price = $product ? $product->harga * $transaction->quantity : 0;
            }
        });

        static::updating(function ($transaction) {
            // Update total price jika quantity atau produk berubah
            $product = Produk::find($transaction->produk_id);
            if ($product) {
                $transaction->total_price = $product->harga * $transaction->quantity;
            }
        });
    }
}
