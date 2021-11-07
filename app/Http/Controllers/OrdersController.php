<?php

namespace App\Http\Controllers;

use App\Models\{
    Crust,
    Order,
    MstTopping,
    Type
};
use App\Services\OrderService;
use Illuminate\Http\Request;
use Jodan\PMLParser\PMLParser;
use RuntimeException;

class OrdersController extends Controller
{
    /**
     * Display Upload File
     */
    public function index() {
        return view('order.index');
    }

    /**
     * Display manual entry of code
     */
    public function manualInput()
    {
        $toppings = MstTopping::all();

        return view('order.input', compact('toppings'));
    }

    /**
     * validate and Create submitted order string
     * 
     * @param PMLParser $parser
     * @param OrderService $service
     * 
     * @return Response JSON
     */
    public function order(PMLParser $parser, OrderService $service)
    {
        $str = request()->input('orderString');

        try {
            // parse string to PML object form
            $obj = $parser->parse($str);
            // initiate data store
            $service->createOrder($obj);

        } catch (RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Not Valid PML format!",
            ], 422);
        }

        return response()->json([
            'message' => "success",
            'data' => $obj->getOrder(),
        ]);
    }

    /**
     * Display all order list
     */
    public function list(Request $request)
    {
        $orders = Order::getOrderList($request)
            ->paginate(10)
            ->withQueryString();

        $toppings = MstTopping::getToppingsCount()->get();
        $crusts = Crust::all();
        $types = Type::all();

        return view('order.list',
            compact(
                'orders',
                'toppings',
                'crusts',
                'types'
            )
        );
    }
}
