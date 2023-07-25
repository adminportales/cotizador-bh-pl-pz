<?php

namespace App\Models\Catalogo;

use App\Models\CompaniePro;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'providers';

    protected $connection = 'mysql_catalogo';

    protected $fillable = ['company', 'email', 'phone', 'contact', 'discount'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Catalogo\Product', 'provider_id', 'id');
    }
    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'companies_providers', 'provider_id', 'company_id');
    }
}
