<?php

namespace App\Http\Controllers;

use App\Models\Percentages;
use App\Models\Qualification;
use App\Models\student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkStudent;
use App\Models\WorkType;
use App\Services\TeacherServices;
use App\Services\WorkServices;
use Illuminate\Http\Request;
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
        $work = Work::where('teacher_id', $teacher->id)
            ->none($request->all())
            ->course($request->get('course'))
            ->get();

        // Retornar la vista con las tareas y cursos
        return view('teacher.work.works', ['course' => $course, 'work' => $work]);
    }

    // Mostrar formulario para agregar una nueva tarea
    public function showAddWork()
    {
        $info = Teacher::where('email', auth()->user()->email)->first()->subject;
        $course = student::select('course')->orderBy('course')->distinct()->get();

        return view('teacher.work.add-work', ['info' => $info, 'course' => $course]);
    }

    // Obtener el orden de calificaciones según la materia y curso
    public function orderQualification(Request $request)
    {
        return Percentages::with('workType')->where('subject', $request->subject)->where('course', $request->course)->get();
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
        if($request->hasFile('file'))
        {
            $file = $requestWork->addFileWork($request);

            if(!$file) return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Si se sube una imagen, procesarla
        if($request->hasFile('image'))
        {
            $image = $requestWork->addImageWork($request);

            if(!$image) return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Agregar la tarea con los datos proporcionados
        $requestWork->addWork($request, $file, $image);

        return redirect()->route('teacher.works');
    }

    // Mostrar el formulario para editar una tarea
    public function showEditWork(Request $request)
    {        
        $work = Work::find($request->id);
        $course = student::select('course')->orderBy('course')->distinct()->get();

        return view('teacher.work.edit-work', ['work' => $work, 'course' => $course]);
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
        if($request->hasFile('file'))
        {
            $file = $requestWork->addFileWork($request);

            if(!$file) return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Si se sube una imagen, procesarla
        if($request->hasFile('image'))
        {
            $image = $requestWork->addImageWork($request);

            if(!$image) return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Actualizar la tarea con los datos proporcionados
        $requestWork->updateWork($request, $file, $image);

        return redirect()->route('teacher.works');
    }

    // Eliminar una tarea
    public function deleteWork(Request $request)
    {
        $work = Work::find($request->id);

        // Si la tarea tiene un archivo asociado, eliminarlo del almacenamiento
        if (isset($work->file)) {
            $relativePath = str_replace('/storage/', '', $work->pdf);
            Storage::delete($relativePath);
        }
    
        // Si la tarea tiene una imagen asociada, eliminarla del almacenamiento
        if (isset($work->image)) {
            $relativePath = str_replace('/storage/', '', $work->img);
            Storage::delete($relativePath);
        }

        // Eliminar la tarea de la base de datos
        $work->delete();

        return redirect()->route('teacher.works');
    }

    //========================================Método de Calificaciones========================================

    // Mostrar las calificaciones
    public function showQualification(Request $request)
    {
        $teacher = Teacher::where('email', auth()->user()->email)->first();
        $course = student::select('course')->distinct()->orderBy('course')->get();

        $percentages = Percentages::with('workType')
            ->where('teacher_id', $teacher->id)
            ->course($request->get("course"))
            ->none($request->all())
            ->get();

        // Calcular el valor total de los porcentajes
        $valor = 0;
        foreach ($percentages as $key) {
            $valor += intval($key->percentage);
        }

        // Retornar la vista con las calificaciones y cursos
        return view('teacher.qualification.qualification', ['all' => $percentages, 'course' => $course, 'valor'=>$valor]);
    }

    // Mostrar el formulario para agregar una nueva calificación
    public function ShowAddQualification()
    {
        $metodos = WorkType::all();
        $course = student::select('course')->orderBy('course')->distinct()->get();
        $subject = Teacher::where('email', auth()->user()->email)->first()->subject;

        return view('teacher.qualification.add-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course]);
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

        return view('teacher.qualification.edit-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course, 'search' => $search]);
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
        Percentages::find($request->search)->delete();

        return redirect()->route('teacher.qualification');
    }


    //========================================Corregir las Tareas========================================
    // Mostrar las tareas de los estudiantes
    public function showWorksStudents(Request $request, $nameWork)
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
            ->where('teacher_id', $teacher->id)
            ->first();

        // Retornar la vista con los detalles de la tarea y los estudiantes
        return view('teacher.worksStudents.index', ['studentWorks'=>$studentWorks]); 
    }

    // Mostrar la tarea de un estudiante específico para su corrección
    public function showCorrectWorkStudent(Request $request, $nameStudent)
    {
        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener los detalles de la tarea del estudiante específico
        $studentWork = WorkStudent::with(['work'=>
            function($query) use ($teacher){
                $query->where('teacher_id', $teacher->id);
            }])
            ->where('slug', $nameStudent)
            ->first();

        // Retornar la vista con los detalles de la tarea del estudiante
        return view('teacher.worksStudents.correct', ['student'=>$studentWork]); 
    }

    // Corregir la tarea de un estudiante
    public function correctWork(Request $request)
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

        // Redirigir de vuelta a la página de las tareas de los estudiantes
        return redirect()->route("teacher.works.students", ['nameWork'=>$request->slug]);
    }

    // Eliminar una tarea de un estudiante
    public function deleteWorks(Request $request)
    {
        // Obtener la tarea del estudiante
        $work = WorkStudent::find($request->workStudent_id);

        // Eliminar las imágenes asociadas a la tarea
        foreach(json_decode($work->image, true) as $item){
            $relativePath = str_replace('/storage/', '', $item);
            Storage::delete($relativePath);
        }

        // Eliminar la tarea del estudiante
        $work->delete();

        // Redirigir de vuelta a la página de las tareas de los estudiantes
        return redirect()->route("teacher.works.students", ['nameWork'=>$request->slug]);
    }
}
