<?php

namespace App\Models;

use App\Models\Catalogo\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentQuoteDetails extends Model
{
    use HasFactory;

    protected $table = 'current_quotes_details';
    protected $fillable = [
        'current_quote_id',
        'product_id',
        'prices_techniques_id',
        'new_price_technique',
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

    public function priceTechnique()
    {
        return $this->belongsTo(PricesTechnique::class, 'prices_techniques_id');
    }
}
