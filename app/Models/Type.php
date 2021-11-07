<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Type
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models
 */
class Type extends Model
{
	protected $table = 'types';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];
}
