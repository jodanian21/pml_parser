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
 * Class MstTopping
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ToppingDetail[] $topping_details
 *
 * @package App\Models
 */
class MstTopping extends Model
{
	protected $table = 'mst_toppings';

	protected $fillable = [
		'name'
	];

    public function topping_details()
    {
        return $this->hasMany(ToppingDetail::class);
    }

    /**
     * Get List of Toppings and their total count
     */
    public static function getToppingsCount()
    {
        return self::leftJoin('topping_details', 'mst_toppings.id', '=', 'topping_details.mst_topping_id')
            ->select(['mst_toppings.id', 'name', DB::raw('count(topping_details.mst_topping_id) as total')])
            ->groupBy('mst_toppings.id')
            ->orderBy('total', 'DESC');
    }
}