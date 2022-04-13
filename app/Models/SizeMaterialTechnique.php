<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeMaterialTechnique extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'sizeMaterialTechniques';

    protected $fillable = ['size_id','material_technique_id'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function materialTechnique()
    {
        return $this->hasOne('App\Models\MaterialTechnique', 'id', 'material_technique_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricesTechniques()
    {
        return $this->hasMany('App\Models\PricesTechnique', 'size_material_technique_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function size()
    {
        return $this->hasOne('App\Models\Size', 'id', 'size_id');
    }
    
}
