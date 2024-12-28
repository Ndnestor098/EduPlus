<?php
namespace App\Services;

use App\Models\Administer;
use App\Models\RolesUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdministerServices
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
            // Verificar si el email ya está en uso en la tabla de administradores
            if (Administer::where('email', $request->email)->first()->email == $request->email) {
                return redirect()->back()->with('errors', 'Email ya en uso.');
            }
        } catch (\Throwable $th) {
            // Manejar excepción si no se encuentra el email en la tabla de administradores
        }
    }

    // Función para crear un nuevo administrador
    public function createAdminister(Request $request)
    {
        // Crear y guardar el administrador en la base de datos
        $admin = new Administer();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->salary = $request->salary;
        $admin->cellphone = $request->cellphone;
        $admin->started = $request->started;
        $admin->password = Hash::make($request->password);
        $admin->save();

        // Crear y guardar el usuario para que el administrador pueda iniciar sesión
        $user = new User();
        $user->name = $admin->name;
        $user->email = $admin->email;
        $user->password = Hash::make($admin->password);
        $user->save();

        // Asignar el rol de administrador al usuario
        $role = new RolesUser();
        $role->user_id = $user->id;
        $role->role_id = 1; // 1 representa el rol de administrador
        $role->save();
    }

    // Función para actualizar los datos de un administrador
    public function updateAdminister(Request $request)
    {
        // Buscar el administrador por su ID
        $admin = Administer::find($request->id);

        // Si el email ha cambiado
        if($admin->email != $request->email){
            // Actualizar los datos del usuario
            $user = User::where("email", $admin->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            // Actualizar los datos del administrador
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->cellphone = $request->cellphone;
            $admin->salary = $request->salary;
            $admin->started = $request->started;
            $admin->save();

        } else {
            // Si el email no ha cambiado, solo actualizar otros datos del administrador
            $admin->name = $request->name;
            $admin->salary = $request->salary;
            $admin->cellphone = $request->cellphone;
            $admin->started = $request->started;
            $admin->save();
        }
    }

    // Función para eliminar un administrador
    public function deleteAdminister(Request $request)
    {
        // Buscar el usuario asociado al correo electrónico del administrador
        $user = User::where("email", $request->email)->first();

        // Eliminar el rol del usuario
        RolesUser::where("user_id", $user->id)->delete();

        // Eliminar el usuario
        $user->delete();

        // Eliminar al administrador de la base de datos usando su ID
        Administer::find($request->id_admin)->delete();
    }
}