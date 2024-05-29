<?php
namespace App\Services;

use App\Models\Qualification;
use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class StudentAdminServices
{
    // Función para verificar si el email ya está en uso
    public function checkEmailNew(Request $request)
    {
        try {
            // Verificar si el email ya está en uso en la tabla de usuarios
            if (User::where('email', $request->email)->first()->email == $request->email) {
                return redirect()->back()->with('errors', 'Email ya en uso.');
            }
        } catch (\Throwable $th) {
            // Manejar excepción si no se encuentra el email en la tabla de usuarios
        }

        try {
            // Verificar si el email ya está en uso en la tabla de estudiantes
            if (student::where('email', $request->email)->first()->email == $request->email) {
                return redirect()->back()->with('errors', 'Email ya en uso.');
            }
        } catch (\Throwable $th) {
            // Manejar excepción si no se encuentra el email en la tabla de estudiantes
        }
    }

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
        // Buscar el estudiante por su ID
        $student = student::find($request->id);

        // Si el email ha cambiado
        if($student->email != $request->email){

            // Actualizar los datos del usuario
            $user = User::where("email", $student->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            $user->role()->sync(3);

            // Actualizar los datos del estudiante
            $student->name = $request->name;
            $student->email = $request->email;
            $student->cellphone = $request->cellphone;
            $student->course = $request->course;
            $student->save();

        } else {
            // Si el email no ha cambiado, solo actualizar otros datos del estudiante
            $student->name = $request->name;
            $student->course = $request->course;
            $student->cellphone = $request->cellphone;
            $student->save();
        }
    }

    // Función para eliminar un estudiante
    public function deleteStudent(Request $request)
    {
        // Eliminar el usuario asociado al email del estudiante
        User::where("email", $request->email)->delete();

        // Eliminar el estudiante por su ID
        student::find($request->id_student)->delete();
    }
}
