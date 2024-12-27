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

class projectsExamsController extends Controller
{
    //========================================Proyectos y Examenes========================================
    // Mostrar las tareas del profesor
    public function showExamAndProject(Request $request)
    {
        // Obtener todos los cursos distintos ordenados
        $course = student::select('course')->distinct()->orderBy('course')->get();

        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Filtrar y obtener las tareas del profesor
        $projectWorks = Work::with('workType')
            ->where('subject', $teacher->subject)
            ->whereHas('workType', function ($query) {
                $query->where("name", "Proyecto");
            })
            ->none($request->all())
            ->course($request->get('course'))
            ->get();

        $exposicionWorks = Work::with('workType')
            ->where('subject', $teacher->subject)
            ->whereHas('workType', function ($query) {
                $query->where("name", "Exposicion");
            })
            ->none($request->all())
            ->course($request->get('course'))
            ->get();

        // Consulta para obtener los trabajos que son exámenes
        $examWorks = Work::with('workType')
            ->where('subject', $teacher->subject)
            ->whereHas('workType', function ($query) {
                $query->where("name", "like", "%Examen%");
            })
            ->none($request->all())
            ->course($request->get('course'))
            ->get();

        // Combina los resultados en una sola colección
        $work = $projectWorks->merge($exposicionWorks)->merge($examWorks);
        
        $bool = false;

        foreach ($work as $value) {
            foreach ($value->students as $item) {
                if(!$item->qualification){
                    $bool = true;
                }
            }
        }

        // Obtener informacion de los metodos calificativos
        $showMethod = Percentages::with('workType')->where('subject', $teacher->subject)->get();

        foreach ($showMethod as $method)
        {
            if($method->workType->name == "Examen oral" || $method->workType->name == "Proyecto" || $method->workType->name == "Examen escrito"){
                $boolIf = true;
                break;
            }else{
                $boolIf = false;
            }
        }
        if($showMethod->isEmpty()) 
                $bool = false;

        // Retornar la vista con las tareas y cursos
        return view('teacher.exam_project.index', ['course' => $course, 'work' => $work, 'bool' => $bool, 'boolIf' => $boolIf]);
    }

    // Mostrar las tareas de los estudiantes
    public function showExamAndProjectStudents(Request $request, $nameWork)
    {
        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener los detalles de la tarea y los estudiantes asociados
        $work = Work::with(['students' => 
            function ($query) use ($request) {
                // Si se proporciona un nombre, filtrar los estudiantes por ese nombre
                if (isset($request->name)) {
                    $query->where('name', 'like', "%$request->name%");
                }
            }])
            ->where('slug', $nameWork)
            ->where('subject', $teacher->subject)
            ->first();
        
        $students = WorkStudent::where('course', $work->course)
            ->where('work_id', $work->id)
            ->where('name', 'like', "%$request->name%")
            ->get();

        // Retornar la vista con los detalles de la tarea y los estudiantes
        return view('teacher.exam_project.correct', ['work'=>$work, 'students'=>$students]); 
    }

    public function qualification(Request $request, NoteServices $noteRequest)
    {
        $request->validate([
            'students' => 'required'
        ]);

        $work = json_decode($request->work, true)['id'];

        foreach($request->students as $search){
            if(floatval($search['note']) > 10 || floatval($search['note']) < 0){
                return redirect()->back()->with('errors', 'Error en la nota, debe ser entre 1 a 10.');
            }
            $studentUpdate = WorkStudent::where('student_id', $search['id'])
                ->where('work_id', $work)
                ->first();

            $studentUpdate->qualification = floatval($search['note']);
            
            $studentUpdate->save();

            $student = student::find($search['id']);

            $noteRequest->updateQualification($student);
        }

        return redirect()->route('teacher.exam');
    }
}