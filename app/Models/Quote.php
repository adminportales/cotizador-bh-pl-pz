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
        'show_total',
        'type_days',
        'logo',
        "pending_odoo",
        "company_id",
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class);
    }
}
