<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Validator;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::paginate(10);
        return response()->json([
            'data' => $restaurants->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_name' => 'required',
            'desc' => 'required',
            'location' => 'required',
            'number' => 'required',
            'email' => 'required',
            'product' => 'required',
            'reviews' => 'required',
            'photo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $restaurant = Restaurant::create($request->all());

        return response()->json([
            'data' => $restaurant
        ]);
    }

    public function show($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'message' => 'Restaurant not found'
            ], 404);
        }

        return response()->json([
            'data' => $restaurant
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_name' => 'required',
            'desc' => 'required',
            'location' => 'required',
            'number' => 'required',
            'email' => 'required',
            'product' => 'required',
            'reviews' => 'required',
            'photo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'message' => 'Restaurant not found'
            ], 404);
        }

        $restaurant->update($request->all());

        return response()->json([
            'data' => $restaurant
        ]);
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'message' => 'Restaurant not found'
            ], 404);
        }

        $restaurant->delete();

        return response()->json([
            'message' => 'Restaurant deleted'
        ], 204);
    }
}
