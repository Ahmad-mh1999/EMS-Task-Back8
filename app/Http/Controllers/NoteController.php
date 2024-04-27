<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    public function index(){
        $notes = Note::all();
        return response()->json([
            'notes' => $notes
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return response()->json([
            'notes' => $note
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function departmentNoteStore(DepartmentStoreRequest $request)
    {
        $department = Department::where('id', $request->department_id)->first();
        $note = $department->notes()->create([
            'notes' => $request->note,
        ]);
        return response()->json([
            'message' => 'Created Successfully',
            'note' => $note
        ]);
    }
    public function employeeNoteStore(Request $request)
    {
        $employee = Employee::where('id', $request->employee_id)->first();
        $note = $employee->notes()->create([
            'notes' => $request->note,
        ]);
        return response()->json([
            'message' => 'Created Successfully',
            'note' => $note
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentUpdateRequest $request, Note $Tnote) 
    {
        try {

            $Tnote -> note = $request->input('note') ?? $Tnote->note;
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'some error occurred',
            ]);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json([
            'message' => 'Note deleted successfully',
        ]);
    }
}
