<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Services\TeacherAdminServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
    //Vizualizar la area de los profesores (Admin)
    public function index(Request $request)
    {
        // Verificar si se proporciona un parámetro de ordenamiento
        if($request->orden){
            $search =  explode("/", $request->orden);

            // Ordenar los profesores según el parámetro especificado
            $teachers = Teacher::orderBy($search[0], $search[1])->get();
        } else {
            // Si no se proporciona ningún parámetro, ordenar por nombre de manera ascendente por defecto
            $teachers = Teacher::orderBy("name", "asc")->get();
        }

        // Retornar la vista con la lista de profesores
        return view('teacherAdmin.index', ['teachers' => $teachers]);
    }

    //Vizualizar la area de agregar profesores (Admin)
    public function showAdd()
    {
        // Retornar la vista para agregar un nuevo profesor
        return view('teacherAdmin.add');
    }

    //Vizualizar la area de editar profesores (Admin)
    public function showEdit(Request $request)
    {
        // Obtener los detalles del usuario/profesor para editar
        $user = Teacher::where("name", $request->name)->where("id", $request->id)->first();

        // Retornar la vista para editar el profesor con los detalles obtenidos
        return view('teacherAdmin.edit', ['user'=>$user]);
    }

    //Crear profesores (Admin)
    public function create(Request $request, TeacherAdminServices $requestTeacher)
    {
        // Validar las entradas del formulario de creación de profesor
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'subject' => 'required|string|max:255',
            'cellphone' => 'required',
            'salary' => 'required',
            'started' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verificar si las validaciones fallan
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }
        
        // Verificar si el email ya existe en la base de datos
        $requestTeacher->checkEmailNew($request);

        // Crear un nuevo profesor con los datos proporcionados
        $requestTeacher->createTeacher($request);

        // Redirigir a la página de administración de profesores
        return redirect(route("teacher.admin"));
    }

    //Actualizar profesores (Admin)
    public function update(Request $request, TeacherAdminServices $requestTeacher)
    {
        // Validar las entradas del formulario de edición de profesor
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cellphone' => 'required', 
            'subject' => 'required|string|max:255',
            'salary' => 'required',
            'started' => 'required',
        ]);

        // Verificar si las validaciones fallan
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Verificar si el email ya existe en la base de datos
        $requestTeacher->checkEmailNew($request);

        // Actualizar los datos del profesor
        $requestTeacher->updateTeacher($request);

        // Redirigir a la página de administración de profesores
        return redirect(route("teacher.admin"));
    }

    //Eliminar profesores (Admin)
    public function destroy(Request $request, TeacherAdminServices $requestTeacher)
    {
        // Eliminar el profesor de la base de datos
        $requestTeacher->deleteTeacher($request);

        // Redirigir a la página de administración de profesores
        return redirect(route("teacher.admin"));
    }
}

