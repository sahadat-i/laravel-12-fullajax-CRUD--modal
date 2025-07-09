<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Student.index');
    }

    public function json()
    {
        try {
            return DataTables::of(Students::query())->make(true);
        } catch (\Exception $e) {
            Log::error('Students JSON Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required',
        ]);
        $name = $request->input('student_name');
        if (Students::where('student_name', $name)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture already exists'
            ], 409);
        }
        Students::create($request->only(['student_name']));
        return response()->json([
            'status' => 'success',
            'message' => 'Student created successfully'
        ],200);

    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Students $students, $id)
    {
        $student = Students::findOrFail($id);
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_name' => 'required',
        ]);
        $student = Students::findOrFail($id);
        $student->update($request->only(['student_name']));
        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $student = Students::findOrFail($id);
            $student->delete();
            return redirect()->back()->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            Log::error('Student Delete Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete student'], 500);
        }

    }
}
