<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\StudentAdminServices;

class StudentAdminController extends Controller
{
    // Vizualizar la Pagina Principal
    public function index(Request $request)
    {
        $students = Student::orderBy('course', 'ASC')
            ->none($request->all())
            ->course($request->get('course'))
            ->name($request->get('name'))
            ->get();

        $course = Student::select('course')->distinct()->orderBy('course')->get();


        return view('studentAdmin.index', ['students'=>$students, 'course' => $course]);
    }

    // Vizualizar la Pagina de Editar Estudiante
    public function showEdit(Request $request)
    {
        $student = student::where("name", $request->name)->where("id", $request->id)->first();

        return view("studentAdmin.edit", ['user'=>$student]);
    }

    // Vizualizar la Pagina de notas del Estudiante
    public function showNote(Request $request)
    {
        $student = student::where('id', $request->id)->first();
        $subjects = $student->qualification;

        return view("studentAdmin.note", ['subjects'=>$subjects, 'student'=>$student]);
    }

    // Vizualizar la Pagina Agregar Estudiante
    public function showAdd()
    {
        return view("studentAdmin.add");
    }

    // Cracion de Estudiante con metodo PUT
    public function create(Request $request, StudentAdminServices $requestStudent)
    {
        //=========Validar las Entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cellphone' => 'required', 
            'course' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //=========Comprobar usuario si existe=========
        $requestStudent->checkEmailNew($request);

        //=========Guardar datos de los nuevos cambios=========
        $requestStudent->createStudent($request);


        return redirect(route("student.admin"));
    }

    // Actualizacion de Estudiante con metodo POST
    public function update(Request $request, StudentAdminServices $requestStudent)
    {
        //=========Validar las entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cellphone' => 'required', 
            'course' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //=========Comprobar usuario si existe=========
        $requestStudent->checkEmailNew($request);

        //=========Buscar id del estudiante=========
        $requestStudent->updateStudent($request);

        return redirect(route("student.admin"));
    }

    // Eliminacion de Estudiante con metodo DELETE
    public function destroy(Request $request, StudentAdminServices $requestStudent)
    {
        //=========Buscar el id del teacher en la tabla user=========
        $requestStudent->deleteStudent($request);

        return redirect(route("student.admin"));
    }
}
