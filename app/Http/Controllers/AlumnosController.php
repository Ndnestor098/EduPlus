<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use App\Models\student;
use App\Models\subject;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\RolesUser;

class AlumnosController extends Controller
{
    public function role(){
        return auth()->user()->RolesUser->first()->role_id == 1;
    }

    public function index(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        if($request->orden){
            $search =  explode("/", $request->orden);

            $students = student::orderBy($search[0], $search[1])->paginate(25);
        }else{
            $students = student::orderBy('course', 'ASC')->paginate(25);
        }

        $students->appends([
            'orden' => $request->orden
        ]);

        return view('student.students', ['students'=>$students]);
    }


    public function showEdit(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        $student = student::where("name", $request->name)->where("id", $request->id)->first();

        return view("student.student-edit", ['user'=>$student]);
    }


    public function showNote(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));
        $student = student::where('id', $request->id)->first();
        $subjects = $student->qualification;

        return view("student.note", ['subjects'=>$subjects, 'student'=>$student]);
    }


    public function showAdd()
    {
        return view("student.student-add");
    }


    public function create(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'course' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        try {
            $bool = User::where('email', $request->email)->first()->email == $request->email;
        } catch (\Throwable $th) {
            $bool = false;
        }

        if($bool){
            return redirect()->back()->with('errors', 'Email ya en uso.');
        }

        //Crear el Profesor con su tabla
        $student = new student();
        $student->name = $request->name;
        $student->email = $request->email;
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


        return redirect(route("alumnos"));
    }

    
    public function update(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.',
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
        ]; 

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'course' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        try {
            $bool = User::where('email', $request->email)->first()->email == $request->email;
        } catch (\Throwable $th) {
            $bool = false;
        }

        if($bool){
            return redirect()->back()->with('errors', 'Email ya en uso.');
        }

        $student = student::find($request->id);

        if($student->email != $request->email){
            //=========Validar email en tabla Teachers=========
            try {
                $bool = student::where('email', $request->email)->first()->email == $request->email;
                $bool = true;
            } catch (\Throwable $th) {
                $bool = false;
            }

            //=========Validar si el nuevo email existe en la tabla Users=========
            try {
                $bool = User::where('email', $request->email)->first()->email;
                $bool = true;
            } catch (\Throwable $th) {
                $bool = false;
            }

            //=========Confirmar si hay email iguales=========
            if($bool){
                return redirect()->back()->with('errors', 'Email ya en uso.');
            }

            //=========Guardar datos de los nuevos cambios=========
            //Actualizar usuario
            $user = User::where("email", $student->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            //Actualizar estudiante
            $student->name = $request->name;
            $student->email = $request->email;
            $student->course = $request->course;
            $student->save();

        }else{

            $student->name = $request->name;
            $student->course = $request->course;
            $student->save();
        }

        return redirect(route("alumnos"));
    }


    public function destroy(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        //Buscar el id del profesor en la tabla user
        $user =  User::where("email", $request->email)->first();

        RolesUser::where("user_id", $user->id)->delete();

        $user->delete();

        Student::find($request->id_student)->delete();

        return redirect(route("alumnos"));
    }
}
