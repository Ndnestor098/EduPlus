<?php

namespace App\Http\Controllers;

use App\Models\student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AlumnosController extends Controller
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

            $students = student::orderBy($search[0], $search[1])->paginate(25);
        }else{
            $students = student::orderBy('course', 'ASC')->paginate(25);

        }

        $students->appends([
            'orden' => $request->orden
        ]);

        return view('student.students', ['students'=>$students]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function showEdit(Request $request)
    {
        if(!auth()->user()->admin){
            return redirect(route("home"));
        }

        $student = student::where("name", $request->name)->get()[0];

        return view("student.student-edit", ['user'=>$student]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
