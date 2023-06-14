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
    // 전체 팀원 정보 조회
    public function index()
    {
        $teammates = Teammate::all();
        return response()->json($teammates);
    }

    // 특정 팀원 정보 조회
    public function show($id)
    {
        $teammate = Teammate::find($id);

        if ($teammate) {
            return response()->json([
                'id' => $teammate->id,
                'profile_image' => $teammate->profile_image,
                'student_id' => $teammate->student_id,
                'name' => $teammate->name,
                'part' => $teammate->part,
                'description' => $teammate->description,
                'github_link' => $teammate->github_link,
            ]);
        } else {
            return response()->json(['error' => 'Teammate not found'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    // 팀원 정보 생성
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

        if ($request->hasFile('profile_image')) {
            $imageName = $request->profile_image->store('public/images/teammates');
            $imagePath = 'http://localhost:8080/storage/images/teammates/' . basename($imageName);
        } else {
            $imageName = null;
        }

        $teammate = new Teammate([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'profile_image' => $imagePath,
            'part' => $request->part,
            'description' => $request->description,
            'github_link' => $request->github_link,
        ]);
        $teammate->save();

        return response()->json($teammate, 201);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param \Illuminate\Http\Response
     */
    // 팀원 정보 수정
    public function update(Request $request, $id)
    {
        $teammate = Teammate::findOrFail($id);

        $request->validate([
            'student_id' => 'required',
            'name' => 'required',
            // 'profile_image' => 'required',
            'part' => 'required',
            'description' => 'required',
            'github_link' => 'required',
        ]);

        if ($request->hasFile('profile_image')) {
            $imageName = $request->profile_image->store('public/images/teammates');
            $imagePath = 'http://localhost:8080/storage/images/teammates/' . basename($imageName);
        } else {
            $imagePath = $teammate->profile_image;
        }

        $teammate_data = [
            'student_id' => $request->student_id,
            'name' => $request->name,
            'profile_image' => $imagePath,
            'part' => $request->part,
            'description' => $request->description,
            'github_link' => $request->github_link,
        ];
        $teammate->update($teammate_data);

        return response()->json([
            'id' => $teammate->id,
            'student_id' => $teammate->student_id,
            'name' => $teammate->name,
            'profile_image' => $teammate->profile_image,
            'part' => $teammate->part,
            'description' => $teammate->description,
            'github_link' => $teammate->github_link,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @param \Illuminate\Http\Response
     */
    // 팀원 정보 삭제
    public function destroy($id)
    {
        $teammate = Teammate::findOrFail($id);
        $teammate->delete();

        return response()->json(['message' => 'Teammate successfully deleted']);
    }
}
