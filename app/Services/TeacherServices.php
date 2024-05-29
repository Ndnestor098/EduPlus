<?php
namespace App\Services;

use App\Models\Percentages;
use App\Models\Teacher;
use App\Models\WorkType;
use Illuminate\Http\Request;

class TeacherServices
{
    public function countPercentage(Request $request)
    {
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        $percentages = Percentages::with('workType')->where('teacher_id', $teacher->id)->where('course', $request->course)->get();

        $valor = 0;
        foreach ($percentages as $key) {
            if($request->value == $key->id){
                $valor += intval($request->percentage); 
                continue;
            }

            $valor += intval($key->percentage);
        }

        if(!$request->value){
            $valor += intval($request->percentage); 
        }

        if($valor > 100){
            return true;
        }

        return false;
    }

    public function existWork(Request $request)
    {
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        $percentages = Percentages::with('workType')->where('teacher_id', $teacher->id)->where('course', $request->course)->get();

        if(!$request->search){
            foreach ($percentages as $item) {
                if ($request->workType == $item->workType->name) {
                    return true;
                }
            }
        }else{
            foreach($percentages as $item){
                if ($request->search == $item->id && $request->workType == $item->workType->name) {
                    return false;
                    break;
                }else{
                    if($request->workType == $item->workType->name){
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function createPercentage(Request $request)
    {
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        $percentage = new Percentages();

        $percentage->percentage = $request->percentage;
        $percentage->subject = $teacher->subject;
        $percentage->course = $request->course;
        $percentage->work_type_id = WorkType::where('name', $request->workType)->first()->id;
        $percentage->teacher_id = $teacher->id;

        $percentage->save();
    }

    public function updatePercentage(Request $request)
    {
        $percentage = Percentages::find($request->value);

        $percentage->percentage = $request->percentage;
        $percentage->course = $request->course;
        $percentage->work_type_id = WorkType::where('name', $request->workType)->first()->id;

        $percentage->save();
    }
}