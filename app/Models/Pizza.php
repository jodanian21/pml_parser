<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pizza
 * 
 * @property int $id
 * @property int $number
 * @property int $order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property Collection|ToppingDetail[] $topping_details
 *
 * @package App\Models
 */
class Pizza extends Model
{
	protected $table = 'pizzas';

	protected $casts = [
		'number' => 'int',
		'order_id' => 'int'
	];

	protected $fillable = [
		'number',
		'order_id',
		'size',
		'crust',
		'type'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function topping_details()
	{
		return $this->hasMany(ToppingDetail::class);
	}
}
