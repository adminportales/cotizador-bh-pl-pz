<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniePro extends Model
{
    use HasFactory;
    protected $table = 'companies_providers';
    protected $fillable = [
        'companie_id',
        'provider_id',
    ];

    public function provider()
    {
        return $this->belongsToMany(provider::class, 'provider_id');
    }
    public function company()
    {
        return $this->belongsToMany(Company::class, 'companie_id');
    }
}
