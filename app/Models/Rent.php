<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
	protected $keyType = 'string';

	protected $fillable = [
		'id',
	    'duration',
		'date_of_rent',
		'status_id',
		'car_id',
		'user_id',
    ];
}
