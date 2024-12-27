<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Work;
use App\Models\WorkStudent;
use App\Models\WorkType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class StudentNotesController extends Controller
{
    //========================================Mostrar las calificaciones========================================
    public function showSubject($subject)
    {
        $student = Student::where('email', auth()->user()->email)->first();
        $works = Work::where('subject', $subject)->get();

        $searchID = [];
        $searchWorks = [];
        $workType = [];

        foreach ($works as $work) {
            $searchID[$work->id] = $work->id;
        }

        foreach ($searchID as $id) {
            $work = WorkStudent::with('work')->where('student_id', $student->id)->where('work_id', intval($id))->get();

            if(!$work->isEmpty())
                $searchWorks[$id] = $work;
        }

        $worksType = WorkType::all();

        foreach ($worksType as $value) {
            $workType[$value->id] = $value->name;
        }

        return view('student.note.note', ['works'=>$searchWorks, 'subject'=>$subject, 'workType'=>$workType]);
    }
}