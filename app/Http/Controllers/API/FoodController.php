<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use Validator;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::paginate(10);
        return response()->json([
            'data' => $foods->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_name' => 'required',
            'restaurant' => 'required',
            'desc' => 'required',
            'location' => 'required',
            'ori_price' => 'required',
            'now_price' => 'required',
            'reviews' => 'required',
            'photo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $food = Food::create($request->all());

        return response()->json([
            'data' => $food
        ]);
    }

    public function show($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'message' => 'Food not found'
            ], 404);
        }

        return response()->json([
            'data' => $food
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'food_name' => 'required',
            'restaurant' => 'required',
            'desc' => 'required',
            'location' => 'required',
            'ori_price' => 'required',
            'now_price' => 'required',
            'reviews' => 'required',
            'photo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'message' => 'Food not found'
            ], 404);
        }

        $food->update($request->all());

        return response()->json([
            'data' => $food
        ]);
    }

    public function destroy($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'message' => 'Food not found'
            ], 404);
        }

        $food->delete();

        return response()->json([
            'message' => 'Food deleted'
        ], 204);
    }
}
