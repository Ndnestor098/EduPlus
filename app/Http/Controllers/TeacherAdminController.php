<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Services\TeacherAdminServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TeacherAdminController extends Controller
{
    // Vizualizar la Pagina Principal
    public function index(Request $request)
    {
        if($request->orden){
            $search =  explode("/", $request->orden);

            $teachers = Teacher::orderBy($search[0], $search[1])->get();
        }else{
            
            $teachers = Teacher::orderBy("name", "asc")->get();
        }

        return view('teacherAdmin.index', ['teachers' => $teachers]);
    }

    // Vizualizar la Pagina Agregar Profesor
    public function showAdd()
    {
        return view('teacherAdmin.add');
    }

    // Vizualizar la Pagina de Editar Profesor
    public function showEdit(Request $request)
    {
        $user = Teacher::where("name", $request->name)->where("id", $request->id)->get()[0];

        return view('teacherAdmin.edit', ['user'=>$user]);
    }
    
    // Cracion de Profesor con metodo PUT
    public function create(Request $request, TeacherAdminServices $requestTeacher)
    {
        //=========Validar las Entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'cellphone' => 'required',
            'salary' => 'required',
            'started' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }
        
        //=========Visualizar si el email exitste=========
        $requestTeacher->checkEmailNew($request);

        //=========Guardar datos de los nuevos cambios=========
        $requestTeacher->createTeacher($request);

        return redirect(route("teacher.admin"));
    }

    // Actualizacion de Profesor con metodo POST
    public function update(Request $request, TeacherAdminServices $requestTeacher)
    {
        //=========Validar las entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cellphone' => 'required', 
            'subject' => 'required|string|max:255',
            'salary' => 'required',
            'started' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //=========Validar email en tabla Teachers y User=========
        $requestTeacher->checkEmailNew($request);

        //=========Actualizar los datos del usuario=========
        $requestTeacher->updateTeacher($request);

        return redirect(route("teacher.admin"));
    }

    // Eliminacion de Profesor con metodo DELETE
    public function destroy(Request $request, TeacherAdminServices $requestTeacher)
    {
        //=========Buscar el id del student en la tabla user=========
        $requestTeacher->deleteTeacher($request);

        return redirect(route("teacher.admin"));
    }
}
