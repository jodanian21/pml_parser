<?php

namespace App\Http\Controllers;

use App\Models\MstTopping;
use App\Models\Order;
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
        $toppings = MstTopping::getToppingsCount()->get();

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
    public function list()
    {
        $orders = Order::getOrderList()
            ->paginate(10);

        $toppings = MstTopping::getToppingsCount()->get();

        return view('order.list',
            compact('orders', 'toppings')
        );
    }
}
