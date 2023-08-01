<?php

namespace App\Models;

use App\Http\Livewire\Companies;
use App\Models\Catalogo\Provider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'companies';

    protected $fillable = ['name', 'image', 'manager', 'email', 'phone'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', 'company_id', 'id');
    }
    public function provider()
    {
        return $this->hasMany(CompaniePro::class, 'companie_id', 'id');
    }

    public function providers()
    {
        // Obtener nombre de la base de datos
        $nameDB = (new Company())->getConnection()->getDatabaseName();
        return $this->belongsToMany(Provider::class, $nameDB . '.companies_providers', 'companie_id', 'provider_id')->orderBy('provider_id', 'asc');
    }
}
