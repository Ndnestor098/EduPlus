<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function showWorks(Request $request)
    {
        $student = Student::with('works')->where('email', auth()->user()->email)->first();

        $excludedWorkIds = $student->works->pluck('work_id');

        $works = Work::where('course', $student->course)
                    ->whereNotIn('id', $excludedWorkIds)
                    ->today()
                    ->public()
                    
                    ->subject($request->get('subject'))
                    ->get();

        $subjects = Teacher::select('subject')
                            ->orderBy('subject')
                            ->distinct()
                            ->get();


        return view('student.works.index', ['works'=>$works, 'subjects'=>$subjects]);
    }

    public function readWork(Request $request, $nameWork)
    {
        $work = Work::where('slug', $nameWork)->first();

        return view('student.works.show', compact('work'));
    }

    public function upWork(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx|max:15420', // permite archivos PDF y documentos de Word de hasta 2MB
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:15420', // permite imágenes de hasta 2MB
        ]);

         // Guardar archivos
        $filePaths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $files) {
                if ($files->isValid()) {
                    $fileName = uniqid() . '.' . $files->getClientOriginalExtension();
                    $filePath = $files->storeAs('public/image', $fileName);
                    $filePaths[] = Storage::url($filePath);
                }
            }
        }

        // Guardar imágenes
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('public/image', $imageName);
                    $imagePaths[] = Storage::url($imagePath);
                }
            }
        }

        $student = Student::where('email', auth()->user()->email)->first();

        WorkStudent::create([
            'name' => $student->name,
            'slug' => $student->name,
            'course' => $student->course,
            'file' => $filePaths ? json_encode($filePaths) : null,
            'image' => $imagePaths ? json_encode($imagePaths) : null,
            'subject' => $request->input('subject'),
            'student_id' => $student->id,
            'work_id' => $request->input('work_id'),
        ]);

        return redirect()->route('student.works');
    }
}
