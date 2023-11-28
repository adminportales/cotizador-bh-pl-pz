<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'company_session',
        "visible",
        "last_login",
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companySession()
    {
        return $this->belongsTo(Company::class, 'company_session');
    }

    public function currentQuotes()
    {
        return $this->hasMany(CurrentQuote::class);
    }
    public function currentQuoteActive()
    {
        return $this->hasOne(CurrentQuote::class)->where('current_quotes.active', 1);
    }
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
    public function info()
    {
        return $this->hasMany(UserInformationOdoo::class);
    }

    public function assistants()
    {
        return $this->belongsToMany(User::class, 'user_assistant', 'user_id', 'assistant_id');
    }
    public function managments()
    {
        return $this->belongsToMany(User::class, 'user_assistant', 'assistant_id', 'user_id');
    }

    public function listProducts()
    {
        return $this->hasMany(UserProduct::class, 'user_id');
    }
}
