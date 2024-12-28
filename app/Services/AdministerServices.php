<?php
namespace App\Services;

use App\Models\Administer;
use App\Models\RolesUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdministerServices
{
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
        $user = User::where("email", $admin->email)->first();

        // Si el email ha cambiado
        if($admin->email != $request->email){
            $user->email = $request->email;

            $admin->email = $request->email;
        }

        if(Hash::make($request->password) != $user->password){
            $user->password = Hash::make($request->password);
        }
        
        // update users
        $user->name = $request->name;
        $user->save();
        
        // update teacher
        $admin->name = $request->name;
        $admin->salary = $request->salary;
        $admin->cellphone = $request->cellphone;
        $admin->started = $request->started;
        $admin->save();
    }

}