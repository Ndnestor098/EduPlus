<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('calendar');
    }

    public function show(Request $request)
    {   
        if(Cache::has('calendar')){
            $works = Cache::get('calendar');

        } else {    
            if ($request->role == 'student') {
                // Obtener los trabajos disponibles para el estudiante
                $student = Student::with('works')->where('email', auth()->user()->email)->first();
                $excludedWorkIds = $student->works->pluck('work_id');
    
                $works = Work::whereNotIn('work_type_id', [7, 6])
                    ->where('course', $student->course)
                    ->whereNotIn('id', $excludedWorkIds) // Filtrar por los id que no necesitamos
                    ->public()
                    ->get();
            }
    
            if($request->role == 'teacher'){
                // Obtener los trabajos disponibles para el profesores
                $teacher = Teacher::where('email', auth()->user()->email)->first();
                
                $works = Work::where('subject', $teacher->subject)
                    ->whereNotIn('work_type_id', [7, 6])
                    ->public() // Filtrar por trabajos públicos
                    ->get();
            }

            Cache::put('calendar', $works, now()->addMinutes(10));
        }
        

        return response()->json(['works' => $works]);
    }

    public function clearCache() {
        Cache::forget('calendar');

        return redirect()->back();
    }
}
