<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AlumnosController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        if($request->orden){
            $search =  explode("/", $request->orden);

            $students = student::orderBy($search[0], $search[1])->where("director", auth()->user()->id)->paginate(25);
        }else{
            $students = student::orderBy('course', 'ASC')->where("director", auth()->user()->id)->paginate(25);
        }

        $students->appends([
            'orden' => $request->orden
        ]);

        return view('student.students', ['students'=>$students]);
    }


    public function showEdit(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        $student = student::where("name", $request->name)->where("id", $request->id)->where("director", auth()->user()->id)->get()[0];

        return view("student.student-edit", ['user'=>$student]);
    }


    public function showNote(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }
        $student = student::where("director", auth()->user()->id)->where('id', $request->id)->get()[0];
        $subjects = subject::find($request->id);

        return view("student.note", ['subjects'=>$subjects, 'student'=>$student]);
    }


    public function showAdd()
    {
        return view("student.student-add");
    }


    public function create(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'course' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }
        
        if(!empty(student::where('email', $request->email)->where('director', auth()->user()->id)->get()[0]->email) && student::where('email', $request->email)->where('director', auth()->user()->id)->get()[0]->email == $request->email){
            return redirect()->back()->with('errors', 'El email existente.');
        }

        $student = new student();
        $note = new subject();

        $student->name = $request->name;
        $student->email = $request->email;
        $student->course = $request->course;
        $student->password = Hash::make($request->password);
        $student->director = auth()->user()->id;

        $note->matematicas = 0;
        $note->ingles = 0; 
        $note->fisica = 0; 
        $note->ciencia = 0; 
        $note->computacion = 0; 
        $note->arte = 0; 
        $note->literatura = 0; 
        $note->historia = 0; 

        $student->save();
        $note->save();

        return redirect(route("alumnos"));
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
            'email' => 'required|email|max:255',
            'course' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        $student = student::find($request->id);

        $student->name = $request->name;
        $student->email = $request->email;
        $student->course = $request->course;

        $student->save();

        return redirect(route("alumnos"));
    }


    public function destroy(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        student::find($request->id)->delete();

        subject::find($request->id)->delete();

        return redirect(route("alumnos"));
    }
}
