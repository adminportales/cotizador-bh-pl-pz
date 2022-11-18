<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lead',
        'iva_by_item',
        'logo',
        "pending_odoo",
        "company_id",
        // 'client'
    ];

    public function quotesUpdate()
    {
        return $this->hasMany(QuoteUpdate::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function latestQuotesUpdate()
    {
        return $this->hasOne(QuoteUpdate::class)->latestOfMany();
    }
}
