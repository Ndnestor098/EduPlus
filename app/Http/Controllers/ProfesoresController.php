<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfesoresController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        if($request->orden){
            $search =  explode("/", $request->orden);

            $teachers = Teacher::orderBy($search[0], $search[1])->where("director", auth()->user()->id)->get();
        }else{
            $teachers = Teacher::orderBy("name", "asc")->where("director", auth()->user()->id)->get();
        }

        return view('teacher.profesores', ['teachers' => $teachers]);
    }

    public function showAdd()
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        return view('teacher.profesor-add');
    }


    public function showEdit(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        $user = Teacher::where("name", $request->name)->where("id", $request->id)->where("director", auth()->user()->id)->get()[0];

        return view('teacher.profesor-edit', ['user'=>$user]);
    }
    

    public function create(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'salary' => 'required',
            'started' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }
        

        if(!empty(Teacher::where('email', $request->email)->where('director', auth()->user()->id)->get()[0]->email) && Teacher::where('email', $request->email)->where('director', auth()->user()->id)->get()[0]->email == $request->email){
            return redirect()->back()->with('errors', 'El email existente.');
        }

        $teacher = new Teacher();

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->subjects = $request->subject;
        $teacher->salary = $request->salary;
        $teacher->started = $request->started;
        $teacher->password = Hash::make($request->password);
        $teacher->director = auth()->user()->id;

        $teacher->save();

        return redirect(route("profesores"));
    }


    public function update(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

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

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->subjects = $request->subject;
        $teacher->salary = $request->salary;
        $teacher->started = $request->started;

        $teacher->save();

        return redirect(route("profesores"));
    }


    public function destroy(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        Teacher::find($request->id)->delete();

        return redirect(route("profesores"));
    }
}
