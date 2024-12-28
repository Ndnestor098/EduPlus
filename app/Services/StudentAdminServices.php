<?php
namespace App\Services;

use App\Models\Qualification;
use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class StudentAdminServices
{
    // Función para crear un nuevo estudiante
    public function createStudent(Request $request)
    {
        // Crear y guardar el estudiante en la base de datos
        $student = new student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->cellphone = $request->cellphone;
        $student->course = $request->course;
        $student->password = Hash::make($request->password);
        $student->save();

        // Crear y guardar el usuario para que el estudiante pueda iniciar sesión
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        // Asignar el rol de estudiante al usuario
        $user->role()->sync(3);

        // Crear la tabla de calificaciones para el estudiante
        $qualification = new Qualification();
        $qualification->student_id = $student->id;
        $qualification->save();
    }

    // Función para actualizar los datos de un estudiante
    public function updateStudent(Request $request)
    {
        // Buscar el estudiante por su ID y email
        $student = student::find($request->id);
        $user = User::where("email", $student->email)->first();

        if($student->email != $request->email){
            $user->email = $request->email;
            $student->email = $request->email;
        } 

        if(!empty($request->password && !Hash::check($request->password, $user->password))){
            $user->password = Hash::make($request->password);
        }

        // update users table
        $user->name = $request->name;
        $user->save();

        // update students table
        $student->name = $request->name;
        $student->course = $request->course;
        $student->cellphone = $request->cellphone;
        $student->save();
        
    }
}
