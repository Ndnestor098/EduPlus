<?php
namespace App\Services;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class TeacherAdminServices
{
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
        $user = User::where("email", $teacher->email)->first();
        
        // Si el email ha cambiado
        if($teacher->email != $request->email){
            $user->email = $request->email;

            $teacher->email = $request->email;
        }

        if(!empty($request->input("password")) && !Hash::check($request->password, $user->password)){
            $user->password = Hash::make($request->password);
        }

        // update users table
        $user->name = $request->name;
        $user->save();

        // update teacher table
        $teacher->name = $request->name;
        $teacher->subject = $request->subject;
        $teacher->salary = $request->salary;
        $teacher->cellphone = $request->cellphone;
        $teacher->started = $request->started;
        $teacher->save();
    }
}
