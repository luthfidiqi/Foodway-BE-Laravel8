<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10);
        return response()->json([
            'data' => $orders->toArray()
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food' => 'required',
            'amount' => 'required',
            'price' => 'required',
            'notes' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $order = Order::create($request->all());

        return response()->json([
            'data' => $order
        ]);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'data' => $order
        ]);
    }

    public function update(Request $request, $id)
    { 
        $validator = Validator::make($request->all(), [
            'food' => 'required',
            'amount' => 'required',
            'price' => 'required',
            'notes' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $order->update($request->all());

        return response()->json([
            'data' => $order
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'message' => 'Order deleted'
        ], 204);
    }
}
