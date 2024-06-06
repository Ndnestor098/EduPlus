<?php

namespace App\Http\Controllers;

use App\Models\Administer;
use App\Models\Qualification;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\AdministerServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    // Vizualizar la página principal de administradores
    public function index(Request $request)
    {
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

        // Retornar la vista con la lista paginada de administradores
        return view('administrator.index', ['admin'=>$admin]);
    }

    // Vizualizar la página de agregar administrador
    public function showAdd()
    {
        // Retornar la vista para crear un nuevo administrador
        return view('administrator.create');
    }

    // Vizualizar la página de editar administrador
    public function showEdit(Request $request)
    {
        // Obtener la información del administrador específico a editar
        $admin = Administer::where("name", $request->name)->where("id", $request->id)->first();

        // Retornar la vista para editar el administrador
        return view('administrator.edit', ['user'=>$admin]);
    }

    // Crear un nuevo administrador
    public function create(Request $request, AdministerServices $requestAdmin)
    {
        // Validar las entradas proporcionadas para crear un administrador
        $validator = Validator::make($request->all(), [
            // Agregar reglas de validación para cada campo
        ]);

        // Verificar si la validación falla y redirigir con un mensaje de error si es así
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Verificar si el correo electrónico ingresado pertenece a otro usuario
        $requestAdmin->checkEmailNew($request);
        
        // Crear el nuevo administrador con los datos proporcionados
        $requestAdmin->createAdminister($request);

        // Redirigir a la página principal de administradores después de crear exitosamente
        return redirect(route("administrador"));
    }

    // Actualizar los detalles de un administrador existente
    public function update(Request $request, AdministerServices $requestAdmin)
    {
        // Validar las entradas proporcionadas para actualizar un administrador
        $validator = Validator::make($request->all(), [
            // Agregar reglas de validación para cada campo
        ]);

        // Verificar si la validación falla y redirigir con un mensaje de error si es así
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        // Verificar si el correo electrónico ingresado pertenece a otro usuario
        $requestAdmin->checkEmailNew($request);

        // Actualizar los detalles del administrador con los datos proporcionados
        $requestAdmin->updateAdminister($request);

        // Redirigir a la página principal de administradores después de actualizar exitosamente
        return redirect(route("administrador"));
    }

    // Eliminar un administrador existente
    public function destroy(Request $request, AdministerServices $requestAdmin)
    {
        // Eliminar el administrador con el ID proporcionado
        $requestAdmin->deleteAdminister($request);

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
