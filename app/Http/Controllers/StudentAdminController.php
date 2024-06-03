<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\StudentAdminServices;

class StudentAdminController extends Controller
{
    // Vizualizar la página principal de estudiantes
    public function index(Request $request)
    {
        // Obtener la lista de estudiantes ordenados por curso y aplicar filtros si se proporcionan
        $students = Student::orderBy('course', 'ASC')
            ->none($request->all())
            ->course($request->get('course'))
            ->name($request->get('name'))
            ->get();

        // Obtener la lista de cursos disponibles para mostrar en los filtros
        $course = Student::select('course')->distinct()->orderBy('course')->get();

        // Retornar la vista con la lista de estudiantes y los cursos disponibles
        return view('studentAdmin.index', ['students'=>$students, 'course' => $course]);
    }

    // Vizualizar la página de editar estudiante
    public function showEdit(Request $request)
    {
        // Obtener la información del estudiante específico a editar
        $student = Student::where("name", $request->name)->where("id", $request->id)->first();

        // Retornar la vista para editar el estudiante
        return view("studentAdmin.edit", ['user'=>$student]);
    }

    // Vizualizar la página de notas del estudiante
    public function showNote(Request $request)
    {
        // Obtener la información del estudiante específico y sus calificaciones
        $student = Student::where('id', $request->id)->first();
        $subjects = $student->qualification;

        // Retornar la vista para ver las notas del estudiante
        return view("studentAdmin.note", ['subjects'=>$subjects, 'student'=>$student]);
    }

    // Vizualizar la página de agregar estudiante
    public function showAdd()
    {
        // Retornar la vista para agregar un nuevo estudiante
        return view("studentAdmin.add");
    }

    // Creación de estudiante con método PUT
    public function create(Request $request, StudentAdminServices $requestStudent)
    {
        // Validar las entradas proporcionadas para crear un estudiante
        $validator = Validator::make($request->all(), [
            // Agregar reglas de validación para cada campo
        ]);

        // Verificar si la validación falla y redirigir con un mensaje de error si es así
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Comprobar si el correo electrónico ingresado pertenece a otro usuario
        $requestStudent->checkEmailNew($request);

        // Crear el nuevo estudiante con los datos proporcionados
        $requestStudent->createStudent($request);

        // Redirigir a la página principal de estudiantes después de crear exitosamente
        return redirect(route("student.admin"));
    }

    // Actualización de estudiante con método POST
    public function update(Request $request, StudentAdminServices $requestStudent)
    {
        // Validar las entradas proporcionadas para actualizar un estudiante
        $validator = Validator::make($request->all(), [
            // Agregar reglas de validación para cada campo
        ]);

        // Verificar si la validación falla y redirigir con un mensaje de error si es así
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Comprobar si el correo electrónico ingresado pertenece a otro usuario
        $requestStudent->checkEmailNew($request);

        // Actualizar los detalles del estudiante con los datos proporcionados
        $requestStudent->updateStudent($request);

        // Redirigir a la página principal de estudiantes después de actualizar exitosamente
        return redirect(route("student.admin"));
    }

    // Eliminación de estudiante con método DELETE
    public function destroy(Request $request, StudentAdminServices $requestStudent)
    {
        // Eliminar el estudiante con el ID proporcionado
        $requestStudent->deleteStudent($request);

        // Redirigir a la página principal de estudiantes después de eliminar exitosamente
        return redirect(route("student.admin"));
    }
}
