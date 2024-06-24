<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function ShowNotifications()
    {
        $user = auth()->user();

        $teacher = Teacher::where('email', $user->email)->first();
        if ($teacher) {
            $notify = $teacher->notifications;
        } 

        $student = Student::where('email', $user->email)->first();
        if ($student) {
            $notify = $student->notifications;
        }

        $subject = null;
        if(Teacher::where('email', auth()->user()->email)->first()){
            $subject = Teacher::where('email', auth()->user()->email)->first()->subject;
        }

        return view('notify', ['notify' => $notify, 'subject' => $subject]);
    }


    public function readNotifications()
    {
        $user = auth()->user();

        $teacher = Teacher::where('email', $user->email)->first();
        if ($teacher) {
            return $teacher->unreadNotifications;
        } 

        $student = Student::where('email', $user->email)->first();
        if ($student) {
            return $student->unreadNotifications;
        }

        return response()->json(['length'=>0]);
    }

    public function showCalendar()
    {
        return view('calendar');
    }

    public function readCalendar(Request $request)
    {   
        if ($request->role == 'student') {
            // Obtener la información del estudiante actual y sus trabajos asociados
            $student = Student::with('works')->where('email', auth()->user()->email)->first();

            if (!$student) {
                return response()->json(['error' => 'Estudiante no encontrado'], 404);
            }

            // Obtener los IDs de los trabajos excluidos (ya realizados) por el estudiante
            $excludedWorkIds = $student->works->pluck('work_id');

            // Obtener los trabajos disponibles para el estudiante
            $works = Work::where('deliver', $request->date)
                ->whereNotIn('work_type_id', [7, 6])
                ->where('course', $student->course) // Buscar las tareas del curso del estudiante
                ->whereNotIn('id', $excludedWorkIds) // Filtrar por los id que no necesitamos
                ->public() // Filtrar por trabajos públicos
                ->limit(4)
                ->get();
            
            return response()->json([$works, 'date' => $request->date]);
        }

        if($request->role == 'teacher'){
            $teacher = Teacher::where('email', auth()->user()->email)->first();

            $works = Work::where('subject', $teacher->subject)
                ->whereNotIn('work_type_id', [7, 6])
                ->public() // Filtrar por trabajos públicos
                ->where('deliver', $request->date)
                ->limit(4)
                ->get();

            return response()->json([$works, 'date' => $request->date]);
        }
        
        return response()->json(['error' => 'Acceso no autorizado'], 403);
    }

}
