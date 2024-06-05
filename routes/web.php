<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentAdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeacherAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeachersController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth', 'verified', AdminMiddleware::class])->controller(TeacherAdminController::class)->group(function(){
    // Visualizar Profesores
    Route::get("/teachers/admin", 'index')->name('teacher.admin');

    // Agregar Profesores
    Route::get("/teacher/admin/add", 'showAdd')->name('teacher.admin.add');
    Route::put("/teacher/admin/add", 'create');

    // Editar Profesores
    Route::post("/teacher/admin/edit", 'update');
    Route::get("/teacher/admin/edit", 'showEdit')->name('teacher.admin.edit');
    Route::delete("/teacher/admin/edit", 'destroy');

});

Route::middleware(['auth', 'verified', AdminMiddleware::class])->controller(StudentAdminController::class)->group(function(){
    // Visualizar Alumnos
    Route::get("/students/admin", 'index')->name('student.admin');
    Route::get("/students/admin/nota", 'showNote')->name('student.admin.notas');

    // Agregar Alumnos
    Route::get("/students/admin/add", 'showAdd')->name('student.admin.add');
    Route::put("/students/admin/add", 'create');

    // Editar Alumnos
    Route::get("/students/admin/edit", 'showEdit')->name('student.admin.edit');
    Route::post("/students/admin/edit", 'update');
    Route::delete("/students/admin/edit", 'destroy');

});

Route::middleware(['auth', 'verified', AdminMiddleware::class])->controller(AdminController::class)->group(function(){
    // Visualizar Administradores
    Route::get("/administrator", 'index')->name('administrador');

    // Agregar Administradores
    Route::get("/administrator/add", 'showAdd')->name('administrador.add');
    Route::put("/administrator/add", 'create');

    // Editar Administradores
    Route::get("/administrator/edit", 'showEdit')->name('administrador.edit');
    Route::post("/administrator/edit", 'update');
    Route::delete("/administrator/edit", 'destroy');

});

Route::middleware(['auth', 'verified', TeacherMiddleware::class])->controller(TeachersController::class)->group(function(){
    // Tareas
    Route::get("/teacher/works", 'showWorks')->name('teacher.works');

    // Agregar Tareas
    Route::get("/teacher/work/add", 'showAddWork')->name('teacher.work.add');
    Route::post("/teacher/work/add", 'orderQualification');
    Route::put("/teacher/work/add", 'addWork');

    // Editar Tareas
    Route::get("/teacher/work/edit", 'showEditWork')->name('teacher.work.edit');
    Route::put("/teacher/work/edit", 'updateWork');

    // Eliminar Tareas
    Route::delete("/teacher/work/edit", 'deleteWork');

    // Método de Calificación
    Route::get("/teacher/qualification", 'showQualification')->name('teacher.qualification');
    // Agregar Calificación
    Route::get("/teacher/qualification/add", 'ShowAddQualification')->name('teacher.qualification.add');
    Route::post("/teacher/qualification/add", 'AddQualification');

    // Editar Calificación
    Route::get("/teacher/qualification/edit", 'showEditQualification')->name('teacher.qualification.edit');
    Route::post("/teacher/qualification/edit", 'updateQualification');

    // Eliminar Calificación
    Route::delete("/teacher/qualification/edit", 'deleteQualification');

    // Corregir Actividades
    Route::get("/teacher/works/students/{nameWork}", "showWorksStudents")->name('teacher.works.students');
    Route::get("/teacher/correct/student/{nameStudent}", "showCorrectWorkStudent")->name('teacher.correct');

    // Corregir Tareas
    Route::post("/teacher/correct/", "correctWork")->name('correct.work');

    // Eliminar Tareas
    Route::delete("/teacher/correct/", "deleteWorks")->name('delete.work');
    

    //Vizualizar Proyectos y Examenes
    Route::get("/teacher/exam", 'showExamAndProject')->name('teacher.exam');
    Route::get("/teacher/exam/correct/{nameWork}", 'showExamAndProjectStudents')->name('teacher.correct.exam');
    Route::post("/teacher/exam/qualification", 'qualification')->name('teacher.exam.qualification');

});

Route::middleware(['auth', 'verified', StudentMiddleware::class])->controller(StudentController::class)->group(function(){
    // Visualizar Trabajos de Estudiantes
    Route::get('/student/works', 'showWorks')->name('student.works');
    Route::get('/student/work/{name}', 'readWork')->name('student.work.show');

    // Subir Trabajos de Estudiantes
    Route::post('/student/up/work', 'upWork')->name('upWork');

    // Vizualizar Calificaciones - GENERAL
    Route::get('/student/qualificactions', 'qualification')->name('student.qualification');
    
    // Vizualizar Calificaciones - INDIVIDUAL
    Route::get('/student/qualificaction/{subject}', 'showSubject')->name('student.qualification.alone');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
