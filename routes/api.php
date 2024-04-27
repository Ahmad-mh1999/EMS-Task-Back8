<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api'], function () {
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('user')->controller(AuthController::class)->group(function () {
        Route::get('index', 'index');
        Route::get('show/{id}', 'show');
        Route::put('update/{id}', 'update');
        Route::delete('delete/{id}', 'destroy');
    });

    /**
     * @Route("department")
     */
    Route::apiResource('department', DepartmentController::class);
    Route::prefix('department')->controller(DepartmentController::class)->group(function () {
        Route::get('showdeleteddepatment', 'showDeletedDepatment');
        Route::post('restore/{id}', 'restoreDepatment');
        Route::delete('forcedelete', 'forceDeleteDepatment');
    });

    /**
     * @Route("employee")
     */
    Route::apiResource('employee', EmployeeController::class);
    Route::prefix('employee')->controller(EmployeeController::class)->group(function () {
        Route::get('showdeletedemployee', 'showDeletedEmployee');
        Route::post('restore/{id}', 'restoreEmployee');
        Route::delete('forcedelete', 'forceDeleteEmployee');
    });

    /**
     * @Route("notes")
     */
    Route::apiResource('note', NoteController::class)->except('store');
    Route::prefix('note')->controller(NoteController::class)->group(function () {
        Route::put('storeEmployeeNote/{employee}', 'employeeNoteStore');
        Route::put('storeDepartmentNote{department}', 'departmentNoteStore');
    });

    /**
     * @Route("projects")
     */
    Route::apiResource('project', ProjectController::class);
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);




