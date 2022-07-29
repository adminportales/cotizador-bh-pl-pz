<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lead'
    ];

    public function quotesInformation()
    {
        return $this->hasMany(QuoteInformation::class);
    }

    public function latestQuotesInformation()
    {
        return $this->hasOne(QuoteInformation::class)->latestOfMany();
    }
}
