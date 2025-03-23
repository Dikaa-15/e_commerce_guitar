<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'foto',
        'stok',
        'category',
    ];
    
    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }

}
