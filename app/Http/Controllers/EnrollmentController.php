<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $validated = $request->validated();

        $enrollment = new Enrollment;
        $enrollment->course_id = $validated['course_id'];
        $enrollment->user_id = $validated['user_id'];
        $enrollment->save();

        return redirect()->back()->with('success', 'Student added to course');
    }

    public function destroy(string $id)
    {
        $enrollment = Enrollment::destroy($id);

        return redirect()->back()->with('success', 'Student removed from course');
    }
}
