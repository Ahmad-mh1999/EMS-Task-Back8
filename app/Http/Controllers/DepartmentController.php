<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        return response()->json([
            'departments' => $departments
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $department = Department::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Departments created successfully',
                'department' => $department
            ],200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'error in create',
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response()->json([
            'message' => 'Success',
            'department' => $department
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentUpdateRequest $request, Department $department)
    {
        try {
            $department->name = $request->input('name') ?? $department->name;
            $department->description = $request->input('description') ?? $department->description;

            $department->save();
            
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'some error occurred'
            ],500);
        }
        return response()->json([
            'message' => 'Departments updated successfully',
            'department after update' => $department
        ],200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {   
        if(!$department)
        {
            return response()->json([
                'message' => 'Department not found',
            ],404);
        }
        $department->delete();
        return response()->json([
            'message' => 'successfully soft deleted',
        ],200);
    }

    public function showDeletedDepartment()
    {
        $softDeletedDepartments = Department::onlyTrashed()->get();
    
    return response()->json(['soft_deleted_Departments' => $softDeletedDepartments]);

    }
    

    public function restoreDepatment(string $id)
    {
        try{
            $department = Department::withTrashed()->findOrFail($id);
            $department->restore();
            return response()->json(['message' => 'Department restored']);
        }catch(\Throwable $th)
        {
            return response()->json(['message' => 'Department not found'], 404);
        }

    }


    public function forceDeleteDepartment($id)
{
    $department = Department::withTrashed()->find($id);
    
    if (!$department) {
        return response()->json(['message' => 'Department not found'], 404);
    }

    $department->forceDelete();
    
    return response()->json(['message' => 'Department permanently deleted']);
}
}
