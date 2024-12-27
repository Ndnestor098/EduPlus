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

class participationController extends Controller
{
    //========================================Participacion y Conducta========================================
    public function showParticipation(Request $request)
    {
        // Obtener todos los cursos distintos ordenados
        $course = student::select('course')->distinct()->orderBy('course')->get();

        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener informacion de los metodos calificativos
        $conductaMethods = Percentages::with('workType')
            ->whereHas("workType", function($query){
                $query->where("name", "Conducta");
            })
            ->course($request->course)
            ->where('subject', $teacher->subject)
            ->get();

        $participacionMethods = Percentages::with('workType')
            ->whereHas("workType", function($query){
                $query->where("name", "Participacion");
            })
            ->course($request->course)
            ->where('subject', $teacher->subject)
            ->get();

        $showMethod = $conductaMethods->merge($participacionMethods);

        foreach ($showMethod as $method)
        {
            if($method->workType->name == "Participacion" || $method->workType->name == "Conducta"){
                $bool = true;
                break;
            }else{
                $bool = false;
            }
        }
        
        if ($request->course) {
            $bool = true;
        }else{
            if($showMethod->isEmpty()) 
                $bool = false;
        }

        return view("teacher.participation.index", ['course' => $course, 'bool' => $bool, 'showMethod' => $showMethod]);
    }

    public function showParticipationCorrect(Request $request)
    {
        $method = Percentages::find($request->id);

        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        $students = Student::where('course', $method->course)->get();

        $work = Work::where('course', $students[0]->course)
            ->where('subject', $teacher->subject)
            ->where('work_type_id', $method->workType->id)
            ->first();

        if(!$work){
            $work = Work::create([
                'title' => 'Conducta',
                'slug' => 'Conducta',
                'description' => 'Se toma la conducta y modales del alumno dentro del aula.',
                'scored' => floatval($method->percentage),
                'public' => 1,
                'course' => $students[0]->course,
                'deliver' => date("Y-m-d"),
                'subject' => $teacher->subject,
                'work_type_id' => $method->workType->id
            ]);

            foreach($students as $student){
                WorkStudent::create([
                    'name' => $student->name,
                    'slug' => $student->name,
                    'course' => $student->course,
                    'student_id' => $student->id,
                    'work_id' => $work->id
                ]);
            }

            $students = Student::with(['works' => function($query) use ($work){
                $query->where('work_id', $work->id);
            }])
            ->whereHas('works', function($query) use ($work) {
                $query->where('work_id', $work->id);
            })
            ->where('course', $method->course)
            ->get();

        }else{
            $students = Student::with(['works' => function($query) use ($work){
                $query->where('work_id', $work->id);
            }])
            ->whereHas('works', function($query) use ($work) {
                $query->where('work_id', $work->id);
            })
            ->where('course', $method->course)
            ->get();
            
        }
        
        return view('teacher.participation.correct', ['method'=>$method, 'students'=>$students, 'work'=>$work]);
    }

    public function updateParticipation(Request $request, NoteServices $noteRequest)
    {
        $request->validate([
            'students' => 'required'
        ]);

        foreach($request->students as $search){
            if(floatval($search['note']) > 10 || floatval($search['note']) < 0){
                return redirect()->back()->with('errors', 'Error en la nota, debe ser entre 1 a 10.');
            }
            $student = student::find($search['id']);

            $studentUpdate = WorkStudent::where('student_id', $search['id'])
                ->where('work_id', $request->work)
                ->where('course', $student->course)
                ->first();
            
            $studentUpdate->qualification = floatval($search['note']);
            
            $studentUpdate->save();


            $noteRequest->updateQualification($student);
        }

        return redirect()->route("teacher.participation");
    }

    public function showMarks(Request $request)
    {
        // Obtener todos los cursos distintos ordenados
        $course = student::select('course')->distinct()->orderBy('course')->get();

        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener todos los estudiantes en orden
        $students = Qualification::with('student')
            ->selectRaw("id, student_id, {$teacher->subject} AS subject") // Asegúrate de que $teacher->subject es un campo válido en la tabla qualifications
            ->whereHas('student', function($query) use ($request){
                if($request->name){
                    $query->where('name', 'LIKE', "%$request->name%");
                }
                if($request->course){
                    $query->where('course', $request->course);
                }
            })
            ->orderBy("student_id")
            ->paginate(10);

        // Mantener los valores de las variables en la URL
        $students->appends($request->query());
        
        return view("teacher.calification.index", ['course' => $course, 'students'=>$students, 'subject'=>$teacher->subject]);
    }
}