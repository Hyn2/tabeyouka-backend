<?php

namespace App\Http\Controllers;

use App\Models\Teammate;
use Illuminate\Http\Request;

class TeammatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teammates = Teammate::all();
        return response()->json($teammates->map(function ($teammate) {
            return [
                'student_id' => $teammate->student_id,
                'name' => $teammate->name,
                'profile_image' => $teammate->profile_image,
                'part' => $teammate->part,
                'description' => $teammate->description,
                'github_link' => $teammate->github_link,
            ];
        }));
    }
}
