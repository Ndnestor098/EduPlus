<?php

use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\ProfesoresController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');


Route::controller(ProfesoresController::class)->group(function(){
    //===========================Visualizar Profesores===========================
    Route::get("/profesores", [ProfesoresController::class, 'index'])->name('profesores');

    //===========================Agregar Profesores===========================
    Route::get("/profesor/add", [ProfesoresController::class, 'showAdd'])->name('profesor.add');
    Route::put("/profesor/add", [ProfesoresController::class, 'create']);

    //===========================Editar Profesores===========================
    Route::post("/profesor/edit", [ProfesoresController::class, 'update']);
    Route::get("/profesor/edit", [ProfesoresController::class, 'showEdit'])->name('profesor.edit');
    Route::delete("/profesor/edit", [ProfesoresController::class, 'destroy']);

})->middleware(['auth', 'verified']);

Route::controller(AlumnosController::class)->group(function(){
    //===========================Visualizar Alumnos===========================
    Route::get("/alumnos", [AlumnosController::class, 'index'])->name('alumnos');
    Route::get("/alumno/nota", [AlumnosController::class, 'showNote'])->name('alumnos.notas');

    //===========================Agregar Alumnos===========================
    Route::get("/alumno/add", [AlumnosController::class, 'showAdd'])->name('alumno.add');
    Route::put("/alumno/add", [AlumnosController::class, 'create']);

    // //===========================Editar Alumnos===========================
    Route::get("/alumno/edit", [AlumnosController::class, 'showEdit'])->name('alumno.edit');
    Route::post("/alumno/edit", [AlumnosController::class, 'update']);
    Route::delete("/alumno/edit", [AlumnosController::class, 'destroy']);

})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
