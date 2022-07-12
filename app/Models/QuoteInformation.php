<?php

namespace App\Models;

use FontLib\Table\Type\name;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'email',
        'landline',
        'cell_phone',
        'oportunity',
        'rank',
        'department',
        'information',
    ];

    public function quotesProducts()
    {
        return $this->hasMany(QuoteProducts::class);
    }
}
