<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
            // Eliminar la caché si se solicita explícitamente
            if ($request->has('clear_cache') && $request->clear_cache == true) {
                Cache::forget('calendar');
            }

            // Leer los trabajos de la caché si están disponibles
            if (Cache::has('calendar')) {
                $works = Cache::get('calendar');
            } else {
                // Obtener los trabajos disponibles para el estudiante
                $student = Student::with('works')->where('email', auth()->user()->email)->first();
                $excludedWorkIds = $student->works->pluck('work_id');

                $works = work::whereNotIn('work_type_id', [7, 6])
                    ->where('course', $student->course)
                    ->whereNotIn('id', $excludedWorkIds) // Filtrar por los id que no necesitamos
                    ->public()
                    ->get();

                // Guardar los trabajos en la caché por 24 horas (1440 minutos)
                Cache::put('calendar', $works, now()->addMinutes(1440));
            }

            return response()->json(['works' => $works]);
        }

        if($request->role == 'teacher'){
            // Eliminar la caché si se solicita explícitamente
            if ($request->has('clear_cache') && $request->clear_cache == true) {
                Cache::forget('calendar');
            }

            // Leer los trabajos de la caché si están disponibles
            if(Cache::has('calendar')){
                $works = Cache::get('calendar');
            } else {
                // Obtener los trabajos disponibles para el profesores
                $teacher = Teacher::where('email', auth()->user()->email)->first();
                
                $works = Work::where('subject', $teacher->subject)
                    ->whereNotIn('work_type_id', [7, 6])
                    ->public() // Filtrar por trabajos públicos
                    ->get();

                Cache::put('calendar', $works);
            }
            
            return response()->json(['works' => $works]);
        }
        
        return response()->json(['error' => 'Acceso no autorizado'], 403);
    }

}
