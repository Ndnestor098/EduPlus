<?php

namespace App\Http\Controllers;

use App\Models\Percentages;
use App\Models\Qualification;
use App\Models\student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkStudent;
use App\Models\WorkType;
use App\Notifications\TeacherUpAssignment;
use App\Services\NoteServices;
use App\Services\TeacherServices;
use App\Services\WorkServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class correctAssignmentController extends Controller
{
    //========================================Corregir las Tareas========================================
    // Mostrar las tareas de los estudiantes
    public function index(Request $request, $nameWork)
    {
        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener los detalles de la tarea y los estudiantes asociados
        $studentWorks = Work::with(['students' => 
            function ($query) use ($request) {
                // Si se proporciona un nombre, filtrar los estudiantes por ese nombre
                if (isset($request->name)) {
                    $query->where('name', 'like', "%$request->name%");
                }
            }])
            ->where('slug', $nameWork)
            ->where('subject', $teacher->subject)
            ->first();

        // Retornar la vista con los detalles de la tarea y los estudiantes
        return view('teacher.worksStudents.index', ['studentWorks'=>$studentWorks]); 
    }

    // Mostrar la tarea de un estudiante específico para su corrección
    public function show(Request $request, $nameStudent)
    {
        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener los detalles de la tarea del estudiante específico
        $studentWork = WorkStudent::with(['work' => function($query) use ($teacher, $request) {
            $query->where('subject', $teacher->subject)
                  ->where('id', $request->work_id); // Ajuste la condición aquí
        }])
        ->where('slug', $nameStudent)
        ->where('work_id', $request->work_id) // Asegúrate de que el work_id coincide
        ->first();

        $notification = $teacher->notifications()->where('id', $request->notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
        }
        
        // Retornar la vista con los detalles de la tarea del estudiante
        return view('teacher.worksStudents.correct', ['student'=>$studentWork]); 
    }

    // Corregir la tarea de un estudiante
    public function update(Request $request, NoteServices $requestNote)
    {
        // Validar la nota proporcionada
        $validator = Validator::make($request->all(), [
            'note' => 'required|numeric|between:0,10',
        ]);

        // Verificar si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Error en la calificación.');
        }

        // Actualizar la calificación de la tarea del estudiante
        $work = WorkStudent::find($request->workStudent_id);

        $work->qualification = $request->note;
        $work->save();

        $student = student::find($request->student_id);
        $requestNote->updateQualification($student);

        Cache::flush();

        // Redirigir de vuelta a la página de las tareas de los estudiantes
        return redirect()->route("teacher.works.students", ['nameWork'=>$request->slug]);
    }

    // Eliminar una tarea de un estudiante
    public function destroy(Request $request)
    {
        // Obtener la tarea del estudiante
        $work = WorkStudent::find($request->workStudent_id);

        if (!$work) {
            return redirect()->route("teacher.works.students", ['nameWork' => $request->slug])
                            ->with('error', 'Trabajo no encontrado');
        }

        // Eliminar las imágenes asociadas a la tarea
        foreach(json_decode($work->image, true) as $item){
            $relativePath = str_replace('storage', 'public', $item);
            Storage::delete($relativePath);
        }

        // Obtener el usuario actual
        $user = auth()->user();
        $teacher = Teacher::where('email', $user->email)->first();

        // Filtrar y eliminar las notificaciones asociadas a este trabajo
        $teacher->notifications->filter(function ($notification) use ($work) {
            return isset($notification->data['work']['id']) &&
                $notification->data['work']['id'] == $work->id &&
                isset($notification->data['work']['student_id']) &&
                $notification->data['work']['student_id'] == $work->student_id;
        })->each->delete();

        Cache::flush();
        

        // Eliminar la tarea del estudiante
        $work->delete();

        // Redirigir de vuelta a la página de las tareas de los estudiantes
        return redirect()->route("teacher.works.students", ['nameWork'=>$request->slug]);
    }

}