<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'front_page',
        'back_page',
        'background',
        'have_back_page',
        'logo',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
