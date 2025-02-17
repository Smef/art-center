<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseTeacherStoreRequest;
use App\Models\CourseTeacher;
use Illuminate\Http\RedirectResponse;

class CourseTeacherController extends Controller
{
    public function store(CourseTeacherStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $courseTeacher = new CourseTeacher;
        $courseTeacher->fill($validated);
        $courseTeacher->save();

        return redirect()->back();
    }

    public function destroy(string $id): RedirectResponse
    {
        CourseTeacher::destroy($id);

        return redirect()->back();
    }
}
