<?php

namespace App\Http\Controllers;

use App\Models\Percentages;
use App\Models\Qualification;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkStudent;
use App\Models\WorkType;
use App\Notifications\StudentUpAssignment;
use App\Services\NoteServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    //Motras los trabajos al estudiante
    public function showWorks(Request $request)
    {
        if($request->get('subject')){
            $key = 'student-'.$request->get('subject');
        } else {
            $key = 'student';
        }

        if(!Cache::has($key)){
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
                        ->subject($request->get('subject')) // Filtrar por materia si se especifica
                        ->get();

            
            Cache::get($key, $works);
        } else {
            $works = Cache::put($key);
        }
        // Obtener las materias disponibles para mostrarlas en la interfaz
        $subjects = Teacher::select('subject')
                            ->orderBy('subject')
                            ->distinct()
                            ->get();

        // Retornar la vista con la lista de trabajos disponibles y las materias
        return view('student.works.index', ['works'=>$works, 'subjects'=>$subjects]);
    }

    //Lectura individual de los trabajos
    public function readWork(Request $request, $nameWork)
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
    public function upWork(Request $request)
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

    public function qualification(NoteServices $noteRequest)
    {
        $student = Student::where('email', auth()->user()->email)->first();

        $noteRequest->updateQualification($student);

        $note = Qualification::find($student->id);
        
        return view('student.note.index', ['subjects'=>$note]);
    }

    public function showSubject($subject)
    {
        $student = Student::where('email', auth()->user()->email)->first();
        $works = Work::where('subject', $subject)->get();

        $searchID = [];
        $searchWorks = [];
        $workType = [];

        foreach ($works as $work) {
            $searchID[$work->id] = $work->id;
        }

        foreach ($searchID as $id) {
            $work = WorkStudent::with('work')->where('student_id', $student->id)->where('work_id', intval($id))->get();

            if(!$work->isEmpty())
                $searchWorks[$id] = $work;
        }

        $worksType = WorkType::all();

        foreach ($worksType as $value) {
            $workType[$value->id] = $value->name;
        }

        return view('student.note.note', ['works'=>$searchWorks, 'subject'=>$subject, 'workType'=>$workType]);
    }
}
