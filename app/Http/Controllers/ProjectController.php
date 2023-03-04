<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Scopes\ActiveScope;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->withoutGlobalScope(ActiveScope::class)
            ->orderBy('id')
            ->paginate();

        return view('project.index', [
            'projects' => $projects
        ]);
    }

    public function view(Project $project): View
    {
        return view('project.view', [
            'project' => $project,
        ]);
    }

    public function add(): View
    {
        return view('project.add');
    }

    public function create(CreateProjectRequest $request): RedirectResponse
    {
        Project::query()->create($request->validated());

        return Redirect::route('projects.index');
    }

    public function edit(Project $project): View
    {
        return view('project.edit', [
            'project' => $project,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->fill($request->validated());
        $project->save();

        return Redirect::back();
    }
}
