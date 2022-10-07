<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformationOdoo extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'odoo_id', 'company_id'];
}
