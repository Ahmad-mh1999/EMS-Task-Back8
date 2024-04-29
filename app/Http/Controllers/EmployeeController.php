<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class EmployeeController extends Controller
{
    
    use SoftDeletes;
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $employees = Employee::all();
        $employees = Employee::with('projects')->get();
        return response()->json([
            'employees' => $employees
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $employee =  Employee::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'position' => $request->position,
                'department_id' => $request->department_id,
            ]);
            if ($request->has('project_id'))
            {
                $employee->projects()->attach($request->project_id);
                $project = $employee->projects;
            }
            
            if ($request->note)
            {
                $employee->notes()->create([
                    'note' => $request->note,
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'some error occurred'
            ],500);
        }
        return response()->json([
            'message' => 'successfully created employee',
            'employee data ' => $employee,
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {   
        $department= Department::find($employee->department_id);
        $project = $employee->projects;
        return response()->json([
            'Employee id' => $employee->id,
            'Employee full name' => $employee->full_name,
            'Employee email' => $employee->email,
            'Employee postion' => $employee->position,
            'Employee Department' => $department->name,
            'Employee Project' => $project
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        try {
            $employee->first_name = $request->input('first_name') ?? $employee->first_name;
            $employee->last_name = $request->input('last_name') ?? $employee->last_name;
            $employee->email = $request->input('email') ?? $employee->email;
            $employee->position = $request->input('position') ?? $employee->position;
            $employee->department_id = $request->input('department_id') ?? $employee->department_id;
            if ($request->has('project_id'))
            {
                $employee->projects()->sync($request->project_id);
            }
            $employee->save();
        
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'some error occurred'
            ],500);
        }
        return response()->json([
            'message' => 'successfully updated',
            'employee data after update ' => $employee,
            'project' => $employee->projects(),
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {   
        if(!$employee)
        {
            return response()->json([
                'message' => 'employee not found',
            ],404);
        }
        $employee->delete();
        return response()->json([
            'message' => 'successfully soft deleted',
        ],200);
    }

    public function showSoftDeleted()
    {
        $softDeletedEmployees = Employee::onlyTrashed()->get();
        return response()->json(['soft_deleted_employees' => $softDeletedEmployees]);
    }

    public function restoreEmployee(string $id)
    {
        try{
            $employee = Employee::withTrashed()->findOrFail($id);
            $employee->restore();
            return response()->json(['message' => 'Employee restored']);
        }catch(\Throwable $th)
        {
            return response()->json(['message' => 'Employee not found'], 404);
        }

    }

    public function forceDeleteEmployee($id)
{
    $employee = Employee::withTrashed()->find($id);
    
    if (!$employee) {
        return response()->json(['message' => 'employee not found'], 404);
    }
    else{
        $employee->projects()->detach();
        $employee->forceDelete();
        
        return response()->json(['message' => 'employee permanently deleted']);
    
    }
  }
}
