<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseUpdateRequest;
use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Enrollment;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $search = request()->query('filter')['name'] ?? null;

        $courses = Course::with(['location'])
            ->when($search, function ($query, $search) {
                // trim any stars from the search, which can cause errors
                $search = str_replace('*', '', $search);

                // explode, then implode to append a * at the end of each word for wildcard searching
                $searchArray = explode(' ', $search);
                $searchString = implode('* ', $searchArray).'*';

                // Search processing
                return $query->whereFullText('name', $searchString, ['mode' => 'boolean']);
            })->paginate(20)->withQueryString();

        $data = [
            'paginatedData' => $courses,
        ];

        return Inertia::render('Courses/CourseIndex', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return $this->edit(new Course);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $course = new Course($validated);
        $course->save();

        return redirect()->route('courses.show', $course->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): Response
    {
        return $this->edit($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): Response
    {
        $course->load(['location']);

        $courseTeachers = $course->courseTeachers()->with('teacher')->get();
        $enrollments = $course->enrollments()->with('student')->get();

        $data = [
            'course' => $course,
            'courseTeachers' => $courseTeachers,
            'enrollments' => $enrollments,
            'locations' => fn () => Location::orderBy('name')->get(),
            'teachers' => Inertia::lazy(fn () => $this->searchTeachers(request()->query('search-teachers', ''))),
        ];

        return Inertia::render('Courses/CourseEdit', $data);
    }

    protected function searchTeachers($search)
    {
        $search = request()->query('search-teachers', '');

        return User::query()
            ->where('name_first', 'like', $search.'%')
            ->orWhere('name_last', 'like', $search.'%')
            ->limit(10)
            ->orderBy('name_first')
            ->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseUpdateRequest $request, Course $course): Response
    {
        $validated = $request->validated();
        $course->update($validated);

        return $this->show($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('courses.index');
    }

    /**
     * Get locations for the search dropdown
     */
    protected function getSearchedLocations(): mixed
    {
        return QueryBuilder::for(Location::class)
            ->allowedFilters('name')
            ->limit(10)
            ->orderBy('name')
            ->get();
    }
}
