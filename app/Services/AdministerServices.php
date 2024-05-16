<?php
namespace App\Services;

use App\Models\Administer;
use App\Models\Qualification;
use App\Models\RolesUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdministerServices
{
    public function checkEmailNew(Request $request)
    {
        try {
            User::where('email', $request->email)->first()->email == $request->email;
            return redirect()->back()->with('errors', 'Email ya en uso.');
        } catch (\Throwable $th) {
            //
        }

        try {
            Administer::where('email', $request->email)->first()->email == $request->email;
            return redirect()->back()->with('errors', 'Email ya en uso.');
        } catch (\Throwable $th) {
            //
        }
    }

    public function createAdminister(Request $request)
    {
        //Crear el Profesor con su tabla
        $admin = new Administer();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->salary = $request->salary;
        $admin->cellphone = $request->cellphone;
        $admin->started = $request->started;
        $admin->password = Hash::make($request->password);
        $admin->save();

        //Crear el usuario para que inicie sesion
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        //Crear el role de Profesor
        $role = new RolesUser();
        $role->user_id = $user->id;
        $role->role_id = 1;
        $role->save();
    }

    public function updateAdminister(Request $request)
    {
        $admin = Administer::find($request->id);

        //=========Validar si hay cambio en el email=========
        if($admin->email != $request->email){
            $user = User::where("email", $admin->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->cellphone = $request->cellphone;
            $admin->salary = $request->salary;
            $admin->started = $request->started;
            $admin->save();

        }else{
            //El email no se cambio, asi que solo se actualizan estos datos
            $admin->name = $request->name;
            $admin->salary = $request->salary;
            $admin->cellphone = $request->cellphone;
            $admin->started = $request->started;
            $admin->save();
        }
    }

    public function deleteAdminister(Request $request)
    {
        $user =  User::where("email", $request->email)->first();

        RolesUser::where("user_id", $user->id)->delete();
        $user->delete();
        Administer::find($request->id_admin)->delete();
    }
}