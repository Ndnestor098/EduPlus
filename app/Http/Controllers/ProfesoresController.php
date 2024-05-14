<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolesUser;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfesoresController extends Controller
{
    public function role(){
        return auth()->user()->RolesUser->first()->role_id == 1;
    }

    public function index(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));


        if($request->orden){
            $search =  explode("/", $request->orden);

            $teachers = Teacher::orderBy($search[0], $search[1])->get();
        }else{
            $teachers = Teacher::orderBy("name", "asc")->get();
        }

        return view('teacher.profesores', ['teachers' => $teachers]);
    }

    public function showAdd()
    {
        if(!$this->role()) return redirect(route("home"));


        return view('teacher.profesor-add');
    }


    public function showEdit(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));


        $user = Teacher::where("name", $request->name)->where("id", $request->id)->get()[0];

        return view('teacher.profesor-edit', ['user'=>$user]);
    }
    

    public function create(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'salary' => 'required',
            'started' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //Ver si el email ingresado pertenece a otro usuario
        try {
            $bool = User::where('email', $request->email)->first()->email == $request->email;
        } catch (\Throwable $th) {
            $bool = false;
        }

        if($bool){
            return redirect()->back()->with('errors', 'Email ya en uso.');
        }
        
        //Crear el Profesor con su tabla
        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->email = $request->email;
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

        return redirect(route("profesores"));
    }


    public function update(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        //=========Validar las entradas=========
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.',
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
        ]; 

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'salary' => 'required',
            'started' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        $teacher = Teacher::find($request->id);

        //=========Validar si hay cambio en el email=========
        if($teacher->email != $request->email){
            //=========Validar email en tabla Teachers=========
            try {
                $bool = Teacher::where('email', $request->email)->first()->email == $request->email;
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

            if($bool){
                return redirect()->back()->with('errors', 'Email ya en uso.');
            }

            $user = User::where("email", $teacher->email)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $teacher->name = $request->name;
            $teacher->email = $request->email;
            $teacher->subject = $request->subject;
            $teacher->salary = $request->salary;
            $teacher->started = $request->started;
            $teacher->save();

        }else{
            //El email no se cambio, asi que solo se actualizan estos datos
            $teacher->name = $request->name;
            $teacher->subject = $request->subject;
            $teacher->salary = $request->salary;
            $teacher->started = $request->started;
            $teacher->save();
        }

        return redirect(route("profesores"));
    }


    public function destroy(Request $request)
    {
        if(!$this->role()) return redirect(route("home"));

        //Buscar el id del profesor en la tabla user
        $user =  User::where("email", $request->email)->first();

        RolesUser::where("user_id", $user->id)->delete();
        $user->delete();
        Teacher::find($request->id_teacher)->delete();

        return redirect(route("profesores"));
    }
}
