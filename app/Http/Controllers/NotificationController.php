<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function ShowNotifications()
    {
        $user = auth()->user();

        $teacher = Teacher::where('email', $user->email)->first();
        if ($teacher) {
            $notify = $teacher->notifications;
        } 

        $student = Student::where('email', $user->email)->first();
        if ($student) {
            $notify = $student->notifications;
        }

        $subject = null;
        if(Teacher::where('email', auth()->user()->email)->first()){
            $subject = Teacher::where('email', auth()->user()->email)->first()->subject;
        }

        return view('notify', ['notify' => $notify, 'subject' => $subject]);
    }

    public function readNotifications()
    {
        $user = auth()->user();

        $teacher = Teacher::where('email', $user->email)->first();
        if ($teacher) {
            return $teacher->unreadNotifications;
        } 

        $student = Student::where('email', $user->email)->first();
        if ($student) {
            return $student->unreadNotifications;
        }

        return response()->json(['length'=>0]);
    }
}
