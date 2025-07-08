<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Lesture;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class LestureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = Lesture::paginate(1000);
        return view('lecture.index', compact('datas'));
    }
    // public function json()
    // {
    //     $datas = Lesture::paginate(1000);
    //     return DataTables::of($datas)->make(true);
    // }
    public function json()
    {
        try {
            return DataTables::of(Lesture::query())->make(true);
        } catch (\Exception $e) {
            Log::error('Lesture JSON Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function serverside()
    {
        return view('lecture.server');
    }
    /**
     * Show the form for creating a new resource.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lecture_name' => 'required',
        ]);
        $name = $request->input('lecture_name');
        if (Lesture::where('lecture_name', $name)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture already exists'
            ], 409);
        }
        Lesture::create($request->only(['lecture_name']));
        return response()->json([
            'status' => 'success',
            'message' => 'Lecture created successfully'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesture $lesture, $id)
    {
        $lecture = Lesture::findOrFail($id);
        return response()->json($lecture);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'lecture_name' => 'required',
        ]);
        $lecture = Lesture::findOrFail($id);
        $lecture->update($request->only(['lecture_name']));
        return response()->json([
            'status' => 'success',
            'message' => 'Lecture updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $lecture = Lesture::findOrFail($id);
            $lecture->delete();
            return redirect()->back()->with('success', 'Lecture deleted successfully');
        } catch (\Exception $e) {
            Log::error('Lesture Delete Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete lecture'], 500);
        }
    }
}
