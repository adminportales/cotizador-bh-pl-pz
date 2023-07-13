<?php

namespace App\Models;

use App\Models\Catalogo\Provider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $table = 'companies';

    protected $fillable = ['name','image','manager','email','phone'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', 'company_id', 'id');
    }
   /*  public function whatProvider()
    {
        return $this->belongsToMany(Provider::class, 'company_provider', 'companies_id', 'providers_id');
    } */
}
