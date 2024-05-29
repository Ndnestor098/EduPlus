<?php
namespace App\Services;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class TeacherAdminServices
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
            // Verificar si el email ya está en uso en la tabla de profesores
            if (Teacher::where('email', $request->email)->first()->email == $request->email) {
                return redirect()->back()->with('errors', 'Email ya en uso.');
            }
        } catch (\Throwable $th) {
            // Manejar excepción si no se encuentra el email en la tabla de profesores
        }
    }

    // Función para crear un nuevo profesor
    public function createTeacher(Request $request)
    {
        // Crear y guardar el profesor en la base de datos
        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->cellphone = $request->cellphone;
        $teacher->subject = $request->subject;
        $teacher->salary = $request->salary;
        $teacher->started = $request->started;
        $teacher->password = Hash::make($request->password);
        $teacher->save();

        // Crear y guardar el usuario para que el profesor pueda iniciar sesión
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        // Asignar el rol de profesor al usuario
        $user->role()->sync(2);
    }

    // Función para actualizar los datos de un profesor
    public function updateTeacher(Request $request)
    {
        // Buscar el profesor por su ID
        $teacher = Teacher::find($request->id);

        // Si el email ha cambiado
        if($teacher->email != $request->email){
            // Actualizar los datos del usuario
            $user = User::where("email", $teacher->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            $user->role()->sync(2);

            // Actualizar los datos del profesor
            $teacher->name = $request->name;
            $teacher->email = $request->email;
            $teacher->subject = $request->subject;
            $teacher->cellphone = $request->cellphone;
            $teacher->salary = $request->salary;
            $teacher->started = $request->started;
            $teacher->save();

        } else {
            // Si el email no ha cambiado, solo actualizar otros datos del profesor
            $teacher->name = $request->name;
            $teacher->subject = $request->subject;
            $teacher->salary = $request->salary;
            $teacher->cellphone = $request->cellphone;
            $teacher->started = $request->started;
            $teacher->save();
        }
    }

    // Función para eliminar un profesor
    public function deleteTeacher(Request $request)
    {
        // Eliminar el usuario asociado al correo electrónico del profesor
        User::where("email", $request->email)->delete();

        // Eliminar al profesor de la base de datos usando su ID
        Teacher::find($request->id_teacher)->delete();
    }
}
