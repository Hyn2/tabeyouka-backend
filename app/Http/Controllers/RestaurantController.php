<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();

        return response()->json($restaurants->map(function ($restaurants) {
            return [
                'name' => $restaurants->name,
                'address' => $restaurant->address,
                'menu_type' => $restaurant->menu_type,
                'phone_number' => $restaurant->phone_number,
                'total_points' => $restaurant->total_points,
                'total_votes' => $restaurant->total_votes,
            ];
        }));
    }
}