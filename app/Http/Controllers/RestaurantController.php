<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();

        return response()->json($restaurants->map(function ($restaurant) {
            return [
                'name' => $restaurant->name,
                'address' => $restaurant->address,
                'menu_type' => $restaurant->menu_type,
                'phone_number' => $restaurant->phone_number,
                'total_points' => $restaurant->total_points,
                'total_votes' => $restaurant->total_votes,
            ];
        }));
    }

    /**
     * Get information for a specific store by id.
     * 
     * @param int $id The id of the store.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoreById($id)
    {
        $restaurant = Restaurant::select('id', 'title', 'address', 'menu_type', 'phone_number', 'total_points', 'total_votes')->where('id', $id)->firstOrFail();

        $reviews = $restaurant->reviews()->where('restaurant_id', $restaurant->id)->select('id', 'author_id', 'restaurant_id', 'rating', 'review_text', 'image_file')->get();

        return response()->json([
            'restaurant' => $restaurant,
            'reviews' => $reviews,
        ]);
    }
}