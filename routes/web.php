<?php

use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfesoresController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware(['auth', 'verified'])->name('home');


Route::controller(ProfesoresController::class)->group(function(){
    //===========================Visualizar Profesores===========================
    Route::get("/profesores", [ProfesoresController::class, 'index'])->name('profesores')->middleware(['auth', 'verified']);

    //===========================Agregar Profesores===========================
    Route::get("/profesor/add", [ProfesoresController::class, 'showAdd'])->name('profesor.add')->middleware(['auth', 'verified']);
    Route::put("/profesor/add", [ProfesoresController::class, 'create'])->middleware(['auth', 'verified']);

    //===========================Editar Profesores===========================
    Route::post("/profesor/edit", [ProfesoresController::class, 'update'])->middleware(['auth', 'verified']);
    Route::get("/profesor/edit", [ProfesoresController::class, 'showEdit'])->name('profesor.edit')->middleware(['auth', 'verified']);
    Route::delete("/profesor/edit", [ProfesoresController::class, 'destroy'])->middleware(['auth', 'verified']);

});

Route::controller(AlumnosController::class)->group(function(){
    //===========================Visualizar Alumnos===========================
    Route::get("/alumnos", [AlumnosController::class, 'index'])->name('alumnos')->middleware(['auth', 'verified']);
    Route::get("/alumno/nota", [AlumnosController::class, 'showNote'])->name('alumnos.notas')->middleware(['auth', 'verified']);

    //===========================Agregar Alumnos===========================
    Route::get("/alumno/add", [AlumnosController::class, 'showAdd'])->name('alumno.add')->middleware(['auth', 'verified']);
    Route::put("/alumno/add", [AlumnosController::class, 'create'])->middleware(['auth', 'verified']);

    // //===========================Editar Alumnos===========================
    Route::get("/alumno/edit", [AlumnosController::class, 'showEdit'])->name('alumno.edit')->middleware(['auth', 'verified']);
    Route::post("/alumno/edit", [AlumnosController::class, 'update'])->middleware(['auth', 'verified']);
    Route::delete("/alumno/edit", [AlumnosController::class, 'destroy'])->middleware(['auth', 'verified']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
