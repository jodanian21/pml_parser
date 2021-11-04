<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property int $number
 * @property int $pizza_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Pizza[] $pizzas
 *
 * @package App\Models
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $casts = [
        'number' => 'int',
        'pizza_id' => 'int'
    ];

    protected $fillable = [
        'number',
        'pizza_id'
    ];

    public function pizzas()
    {
        return $this->hasMany(Pizza::class);
    }

    public function topping_details()
    {
        return $this->hasManyThrough(ToppingDetail::class, Pizza::class)
            ->join('mst_toppings', 'mst_toppings.id', '=', 'topping_details.mst_topping_id')
            ->select([
                'mst_toppings.id',
                'pizza_id',
                'topping_details.area',
                'mst_toppings.name'
            ])
            ->orderBy('area');
    }

    public static function getOrderList()
    {
        return self::with(['pizzas', 'topping_details']);
    }
}
