<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
	protected $keyType = 'string';

    protected $fillable = [
    	'id',
		'sum',
		'type',
		'rent_id',
		'user_id',
    ];
}
