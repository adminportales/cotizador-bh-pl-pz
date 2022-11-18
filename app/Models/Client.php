<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'clients';

    protected $fillable = ['user_id', 'client_odoo_id', 'name', 'contact', 'email', 'phone', 'company_id'];

    public function userOdoo()
    {
        return $this->hasOne(UserInformationOdoo::class, 'odoo_id', 'user_id');
    }

    public function tradenames()
    {
        return $this->hasMany('App\Models\Tradename');
    }

    public function firstTradename()
    {
        return $this->hasOne('App\Models\Tradename')->latest();
    }
}
