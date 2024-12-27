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

class TeachersController extends Controller
{
    //========================================Tareas========================================

    // Mostrar las tareas del profesor
    public function showWorks(Request $request)
    {
        // Obtener todos los cursos distintos ordenados
        $course = student::select('course')->distinct()->orderBy('course')->get();

        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Filtrar y obtener las tareas del profesor
        $work = Work::with('workType')
            ->where('subject', $teacher->subject)
            ->whereHas('workType', function($query){
                $query->where('name', 'Tarea');
            })
            ->none($request->all())
            ->course($request->get('course'))
            ->get();
        
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
            if($method->workType->name == "Tarea"){
                $boolIf = true;
                break;
            }else{
                $boolIf = false;
            }
        }
        
        // Retornar la vista con las tareas y cursos
        return view('teacher.work.works', ['course' => $course, 'work' => $work, 'bool' => $bool, 'boolIf'=>$boolIf]);
    }

    // Mostrar formulario para agregar una nueva tarea
    public function showAddWork()
    {
        //Buscar los valores del teacher para el filtrado
        $info = Teacher::where('email', auth()->user()->email)->first()->subject;

        //filtrado para la lista de cursos o años
        $course = student::select('course')->orderBy('course')->distinct()->get();

        return view('teacher.work.add-work', ['info' => $info, 'course' => $course]);
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

    // Agregar una nueva tarea
    public function addWork(Request $request, WorkServices $requestWork)
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
    public function showEditWork(Request $request)
    {        
        $work = Work::find($request->id);
        $course = student::select('course')->orderBy('course')->distinct()->get();
        $mt = $request->mt;

        return view('teacher.work.edit-work', ['work' => $work, 'course' => $course, 'mt'=>$mt]);
    }

    // Actualizar una tarea existente
    public function updateWork(Request $request, WorkServices $requestWork)
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
    public function deleteWork(Request $request)
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

    //========================================Método de Calificaciones========================================

    // Mostrar las calificaciones
    public function showQualification(Request $request)
    {
        $teacher = Teacher::where('email', auth()->user()->email)->first();
        $course = student::select('course')->distinct()->orderBy('course')->get();

        $percentages = Percentages::with('workType')
            ->where('subject', $teacher->subject)
            ->course($request->get("course"))
            ->none($request->all())
            ->get();

        // Calcular el valor total de los porcentajes
        $valor = 0;
        foreach ($percentages as $key) {
            $valor += intval($key->percentage);
        }

        // Retornar la vista con las calificaciones y cursos
        return view('teacher.method.qualification', ['all' => $percentages, 'course' => $course, 'valor'=>$valor]);
    }

    // Mostrar el formulario para agregar una nueva calificación
    public function ShowAddQualification()
    {
        $metodos = WorkType::all();
        $course = student::select('course')->orderBy('course')->distinct()->get();
        $subject = Teacher::where('email', auth()->user()->email)->first()->subject;

        return view('teacher.method.add-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course]);
    }

    // Agregar una nueva calificación
    public function AddQualification(Request $request, TeacherServices $requestTeacher)
    {
        // Validar los datos recibidos del formulario
        $validator = Validator::make($request->all(), [
            'workType' => 'required',
            'percentage' => 'required',
            'course' => 'required',
        ]);

        // Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Verificar que el porcentaje no exceda el límite permitido
        if($requestTeacher->countPercentage($request)){
            return redirect()->back()->with('errors', 'Te has pasado el límite de porcentaje de evaluación.');
        }

        // Verificar que el tipo de trabajo no esté ya en uso
        if($requestTeacher->existWork($request)){
            return redirect()->back()->with('errors', 'El Método Calificativo ya está en uso.');
        }

        // Crear el nuevo porcentaje
        $requestTeacher->createPercentage($request);

        return redirect()->route('teacher.qualification');
    }

    // Mostrar el formulario para editar una calificación
    public function showEditQualification(Request $request)
    {
        $metodos = WorkType::all();
        $course = student::select('course')->orderBy('course')->distinct()->get();
        $subject = Teacher::where('email', auth()->user()->email)->first()->subject;
        $search = Percentages::with('WorkType')->find($request->search);

        return view('teacher.method.edit-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course, 'search' => $search]);
    }

    // Actualizar una calificación existente
    public function updateQualification(Request $request, TeacherServices $requestTeacher)
    {
        // Validar los datos recibidos del formulario
        $validator = Validator::make($request->all(), [
            'workType' => 'required',
            'percentage' => 'required',
            'course' => 'required',
            'value' => 'required'
        ]);

        // Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Verificar que el porcentaje no exceda el límite permitido
        if($requestTeacher->countPercentage($request)){
            return redirect()->back()->with('errors', 'Te has pasado el límite de porcentaje de evaluación.');
        }

        // Verificar que el tipo de trabajo no esté ya en uso
        if($requestTeacher->existWork($request)){
            return redirect()->back()->with('errors', 'El Método Calificativo ya está en uso.');
        }

        // Actualizar el porcentaje
        $requestTeacher->updatePercentage($request);

        return redirect()->route('teacher.qualification');
    }

    // Eliminar una calificación
    public function deleteQualification(Request $request)
    {
        $searchPercentage = Percentages::find($request->search);
    
        Work::where('work_type_id', $searchPercentage->workType->id)->delete();

        $searchPercentage->delete();

        return redirect()->route('teacher.qualification');
    }

    

    //========================================Participacion y Conducta========================================
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

