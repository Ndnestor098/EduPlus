<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\correctAssignmentController;
use App\Http\Controllers\StudentAdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\participationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\projectsExamsController;
use App\Http\Controllers\qualifyingMethodController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\worksController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth', 'verified'])->controller(NotificationController::class)->group(function(){
    Route::get("/notifications/show", 'ShowNotifications')->name("notification");
    Route::get("/notifications/read", 'readNotifications')->name("read.notification");
    Route::get('/calendar', 'showCalendar')->name('calendar');
    Route::get('/calendar/read', 'readCalendar');
});

Route::middleware(['auth', 'verified', AdminMiddleware::class])->controller(TeachersController::class)->group(function(){
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

    //Vizualizar Calificaciones
    Route::get("/marks/admin/", 'showMarks')->name("admin.calification");
});


// ============================================== Teacher Routes ==============================================
Route::middleware(['auth', 'verified', TeacherMiddleware::class])->group(function () {
    // ==================================== Works ====================================
    Route::get('/teacher/works', [worksController::class, 'index'])->name('teacher.works');
    Route::get("/teacher/work/add", [worksController::class, 'create'])->name('teacher.work.add');
    Route::post("/teacher/work/add", [worksController::class, 'orderQualification']);
    Route::put("/teacher/work/add", [worksController::class, 'store']);
    Route::get("/teacher/work/edit", [worksController::class, 'edit'])->name('teacher.work.edit');
    Route::put("/teacher/work/edit", [worksController::class, 'update']);
    Route::delete("/teacher/work/edit", [worksController::class, 'destroy']);

    // ==================================== Qualifications Method ====================================
    // Método de Calificación
    Route::get("/teacher/qualification", [qualifyingMethodController::class, 'index'])->name('teacher.qualification');
    Route::get("/teacher/qualification/add", [qualifyingMethodController::class, 'create'])->name('teacher.qualification.add');
    Route::post("/teacher/qualification/add", [qualifyingMethodController::class, 'store']);
    Route::get("/teacher/qualification/edit", [qualifyingMethodController::class, 'edit'])->name('teacher.qualification.edit');
    Route::post("/teacher/qualification/edit", [qualifyingMethodController::class, 'update']);
    Route::delete("/teacher/qualification/edit", [qualifyingMethodController::class, 'destroy']);

    // ==================================== Correct Assignments ====================================
    Route::get("/teacher/works/students/{nameWork}", [correctAssignmentController::class, 'index'])->name('teacher.works.students');
    Route::get("/teacher/correct/student/{nameStudent}", [correctAssignmentController::class, 'show'])->name('teacher.correct');
    Route::post("/teacher/correct/", [correctAssignmentController::class, 'update'])->name('correct.work');
    Route::delete("/teacher/correct/", [correctAssignmentController::class, 'destroy'])->name('delete.work');

    // ==================================== Projects and Exams ====================================
    Route::get("/teacher/exam", [projectsExamsController::class, 'index'])->name('teacher.exam');
    Route::get("/teacher/exam/correct/{nameWork}", [projectsExamsController::class, 'show'])->name('teacher.correct.exam');
    Route::post("/teacher/exam/qualification", [projectsExamsController::class, 'store'])->name('teacher.exam.qualification');

    // ==================================== Participation ====================================
    Route::get("/teacher/participation/", [participationController::class, 'index'])->name("teacher.participation");
    Route::get("/teacher/participation/correct", [participationController::class, 'edit'])->name("teacher.participation.correct");
    Route::post("/teacher/participation/correct", [participationController::class, 'update']);
    Route::get("/teacher/marks/", [participationController::class, 'show'])->name("teacher.marks");
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
