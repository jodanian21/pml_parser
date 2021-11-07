<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Crust
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models
 */
class Crust extends Model
{
	protected $table = 'crusts';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];
}
