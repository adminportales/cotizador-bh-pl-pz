<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tradename extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'tradenames';

    protected $fillable = ['client_id','name'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }
    
}
