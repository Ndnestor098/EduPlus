<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Qualification;
use App\Models\Student;
use App\Services\NoteServices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class QualificationsController extends Controller
{
    //========================================Mostrar las calificaciones========================================
    public function qualification(NoteServices $noteRequest)
    {
        $student = Student::where('email', auth()->user()->email)->first();

        $noteRequest->updateQualification($student);

        $note = Qualification::find($student->id);
        
        return view('student.note.index', ['subjects'=>$note]);
    }
}