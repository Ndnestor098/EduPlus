<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    //Motras los trabajos al estudiante
    public function showWorks(Request $request)
    {
        // Obtener la información del estudiante actual y sus trabajos asociados
        $student = Student::with('works')->where('email', auth()->user()->email)->first();

        // Obtener los IDs de los trabajos excluidos (ya realizados) por el estudiante
        $excludedWorkIds = $student->works->pluck('work_id');

        // Obtener los trabajos disponibles para el estudiante
        $works = Work::where('course', $student->course)
                    ->whereNotIn('id', $excludedWorkIds)
                    ->today() // Filtrar por trabajos asignados para hoy
                    ->public() // Filtrar por trabajos públicos
                    ->subject($request->get('subject')) // Filtrar por materia si se especifica
                    ->get();

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

        // Retornar la vista con los detalles del trabajo
        return view('student.works.show', compact('work'));
    }

    //Subir los trabajos
    public function upWork(Request $request)
    {
        // Validar los archivos enviados por el estudiante
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx|max:15420', // permite archivos PDF y documentos de Word de hasta 2MB
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:15420', // permite imágenes de hasta 2MB
        ]);

        // Guardar rutas de archivos y de imágenes
        $filePaths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $files) {
                if ($files->isValid()) {
                    $fileName = uniqid() . '.' . $files->getClientOriginalExtension();
                    $filePath = $files->storeAs('public/image', $fileName);
                    $filePaths[] = Storage::url($filePath);
                }
            }
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('public/image', $imageName);
                    $imagePaths[] = Storage::url($imagePath);
                }
            }
        }

        // Obtener la información del estudiante actual
        $student = Student::where('email', auth()->user()->email)->first();

        // Crear un nuevo registro de trabajo realizado por el estudiante
        WorkStudent::create([
            'name' => $student->name,
            'slug' => $student->name, // ¿Estás seguro de que el slug del trabajo debe ser el nombre del estudiante?
            'course' => $student->course,
            'file' => $filePaths ? json_encode($filePaths) : null,
            'image' => $imagePaths ? json_encode($imagePaths) : null,
            'subject' => $request->input('subject'),
            'student_id' => $student->id,
            'work_id' => $request->input('work_id'),
        ]);

        // Redirigir al estudiante de nuevo a sus trabajos
        return redirect()->route('student.works');
    }
}
