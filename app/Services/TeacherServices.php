<?php
namespace App\Services;

use App\Models\RolesUser;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class TeacherServices
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
            Teacher::where('email', $request->email)->first()->email == $request->email;
            return redirect()->back()->with('errors', 'Email ya en uso.');
        } catch (\Throwable $th) {
            //
        }
    }


    public function createTeacher(Request $request)
    {
        //Crear el Profesor con su tabla
        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->cellphone = $request->cellphone;
        $teacher->subject = $request->subject;
        $teacher->salary = $request->salary;
        $teacher->started = $request->started;
        $teacher->password = Hash::make($request->password);
        $teacher->save();

        //Crear el usuario para que inicie sesion
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        //Crear el role de Profesor
        $role = new RolesUser();
        $role->user_id = $user->id;
        $role->role_id = 2;
        $role->save();
    }


    public function updateTeacher(Request $request)
    {
        $teacher = Teacher::find($request->id);

        //=========Validar si hay cambio en el email=========
        if($teacher->email != $request->email){
            $user = User::where("email", $teacher->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $teacher->name = $request->name;
            $teacher->email = $request->email;
            $teacher->subject = $request->subject;
            $teacher->cellphone = $request->cellphone;
            $teacher->salary = $request->salary;
            $teacher->started = $request->started;
            $teacher->save();

        }else{
            //El email no se cambio, asi que solo se actualizan estos datos
            $teacher->name = $request->name;
            $teacher->subject = $request->subject;
            $teacher->salary = $request->salary;
            $teacher->cellphone = $request->cellphone;
            $teacher->started = $request->started;
            $teacher->save();
        }
    }

    
    public function deleteTeacher(Request $request)
    {
        //Buscar el id del profesor en la tabla user
        $user =  User::where("email", $request->email)->first();

        RolesUser::where("user_id", $user->id)->delete();
        $user->delete();
        Teacher::find($request->id_teacher)->delete();
    }
}