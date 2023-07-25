<?php

namespace App\Models;

use App\Models\Catalogo\Provider;
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
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'companie_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Catalogo\Provider', 'provider_id', 'id');
    }
}
