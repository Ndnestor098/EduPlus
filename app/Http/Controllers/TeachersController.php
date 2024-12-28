<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Services\TeacherAdminServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class TeachersController extends Controller
{
    //Vizualizar la area de los profesores (Admin)
    public function index(Request $request)
    {
        if(Cache::has('teacher')){
            $teachers = Cache::get('teacher');
        } else {
            // Verificar si se proporciona un parámetro de ordenamiento
            if($request->orden){
                $search =  explode("/", $request->orden);

                // Ordenar los profesores según el parámetro especificado
                $teachers = Teacher::orderBy($search[0], $search[1])->get();
            } else {
                // Si no se proporciona ningún parámetro, ordenar por nombre de manera ascendente por defecto
                $teachers = Teacher::orderBy("name", "asc")->get();
            }

            Cache::put('teacher', $teachers, now()->addMinutes(10));
        }
        

        // Retornar la vista con la lista de profesores
        return view('teacherAdmin.index', ['teachers' => $teachers]);
    }

    //Vizualizar la area de agregar profesores (Admin)
    public function create()
    {
        // Retornar la vista para agregar un nuevo profesor
        return view('teacherAdmin.create');
    }

    //Crear profesores (Admin)
    public function store(Request $request, TeacherAdminServices $requestTeacher)
    {
        // Validar las entradas del formulario de creacion de profesor
        $request->validate( [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email|unique:teachers,email',
            'cellphone' => 'required', 
            'subject' => 'required|string|max:255',
            'salary' => 'required',
            'started' => 'required',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
        ]);
        
        // Crear un nuevo profesor con los datos proporcionados
        $requestTeacher->createTeacher($request);

        Cache::forget('teacher');

        // Redirigir a la página de administración de profesores
        return redirect(route("teacher.admin"));
    }

    //Vizualizar la area de editar profesores (Admin)
    public function edit(Request $request)
    {
        // Obtener los detalles del usuario/profesor para editar
        $user = Teacher::where("name", $request->name)->where("id", $request->id)->first();

        // Retornar la vista para editar el profesor con los detalles obtenidos
        return view('teacherAdmin.edit', ['user'=>$user]);
    }
    
    //Actualizar profesores (Admin)
    public function update(Request $request, TeacherAdminServices $requestTeacher)
    {
        $teachers = Teacher::find($request->id);
        $user = User::where("email", $teachers->email)->first();

        // Validar las entradas del formulario de creacion de profesor
        $request->validate( [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('teachers', 'email')->ignore($teachers->id),
            ],
            'cellphone' => 'required', 
            'subject' => 'required|string|max:255',
            'salary' => 'required',
            'started' => 'required',
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'nullable|same:password',
        ]);

        // Actualizar los datos del profesor
        $requestTeacher->updateTeacher($request);

        Cache::forget('teacher');

        // Redirigir a la página de administración de profesores
        return redirect(route("teacher.admin"));
    }

    //Eliminar profesores (Admin)
    public function destroy(Request $request)
    {
        $request->validate( [
            'id' => 'required',
            'email' => 'required|string|email|exists:users,email',
        ]);

        // Eliminar el usuario asociado al correo electrónico del profesor
        User::where("email", $request->email)->first()->delete();

        // Eliminar al profesor de la base de datos usando su ID
        Teacher::find($request->id)->delete();

        Cache::forget('teacher');

        // Redirigir a la página de administración de profesores
        return redirect(route("teacher.admin"));
    }
}

