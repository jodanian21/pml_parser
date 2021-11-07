<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'number' => 'int'
    ];

    protected $fillable = [
        'number',
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

    public static function getOrderList($params = [])
    {
        return self::select([
                'orders.id',
                'orders.number',
                DB::raw('count(topping_details.id) as total')
            ])
            ->join('pizzas', 'pizzas.order_id', '=', 'orders.id')
            ->leftJoin('topping_details', 'pizzas.id', '=', 'topping_details.pizza_id')
            ->orderBy('orders.created_at', $params['order'] ?? 'asc')
            ->groupBy('orders.id')

            // search parameters
            ->when(!empty($params['size']), function ($query) use ($params) {
                $query->where('size', $params['size']);
            })
            ->when(!empty($params['crust']), function ($query) use ($params) {
                $query->where('crust', $params['crust']);
            })
            ->when(!empty($params['type']), function ($query) use ($params) {
                $query->where('type', $params['type']);
            })
            ->when(!empty($params['toppings']), function ($query) use ($params) {
                $query->havingRaw("total >= " . $params['toppings']);
            })

            // load relationships
            ->with([
                'pizzas',
                'topping_details'
            ]);
    }
}
