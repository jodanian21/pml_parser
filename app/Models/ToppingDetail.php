<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ToppingDetail
 * 
 * @property int $id
 * @property int $area
 * @property int $pizza_id
 * @property int $mst_topping_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MstTopping $mst_topping
 * @property Pizza $pizza
 *
 * @package App\Models
 */
class ToppingDetail extends Model
{
	protected $table = 'topping_details';

	protected $casts = [
		'area' => 'int',
		'pizza_id' => 'int',
		'mst_topping_id' => 'int'
	];

	protected $fillable = [
		'area',
		'pizza_id',
		'mst_topping_id'
	];

	public function mst_topping()
	{
		return $this->belongsTo(MstTopping::class);
	}

	public function pizza()
	{
		return $this->belongsTo(Pizza::class);
	}
}
