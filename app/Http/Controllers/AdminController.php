<?php

namespace App\Http\Controllers;

use App\Models\Administer;
use App\Models\Qualification;
use App\Models\RolesUser;
use App\Models\Student;
use App\Models\User;
use App\Services\AdministerServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Vizualizar la página principal de administradores
    public function index(Request $request)
    {
        if(Cache::has('admin')){
            $admin = Cache::get('admin');
        } else {
            // Si se proporciona un parámetro de orden, ordenar por ese campo y paginar
            if($request->orden){
                $search =  explode("/", $request->orden);
                $admin = Administer::orderBy($search[0], $search[1])->paginate(25);
            }else{
                // De lo contrario, ordenar por nombre de forma ascendente y paginar
                $admin = Administer::orderBy('name', 'ASC')->paginate(25);
            }

            // Asegurarse de que los parámetros de orden se conserven en los enlaces de paginación
            $admin->appends([
                'orden' => $request->orden
            ]);

            Cache::put('admin', $admin, 600);
        }

        // Retornar la vista con la lista paginada de administradores
        return view('administrator.index', ['admin'=>$admin]);
    }

    // Vizualizar la página de agregar administrador
    public function create()
    {
        // Retornar la vista para crear un nuevo administrador
        return view('administrator.create');
    }

    // Crear un nuevo administrador
    public function store(Request $request, AdministerServices $requestAdmin)
    {
        // Validar las entradas proporcionadas para crear un administrador
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:administer,email',
            'salary' => 'required|numeric',
            'cellphone' => 'required|string|min:8',
            'started' => 'required|date',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        // Crear el nuevo administrador con los datos proporcionados
        $requestAdmin->createAdminister($request);

        Cache::forget('admin');

        // Redirigir a la página principal de administradores después de crear exitosamente
        return redirect(route("administrador"));
    }
    
    // Vizualizar la página de editar administrador
    public function edit(Request $request)
    {
        // Obtener la información del administrador específico a editar
        $admin = Administer::where("name", $request->name)->where("id", $request->id)->first();

        // Retornar la vista para editar el administrador
        return view('administrator.edit', ['user'=>$admin]);
    }

    // Actualizar los detalles de un administrador existente
    public function update(Request $request, AdministerServices $requestAdmin)
    {
        $administer = Administer::find($request->id);
        $user = User::where("email", $administer->email)->first();
        
        // Validar las entradas proporcionadas para actualizar un administrador
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('administer', 'email')->ignore($administer->id),
            ],
            'salary' => 'required|numeric',
            'cellphone' => 'required|string|min:8',
            'started' => 'required|date',
            'password' => 'required|string|min:8',
        ]);

        // Actualizar los detalles del administrador con los datos proporcionados
        $requestAdmin->updateAdminister($request);

        Cache::forget('admin');

        // Redirigir a la página principal de administradores después de actualizar exitosamente
        return redirect(route("administrador"));
    }

    // Eliminar un administrador existente
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:administer,id',
            'email' => 'required|email|exists:users,email'
        ]);

        // Buscar el usuario asociado al correo electrónico del administrador
        $user = User::where('email',$request->email)->first();

        // Eliminar el rol del usuario
        RolesUser::where("user_id", $user->id)->delete();

        // Eliminar el usuario
        $user->delete();

        // Eliminar al administrador de la base de datos usando su ID
        Administer::find($request->id)->delete();

        Cache::forget('admin');

        // Redirigir a la página principal de administradores después de eliminar exitosamente
        return redirect(route("administrador"));
    }

    public function showMarks(Request $request)
    {
        // Obtener todos los cursos distintos ordenados
        $course = Student::select('course')->distinct()->orderBy('course')->get();

        // Obtener todos los estudiantes en orden
        $students = Qualification::with('student')
            ->whereHas('student', function($query) use ($request){
                if($request->name){
                    $query->where('name', 'LIKE', "%$request->name%");
                }
                if($request->course){
                    $query->where('course', $request->course);
                }
            })
            ->orderBy("student_id")
            ->paginate(10);

        // Mantener los valores de las variables en la URL
        $students->appends($request->query());
        
        return view("administrator.marks", ['course' => $course, 'students'=>$students]);
    }
}
