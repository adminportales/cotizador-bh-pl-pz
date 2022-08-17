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

    public function quotesUpdate()
    {
        return $this->hasMany(QuoteUpdate::class);
    }

    public function latestQuotesUpdate()
    {
        return $this->hasOne(QuoteUpdate::class)->latestOfMany();
    }
}
