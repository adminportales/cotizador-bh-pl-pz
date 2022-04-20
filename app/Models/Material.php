<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'materials';

    protected $fillable = ['nombre', 'extras', 'slug'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materialTechniques()
    {
        return $this->hasMany('App\Models\MaterialTechnique', 'material_id', 'id');
    }
}
