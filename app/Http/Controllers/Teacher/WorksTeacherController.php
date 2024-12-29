<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Percentages;
use App\Models\student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkStudent;
use App\Notifications\TeacherUpAssignment;
use App\Services\WorkServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class WorksTeacherController extends Controller
{
    //========================================Tareas========================================

    // Mostrar las tareas del profesor
    public function index(Request $request)
    {
        if(Cache::has('works_teacher') && Cache::has('method_exist') && Cache::has('course')){
            $course = Cache::get('course');
            $works = Cache::get('works_teacher');
            $methodExist = Cache::get('method_exist');
        } else {
            // Obtener todos los cursos distintos ordenados
            $course = student::select('course')->distinct()->orderBy('course')->get();

            // Obtener el profesor actualmente autenticado
            $teacher = Teacher::where('email', auth()->user()->email)->first();

            // Filtrar y obtener las tareas del profesor
            $works = Work::with('workType')
                ->where('subject', $teacher->subject)
                ->whereHas('workType', function($query){
                    $query->where('name', 'Tarea');
                })
                ->none($request->all())
                ->course($request->get('course'))
                ->get();

            // Obtener informacion de los metodos calificativos
            $showMethod = Percentages::with('workType')->where('subject', $teacher->subject)->get();
            
            $methodExist = $showMethod->some(function($value){
                return $value->workType->name == "Tarea";
            });

            Cache::put('works_teacher', $works, now()->addMinutes(10));
            Cache::put('course', $course, now()->addMinutes(500));
            Cache::put('method_exist', $methodExist, now()->addMinutes(10));
        }

        // Retornar la vista con las tareas y cursos
        return view('teacher.work.works', ['course' => $course, 'works' => $works, 'methodExist' => $methodExist]);
    }

    // Obtener el orden de calificaciones según la materia y curso
    public function orderQualification(Request $request)
    {
        //Buscar los valores del teacher para el filtrado
        $info = Teacher::where('email', auth()->user()->email)->first();

        //Filtrado de informacion de metodo calificativo
        return Percentages::with('workType')
            ->where('subject', $info->subject)
            ->where('course', $request->course)
            ->get();
    }

    // Mostrar formulario para agregar una nueva tarea
    public function create()
    {
        //Buscar los valores del teacher para el filtrado
        $info = Teacher::where('email', auth()->user()->email)->first()->subject;

        //filtrado para la lista de cursos o años
        $course = student::select('course')->orderBy('course')->distinct()->get();

        return view('teacher.work.add-work', ['info' => $info, 'course' => $course]);
    }

    // Agregar una nueva tarea
    public function store(Request $request, WorkServices $requestWork)
    {
        // Validar los datos recibidos del formulario
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'course' => 'required',
            'qualification' => 'required',
            'deliver' => 'required',
            'public' => 'required'
        ]);

        // Inicializar variables para el archivo y la imagen
        $file = null;
        $image = null;
        
        // Si se sube un archivo, procesarlo
        if($request->hasFile('files'))
        {
            $file = $requestWork->addFileWork($request);

            if(!$file) return redirect()->back()->with('errors', 'Error en la carga de los archivos.');
        }

        // Si se sube una imagen, procesarla
        if($request->hasFile('images'))
        {
            $image = $requestWork->addImageWork($request);

            if(!$image) return redirect()->back()->with('errors', 'Error en la carga de las imagenes.');
        }

        // Agregar la tarea con los datos proporcionados
        $work = $requestWork->addWork($request, $file, $image);

        Cache::flush();

        // Obtener los alumnos correspondientes
        $students = Student::where('course', $work->course)->get();

        // Enviar notificación a los alumnos
        Notification::send($students, new TeacherUpAssignment($work));

        if($request->qualification == "Examen oral" || $request->qualification == "Examen escrito" || $request->qualification == "Proyecto" || $request->qualification == "Exposicion"){
            $students = student::where('course', $request->course)->get();

            foreach ($students as $value) {
                WorkStudent::create([
                    'name' => $value->name,
                    'slug' => $value->name, 
                    'course' => $value->course,
                    'file' => null,
                    'image' => null,
                    'student_id' => $value->id,
                    'work_id' => $work->id,
                ]);
            }

            return redirect()->route('teacher.exam');
        }

        return redirect()->route('teacher.works');
    }

    // Mostrar el formulario para editar una tarea
    public function edit(Request $request)
    {        
        $work = Work::find($request->id);
        $course = student::select('course')->orderBy('course')->distinct()->get();
        $mt = $request->mt;

        return view('teacher.work.edit-work', ['work' => $work, 'course' => $course, 'mt'=>$mt]);
    }

    // Actualizar una tarea existente
    public function update(Request $request, WorkServices $requestWork)
    {
        // Validar los datos recibidos del formulario
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'course' => 'required',
            'qualification' => 'required',
            'deliver' => 'required',
            'public' => 'required'
        ]);

        // Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Inicializar variables para el archivo y la imagen
        $file = null;
        $image = null;
        
        // Si se sube un archivo, procesarlo
        if($request->hasFile('files'))
        {
            $file = $requestWork->addFileWork($request);

            if(!$file) return redirect()->back()->with('errors', 'Error en la carga de los archivos.');
        }

        // Si se sube una imagen, procesarla
        if($request->hasFile('images'))
        {
            $image = $requestWork->addImageWork($request);
            
            if(!$image) return redirect()->back()->with('errors', 'Error en la carga de los imagenes.');
        }

        // Actualizar la tarea con los datos proporcionados
        $requestWork->updateWork($request, $file, $image);

        Cache::flush();

        return redirect()->route('teacher.works');
    }

    // Eliminar una tarea
    public function destroy(Request $request)
    {
        $work = Work::find($request->id);
        $user = auth()->user();

        // Si la tarea tiene un archivo asociado, eliminarlo del almacenamiento
        if (isset($work->file)) {
            $relativePath = str_replace('storage', 'public', $work->pdf);
            Storage::delete($relativePath);
        }
    
        // Si la tarea tiene una imagen asociada, eliminarla del almacenamiento
        if (isset($work->image)) {
            $relativePath = str_replace('storage', 'public', $work->img);
            Storage::delete($relativePath);
        }

        // Eliminar la tarea de la base de datos
        $work->delete();

        Cache::flush();

        return redirect()->route('teacher.works');
    }
}