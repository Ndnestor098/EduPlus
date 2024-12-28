<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Services\NoteServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\StudentAdminServices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // Vizualizar la página principal de estudiantes
    public function index(Request $request)
    {
        if(Cache::has('student') && Cache::has('course')){
            $students = Cache::get('student');
            $course = Cache::get('course');
        } else {
            // Obtener la lista de estudiantes ordenados por curso y aplicar filtros si se proporcionan
            $students = Student::orderBy('course', 'ASC')
            ->none($request->all())
            ->course($request->get('course'))
            ->name($request->get('name'))
            ->get();

            // Obtener la lista de cursos disponibles para mostrar en los filtros
            $course = Student::select('course')->distinct()->orderBy('course')->get();

            Cache::put('student', $students, now()->addMinutes(10));
            Cache::put('course', $course, now()->addMinutes(10));
        }
        

        // Retornar la vista con la lista de estudiantes y los cursos disponibles
        return view('studentAdmin.index', ['students'=>$students, 'course' => $course]);
    }

    // Vizualizar la página de notas del estudiante
    public function show(Request $request, NoteServices $requestNote)
    {
        // Obtener la información del estudiante específico y sus calificaciones
        $student = Student::where('id', $request->id)->first();

        $requestNote->updateQualification($student);

        $subjects = $student->qualification;

        // Retornar la vista para ver las notas del estudiante
        return view("studentAdmin.note", ['subjects'=>$subjects, 'student'=>$student]);
    }

    // Vizualizar la página de agregar estudiante
    public function create()
    {
        // Retornar la vista para agregar un nuevo estudiante
        return view("studentAdmin.create");
    }

    // Creación de estudiante con método PUT
    public function store(Request $request, StudentAdminServices $requestStudent)
    {
        // Validar las entradas proporcionadas para crear un estudiante
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email|unique:students,email',
            'course' => 'required|numeric|max:255',
            'cellphone' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|max:255|same:password'
        ]);

        // Crear el nuevo estudiante con los datos proporcionados
        $requestStudent->createStudent($request);

        Cache::forget('student');
        Cache::forget('course');

        // Redirigir a la página principal de estudiantes después de crear exitosamente
        return redirect(route("student.admin"));
    }

    // Vizualizar la página de editar estudiante
    public function edit(Request $request)
    {
        // Obtener la información del estudiante específico a editar
        $student = Student::where("name", $request->name)->where("id", $request->id)->first();

        // Retornar la vista para editar el estudiante
        return view("studentAdmin.edit", ['user'=>$student]);
    }

    // Actualización de estudiante con método POST
    public function update(Request $request, StudentAdminServices $requestStudent)
    {
        $student = Student::find($request->id);
        $user = User::where("email", $student->email)->first();

        $request->validate([
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('students', 'email')->ignore($student->id)
            ],	
            'course' => 'required|numeric|max:255',
            'cellphone' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'nullable|same:password'
        ]);

        // Actualizar los detalles del estudiante con los datos proporcionados
        $requestStudent->updateStudent($request);

        Cache::forget('student');
        Cache::forget('course');

        // Redirigir a la página principal de estudiantes después de actualizar exitosamente
        return redirect(route("student.admin"));
    }

    // Eliminación de estudiante con método DELETE
    public function destroy(Request $request)
    {
        $request->validate([
            'id_student' => 'required|integer|exists:students,id',
            'email' => 'required|email|exists:users,email'
        ]);

        // Eliminar el usuario asociado al email del estudiante
        User::where("email", $request->email)->first()->delete();

        // Eliminar el estudiante por su ID
        student::find($request->id_student)->delete();

        Cache::forget('student');
        Cache::forget('course');

        // Redirigir a la página principal de estudiantes después de eliminar exitosamente
        return redirect(route("student.admin"));
    }
}
