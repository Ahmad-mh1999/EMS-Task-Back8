<?php

namespace App\Http\Controllers;

use App\Http\Requests\projectStoreRequest;
use App\Http\Requests\projectUpdateRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('employees')->get();
        return response()->json([
            'project' => $projects,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(projectStoreRequest $request)
    {
        try {
            $project = Project::create([
                'name' => $request->name,
            ]);
            if ($request->has('employee_id')) {
                $project->employees()->attach($request->employee_id);
            }
            $employee = $project->employees;
            $project->save();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'some error occurred'
            ], 500);
        }
        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project,
            'employee' => $employee,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {   
        
        if ($project) {
            $employee =$project::with('employees');
            
            return response()->json([
                'project' => $project,
                'employees' => $employee,
            ], 200);
        } else {
            return response()->json([
                'message' => 'project not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(projectUpdateRequest $request, Project $project)
    {


        try {
            $project->name = $request->input('name') ?? $project->name;
            if ($request->has('employee_ids')) {
                $project->employees()->sync($request->employee_ids);
            }
            $project->save();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'some error occurred'
            ], 500);
        }
        return response()->json([
            'message' => 'Project updated successfully',
            'project' => $project,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if(!$project)
        {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }else
        {
            $project->employees()->detach();
            $project->delete();
        return response()->json([
            'message' => 'Project deleted successfully',
            'project' => $project,
        ], 200);
        }
        
    }
}
