<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $search = request()->query('search');

        $query = Project::query();
        if ($search) {
            $query->whereFullText(['name'], $search.'*', ['mode' => 'boolean'])
                ->orWhereHas('company', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%'.$search.'%');
                });
        }

        $projects = $query->with('company')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $data = [
            'paginatedData' => $projects,
        ];

        return Inertia::render('Projects/ProjectIndex', $data);
    }

    /**
     * Show the form for creating a new resource.id
     */
    public function create(): Response
    {
        $newProject = new Project;
        $newProject->start_date = now();
        $newProject->status = ProjectStatus::Active;

        return $this->edit($newProject);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $project = Project::create($validated);

        return redirect()->route('projects.edit', $project->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): Response
    {
        return $this->edit($project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): Response
    {
        $projectStatuses = Project::PROJECT_STATUSES;

        $data = [
            'project' => fn () => $this->loadProjectDetails($project),

            'companies' => Inertia::lazy($this->getSearchedCompanies(...)),

            'projectStatuses' => fn () => $this->loadProjectStatuses(),

        ];

        return Inertia::render('Projects/ProjectEdit', $data);
    }

    protected function loadProjectStatuses(): array
    {
        return Project::PROJECT_STATUSES;
    }

    protected function loadProjectDetails($project): Project
    {
        $project->load('company');
        $project->load('projectDocuments');

        return $project;
    }

    protected function getSearchedCompanies(): Collection
    {
        $companies = QueryBuilder::for(Company::class)
            ->allowedFilters('name')
            ->limit(20)
            ->orderBy('name')
            ->get();

        return $companies;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project): Response
    {
        $validated = $request->validated();

        $project->update($validated);

        return $this->show($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Project::destroy($id);

        return redirect()->route('projects.index');
    }
}
