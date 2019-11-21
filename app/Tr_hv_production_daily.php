<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_hv_production_daily extends Model
{
	protected $table = 'tr_hv_production_daily';
    protected $fillable = array(
    	'plnt',
    	'block_code',
    	'created_on',
    	'quantity',
		'bun'
   	);
	protected $primaryKey = 'plnt';
    //protected $hidden = ['updated_at','created_at'];
}
