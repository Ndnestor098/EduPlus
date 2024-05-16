<?php
namespace App\Services;

use App\Models\Qualification;
use App\Models\RolesUser;
use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class StudentServices
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
            student::where('email', $request->email)->first()->email == $request->email;
            return redirect()->back()->with('errors', 'Email ya en uso.');
        } catch (\Throwable $th) {
            //
        }
    }

    public function createStudent(Request $request)
    {
        //Crear el Profesor con su tabla
        $student = new student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->cellphone = $request->cellphone;
        $student->course = $request->course;
        $student->password = Hash::make($request->password);
        $student->save();

        //Crear el usuario para que inicie sesion
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        //Crear el role de Profesor
        $role = new RolesUser();
        $role->user_id = $user->id;
        $role->role_id = 3;
        $role->save();

        //Crear tabla de Calificaciones del usuario
        $qualification = new Qualification();
        $qualification->student_id = $student->id;
        $qualification->save();
    }

    public function updateStudent(Request $request)
    {
        $student = student::find($request->id);

        //=========Vizualizar cambios en el email=========
        if($student->email != $request->email){

            //=========Guardar datos de los nuevos cambios=========
            //Actualizar usuario
            $user = User::where("email", $student->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            //Actualizar estudiante
            $student->name = $request->name;
            $student->email = $request->email;
            $student->cellphone = $request->cellphone;
            $student->course = $request->course;
            $student->save();

        }else{
            //=========Guardar datos de los nuevos cambios=========
            $student->name = $request->name;
            $student->course = $request->course;
            $student->cellphone = $request->cellphone;
            $student->save();
        }
    }

    public function deleteStudent(Request $request)
    {
        $user =  User::where("email", $request->email)->first();

        RolesUser::where("user_id", $user->id)->delete();

        $user->delete();

        Student::find($request->id_student)->delete();
    }
}