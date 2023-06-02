<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateAddReview
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $data = $request->all();
        $fields = ['restaurant_id', 'rating', 'review_text', 'image_file'];
        foreach($fields as $fields) {
            if(empty($data[$fields])) {
                return response()->json(['error' => $fields.' is required']);
            }
        }
        return $next($request);
    }
}
