<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Percentages;
use App\Models\student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkType;
use App\Services\TeacherServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class QualifyingMethodController extends Controller
{
    //========================================Método de Calificaciones========================================

    // Mostrar las calificaciones
    public function index(Request $request)
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
    public function create()
    {
        $metodos = WorkType::all();
        $course = student::select('course')->orderBy('course')->distinct()->get();
        $subject = Teacher::where('email', auth()->user()->email)->first()->subject;

        return view('teacher.method.add-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course]);
    }

    // Agregar una nueva calificación
    public function store(Request $request, TeacherServices $requestTeacher)
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
    public function edit(Request $request)
    {
        $metodos = WorkType::all();
        $course = student::select('course')->orderBy('course')->distinct()->get();
        $subject = Teacher::where('email', auth()->user()->email)->first()->subject;
        $search = Percentages::with('WorkType')->find($request->search);

        return view('teacher.method.edit-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course, 'search' => $search]);
    }

    // Actualizar una calificación existente
    public function update(Request $request, TeacherServices $requestTeacher)
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
    public function destroy(Request $request)
    {
        $searchPercentage = Percentages::find($request->search);
    
        Work::where('work_type_id', $searchPercentage->workType->id)->delete();

        $searchPercentage->delete();

        return redirect()->route('teacher.qualification');
    }
}