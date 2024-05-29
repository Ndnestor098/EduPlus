<?php

namespace App\Http\Controllers;

use App\Models\Percentages;
use App\Models\Qualification;
use App\Models\student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkType;
use App\Services\TeacherServices;
use App\Services\WorkServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeachersController extends Controller
{
    


    //========================================Tareas========================================
    public function showWorks()
    {
        $course = student::select('course')->distinct()->orderBy('course')->get();

        $teacher = Teacher::where('email', auth()->user()->email)->first();

        $work = Work::where('teacher_id', $teacher->id)->get();

        return view('teacher.work.works', ['course' => $course, 'work' => $work]);
    }
    
    public function showAddWork()
    {
        $info = Teacher::where('email', auth()->user()->email)->first()->subject;
        $course = student::select('course')->orderBy('course')->distinct()->get();

        return view('teacher.work.add-work', ['info' => $info, 'course' => $course]);
    }

    public function orderQualification(Request $request)
    {
        return Percentages::with('workType')->where('subject', $request->subject)->where('course', $request->course)->get();
    }

    public function addWork(Request $request, WorkServices $requestWork)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'course' => 'required',
            'qualification' => 'required',
            'deliver' => 'required'
        ]);

        //Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        $file = null;
        $image = null;
        
        if($request->hasFile('file'))
        {
            $file = $requestWork->addFileWork($request);

            if(!$file) return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        if($request->hasFile('image'))
        {
            $image = $requestWork->addImageWork($request);

            if(!$image) return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }


        $requestWork->addWork($request, $file, $image);

        return redirect()->route('teacher.works');
    }

    public function showEditWork(Request $request)
    {        
        $work = Work::find($request->id);
        $course = student::select('course')->orderBy('course')->distinct()->get();

        return view('teacher.work.edit-work', ['work' => $work, 'course' => $course]);
    }

    //========================================Metodo de Calificacions========================================
    public function showQualification(Request $request)
    {
        $teacher = Teacher::where('email', auth()->user()->email)->first();
        $course = student::select('course')->distinct()->orderBy('course')->get();

        if($request->course){
            $percentages = Percentages::with('workType')->where('teacher_id', $teacher->id)->where('course', $request->course)->get();

        }else{
            $percentages = Percentages::with('workType')->where('teacher_id', $teacher->id)->where('course', 1)->get();

        }

        $valor = 0;
        foreach ($percentages as $key) {
            $valor += intval($key->percentage);
        }

        return view('teacher.qualification.qualification', ['all' => $percentages, 'course' => $course, 'valor'=>$valor]);
    }

    public function ShowAddQualification()
    {
        $metodos = WorkType::all();

        $course = student::select('course')->orderBy('course')->distinct()->get();

        $subject = Teacher::where('email', auth()->user()->email)->first()->subject;

        return view('teacher.qualification.add-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course]);
    }

    public function AddQualification(Request $request, TeacherServices $requestTeacher)
    {
        $validator = Validator::make($request->all(), [
            'workType' => 'required',
            'percentage' => 'required',
            'course' => 'required',
        ]);

        //Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        if($requestTeacher->countPercentage($request)){
            return redirect()->back()->with('errors', 'Te has pasado el limite de porcentaje de Evaluacion.');
        }

        if($requestTeacher->existWork($request)){
            return redirect()->back()->with('errors', 'El Metodo Calificativo ya en uso.');
        }

        $requestTeacher->createPercentage($request);

        return redirect()->route('teacher.qualification');
    }

    public function showEditQualification(Request $request)
    {
        $metodos = WorkType::all();

        $course = student::select('course')->orderBy('course')->distinct()->get();

        $subject = Teacher::where('email', auth()->user()->email)->first()->subject;

        $search = Percentages::with('WorkType')->find($request->search);

        return view('teacher.qualification.edit-qualification', ['method' => $metodos, 'subject' => $subject, 'course' => $course, 'search' => $search]);
    }

    public function updateQualification(Request $request, TeacherServices $requestTeacher)
    {
        $validator = Validator::make($request->all(), [
            'workType' => 'required',
            'percentage' => 'required',
            'course' => 'required',
            'value' => 'required'
        ]);

        //Ver si las validaciones se cumplen
        if ($validator->fails()) {
            return redirect()->back()->with('errors', 'Los datos proporcionados son incorrectos.');
        }

        if($requestTeacher->countPercentage($request)){
            return redirect()->back()->with('errors', 'Te has pasado el limite de porcentaje de Evaluacion.');
        }

        if($requestTeacher->existWork($request)){
            return redirect()->back()->with('errors', 'El Metodo Calificativo ya en uso.');
        }

        $requestTeacher->updatePercentage($request);

        return redirect()->route('teacher.qualification');
    }

    public function deleteQualification(Request $request)
    {
        Percentages::find($request->search)->delete();

        return redirect()->route('teacher.qualification');
    }
}
