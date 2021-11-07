<?php

namespace App\Services;

use App\Models\{MstTopping, Order};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Jodan\PMLParser\PMLObject;
use RuntimeException;
use Throwable;


class OrderService
{
    /**
     * Data store
     * 
     * @param PMLObject $pmlObj
     * 
     * @return void
     */
    public function createOrder(PMLObject $pmlObj)
    {
        try {
            DB::beginTransaction();

            $orderValues = $pmlObj->getOrderFields();
            // check order number
            if (Order::where('number', $orderValues['number'])->first()) {
                throw new RuntimeException("Order Already Exists!");
            }
            // save order
            $order = Order::create($orderValues);

            $namedToppings = $pmlObj->getToppings();
            $mstToppings = MstTopping::whereIn('name', $namedToppings)->get();
            if ($mstToppings->isEmpty()) {
                throw new RuntimeException("Unknown toppings!");
            }
            
            $toppings = [];
            $pizzas = [];
            foreach ($pmlObj->getPizzaFields() as $pizza) {
                $pizzas[] = $pizza;
                $toppings[$pizza['number']] = $this->arrangeToppings($pizza['toppings'], $mstToppings);
            }
            // save all pizzas
            $pizzaRecord = $order->pizzas()->createMany($pizzas);

            $forSaving = [];
            foreach ($pizzaRecord->toArray() as $record) {
                foreach ($toppings[$record['number']] as $item) {
                    $item['pizza_id'] = $record['id'];
                    $forSaving[] = $item;
                }
            }
            // save all toppings
            DB::table('topping_details')->insert($forSaving);

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    /**
     * Extract and arrange toppings per pizza
     * 
     * @param array $toppings
     * @param Collection $mstToppings
     * 
     * @return array
     */
    private function arrangeToppings(array $toppings, Collection $mstToppings) {
        $temp = [];
        foreach ($toppings as $value) {
            foreach ($value['items'] as $item) {
                $source = $mstToppings->firstWhere('name', $item);
                $temp[] = [
                    'area' => $value['area'],
                    'mst_topping_id' => $source->id,
                ];
            }
        }

        return $temp;
    }
}