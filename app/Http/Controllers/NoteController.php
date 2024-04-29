<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Http\Requests\noteStoreRequest;
use App\Models\Department;
use App\Models\Employee;
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
    public function departmentNoteStore(noteStoreRequest $request , Department $department)
    {
        $note = $department->notes()->create([
            'note' => $request->note,
        ]);
        return response()->json([
            'message' => 'Created Successfully',
            'note' => $note
        ]);
    }
    public function employeeNoteStore(noteStoreRequest $request, Employee $employee)
    {
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
            // $Tnote->save();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'some error occurred',
            ]);
        }
        return response()->json([
            'message' => 'Created Successfully',
            'note' => $Tnote
        ]);
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
