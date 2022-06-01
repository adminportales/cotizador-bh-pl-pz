<?php

namespace App\Models;

use App\Models\Catalogo\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentQuote extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'prices_techniques_id',
        'color_logos',
        'costo_indirecto',
        'utilidad',
        'dias_entrega',
        'cantidad',
        'precio_unitario',
        'precio_total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
