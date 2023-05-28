<?php

namespace App\Http\Controllers;

use App\Models\Teammate;
use Illuminate\Http\Request;

class TeammateController extends Controller
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

    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'name' => 'required',
            'profile_image' => 'required',
            'part' => 'required',
            'description' => 'required',
            'github_link' => 'required',
        ]);

        $teammate = new Teammate([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'profile_image' => $request->profile_image,
            'part' => $request->part,
            'description' => $request->description,
            'github_link' => $request->github_link,
        ]);
        $teammate->save();

        return response()->json($teammate);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $teammate = Teammate::findOrFail($id);

        $request->validate([
            'student_id' => 'required',
            'name' => 'required',
            'profile_image' => 'required',
            'part' => 'required',
            'description' => 'required',
            'github_link' => 'required',
        ]);

        $teammate_data = [
            'student_id' => $request->student_id,
            'name' => $request->name,
            'profile_image' => $request->profile_image,
            'part' => $request->part,
            'description' => $request->description,
            'github_link' => $request->github_link,
        ];
        $teammate->update($teammate_data);

        return response()->json($teammate);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @param \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teammate = Teammate::findOrFail($id);
        $teammate->delete();

        return response()->json(['message' => 'Teammate successfully deleted']);
    }
}
