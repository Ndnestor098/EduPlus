<?php

namespace App\Http\Controllers;

use App\Models\student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\StudentServices;

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


    public function create(Request $request, StudentServices $requestStudent)
    {
        if(!$this->role()) return redirect(route("home"));
        
        //=========Validar las Entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'cellphone' => 'required', 
            'course' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //=========Comprobar usuario si existe=========
        $requestStudent->checkEmailNew($request);

        //=========Guardar datos de los nuevos cambios=========
        $requestStudent->createStudent($request);


        return redirect(route("alumnos"));
    }

    
    public function update(Request $request, StudentServices $requestStudent)
    {
        if(!$this->role()) return redirect(route("home"));

        //=========Validar las entradas=========
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cellphone' => 'required', 
            'course' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        //=========Comprobar usuario si existe=========
        $requestStudent->checkEmailNew($request);

        //=========Buscar id del estudiante=========
        $requestStudent->updateStudent($request);

        return redirect(route("alumnos"));
    }


    public function destroy(Request $request, StudentServices $requestStudent)
    {
        if(!$this->role()) return redirect(route("home"));

        //=========Buscar el id del teacher en la tabla user=========
        $requestStudent->deleteStudent($request);

        return redirect(route("alumnos"));
    }
}
