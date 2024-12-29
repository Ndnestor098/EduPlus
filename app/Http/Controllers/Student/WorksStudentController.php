<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkStudent;
use App\Notifications\StudentUpAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class WorksStudentController extends Controller
{
    //========================================Mostrar las Tareas========================================
    //Motras los trabajos al estudiante
    public function index(Request $request)
    {
        if($request->get('subject')){
            Cache::forget('subject');
            Cache::forget('works_student');
        }

        if(Cache::has('works_student') && Cache::has('subject')){
            $works = Cache::get('works_student');
            $subjects = Cache::get('subject');
            
        } else {
            // Obtener la información del estudiante actual y sus trabajos asociados
            $student = Student::with('works')->where('email', auth()->user()->email)->first();

            // Obtener los IDs de los trabajos excluidos (ya realizados) por el estudiante
            $excludedWorkIds = $student->works->pluck('work_id');

            // Obtener los trabajos disponibles para el estudiante
            $works = Work::with('workType')
                ->whereHas('workType', function($query){
                    $query->where("name", 'Tarea'); // Filtrar por el nombre del tipo de trabajo
                })
                ->where('course', $student->course) // Buscar las tareas del curso del estudiante
                ->whereNotIn('id', $excludedWorkIds) // Filtrar por los id que no necesitamos
                ->today() // Filtrar por trabajos asignados para hoy
                ->public() // Filtrar por trabajos públicos
                ->subject($request->input('subject')) // Filtrar por materia si se especifica
                ->get();
            
            // Obtener las materias disponibles para mostrarlas en la interfaz
            $subjects = Teacher::select('subject')
                ->orderBy('subject')
                ->distinct()
                ->get();

            Cache::put('works_student', $works, now()->addMinutes(10));
            Cache::put('subject', $subjects, now()->addMinutes(10));
        }

        // Retornar la vista con la lista de trabajos disponibles y las materias
        return view('student.works.index', ['works' => $works, 'subjects' => $subjects]);
    }

    //Lectura individual de los trabajos
    public function show(Request $request, $nameWork)
    {
        // Obtener los detalles de un trabajo específico para mostrarlos al estudiante
        $work = Work::where('slug', $nameWork)->first();

        $user = auth()->user();

        $student = Student::where('email', $user->email)->first();

        $notification = $student->notifications()->where('id', $request->notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        $date = Carbon::today();

        // Retornar la vista con los detalles del trabajo
        return view('student.works.show', ['work' => $work, 'date' => $date]);
    }

    //Subir los trabajos
    public function store(Request $request)
    {
        // Validar los archivos enviados por el estudiante
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx,xlsx|max:15420', // permite archivos PDF y documentos de Word de hasta 2MB
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:15420', // permite imágenes de hasta 2MB
        ]);

        // Guardar rutas de archivos y de imágenes
        $filePaths = [];
        $fileBool = false;
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $files) {
                if ($files->isValid()) {
                    $fileName = uniqid() . '.' . $files->getClientOriginalExtension();
                    $filePath = $files->storeAs('public/files', $fileName);
                    $filePaths[] = Storage::url($filePath);
                    $fileBool = true;
                }
            }
        }

        $imagePaths = [];
        $imageBool =false;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('public/image', $imageName);
                    $imagePaths[] = Storage::url($imagePath);
                    $imageBool = true;
                }
            }
        }

        Cache::flush();

        if($fileBool || $imageBool){
            // Obtener la información del estudiante actual
            $student = Student::where('email', auth()->user()->email)->first();
            $teacher = Teacher::where('subject', $request->subject)->first();

            // Crear un nuevo registro de trabajo realizado por el estudiante
            $work = WorkStudent::create([
                'name' => $student->name,
                'slug' => $student->name, // ¿Estás seguro de que el slug del trabajo debe ser el nombre del estudiante?
                'course' => $student->course,
                'file' => $filePaths ? json_encode($filePaths) : null,
                'image' => $imagePaths ? json_encode($imagePaths) : null,
                'student_id' => $student->id,
                'work_id' => $request->input('work_id'),
            ]);

            // Enviar notificación a al profesor
            Notification::send($teacher, new StudentUpAssignment($work));
        }

        // Redirigir al estudiante de nuevo a sus trabajos
        return redirect()->route('student.works');
    }
}