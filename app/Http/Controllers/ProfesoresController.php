<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfesoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

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
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        return view('teacher.profesor-add');
    }

    /**
     * Display the specified resource.
     */
    public function showEdit(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        $user = Teacher::where("name", $request->name)->get()[0];

        return view('teacher.profesor-edit', ['user'=>$user]);
    }
    
    /**
     * created new resource in storage.
     */
    public function create(Request $request)
    {
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
            'password' => 'required|string|min:8|confirmed',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        $teacher = new Teacher();

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->subjects = $request->subject;
        $teacher->salary = $request->salary;
        $teacher->started = $request->started;
        $teacher->password = Hash::make($request->password);

        $teacher->save();

        return redirect(route("profesores"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Teacher::find($request->id)->delete();
        return redirect(route("profesores"));
    }
}
