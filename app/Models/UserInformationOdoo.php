<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformationOdoo extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'odoo_id', 'company_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'user_id', 'odoo_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
