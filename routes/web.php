<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Teacher\CorrectAssignmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Teacher\ParticipationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\QualificationsController;
use App\Http\Controllers\Student\WorksStudentController;
use App\Http\Controllers\Teacher\ProjectsExamsController;
use App\Http\Controllers\Teacher\QualifyingMethodController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\Teacher\WorksTeacherController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth', 'verified'])->controller(NotificationController::class)->group(function(){
    Route::get("/notifications/show", 'ShowNotifications')->name("notification");
    Route::get("/notifications/read", 'readNotifications')->name("read.notification");
});

Route::middleware(['auth', 'verified'])->controller(CalendarController::class)->group(function(){
    Route::get('/calendar', 'index')->name('calendar');
    Route::get('/calendar/read', 'show')->name('calendar.read');
    Route::post('/calendar/clear_cache', 'clearCache')->name('calendar.clear_cache');
});

// ============================================== Admin Routes ==============================================
Route::middleware(['auth', 'verified', AdminMiddleware::class])->controller(AdminController::class)->group(function(){
    // ==================================== Admin show ====================================
    Route::get("/administrator", 'index')->name('administrator');

    // ==================================== Admin add ====================================
    Route::get("/administrator/add", 'create')->name('administrator.add');
    Route::put("/administrator/add", 'store');

    // ==================================== Admin edit ====================================
    Route::get("/administrator/edit", 'edit')->name('administrator.edit');
    Route::post("/administrator/edit", 'update');

    // ==================================== Admin destroy ====================================
    Route::delete("/administrator/edit", 'destroy')->name('administrator.destroy');

    //Vizualizar Calificaciones
    Route::get("/marks/admin/", 'qualifications')->name("administrator.qualifications");
});

Route::middleware(['auth', 'verified', AdminMiddleware::class])->controller(TeachersController::class)->group(function(){
    // ==================================== Teachers show ====================================
    Route::get("/teachers/admin", 'index')->name('teacher.admin');

    // ==================================== Teachers add ====================================
    Route::get("/teacher/admin/add", 'create')->name('teacher.admin.add');
    Route::put("/teacher/admin/add", 'store');

    // ==================================== Teachers edit ====================================
    Route::get("/teacher/admin/edit", 'edit')->name('teacher.admin.edit');
    Route::post("/teacher/admin/edit", 'update');

    // ==================================== Teachers destroy ====================================
    Route::delete("/teacher/admin/edit", 'destroy');
});

Route::middleware(['auth', 'verified', AdminMiddleware::class])->controller(StudentController::class)->group(function(){
    // ==================================== Students show ====================================
    Route::get("/students/admin", 'index')->name('student.admin');
    Route::get("/students/admin/nota", 'show')->name('student.admin.notas');

        // ==================================== Students add ====================================
    Route::get("/students/admin/add", 'create')->name('student.admin.add');
    Route::put("/students/admin/add", 'store');

    // ==================================== Students edit ====================================
    Route::get("/students/admin/edit", 'edit')->name('student.admin.edit');
    Route::post("/students/admin/edit", 'update');

    // ==================================== Students destroy ====================================
    Route::delete("/students/admin/edit", 'destroy');
});

// ============================================== Teacher Routes ==============================================
Route::middleware(['auth', 'verified', TeacherMiddleware::class])->group(function () {
    // ==================================== Works ====================================
    Route::get('/teacher/works', [WorksTeacherController::class, 'index'])->name('teacher.works');
    Route::get("/teacher/work/add", [WorksTeacherController::class, 'create'])->name('teacher.work.add');
    Route::post("/teacher/work/add", [WorksTeacherController::class, 'orderQualification']);
    Route::put("/teacher/work/add", [WorksTeacherController::class, 'store']);
    Route::get("/teacher/work/edit", [WorksTeacherController::class, 'edit'])->name('teacher.work.edit');
    Route::put("/teacher/work/edit", [WorksTeacherController::class, 'update']);
    Route::delete("/teacher/work/edit", [WorksTeacherController::class, 'destroy']);

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

// ============================================== Student Routes ==============================================
Route::middleware(['auth', 'verified', StudentMiddleware::class])->group(function(){
    // ==================================== Works ====================================
    Route::get('/student/works', [WorksStudentController::class, 'index'])->name('student.works');
    Route::get('/student/work/{name}', [WorksStudentController::class, 'show'])->name('student.work.show');
    Route::post('/student/add/work', [WorksStudentController::class, 'store'])->name('upWork');

    // ==================================== Qualifications ====================================
    Route::get('/student/qualifications', [QualificationsController::class, 'index'])->name('student.qualification');
    Route::get('/student/qualifications/{subject}', [QualificationsController::class, 'show'])->name('student.qualification.alone');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
