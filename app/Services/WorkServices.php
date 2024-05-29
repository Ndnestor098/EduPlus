<?php
namespace App\Services;

use App\Models\Percentages;
use App\Models\Teacher;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class WorkServices
{
    public function addFileWork(Request $request)
    {
        try {
            // Validar el archivo en la solicitud
            $request->validate([
                'file' => 'required|file|mimes:pdf,xls,xlsx,doc,docx,ppt,pptx|max:20480', // Tamaño máximo ajustado a 20MB
            ]);

            // Verificar si el archivo está presente en la solicitud
            if ($request->hasFile('file')) {
                // Obtener el archivo del request
                $file = $request->file('file');

                // Verificar si el archivo es válido
                if ($file->isValid()) {
                    // Generar un nombre aleatorio para el archivo
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                    // Mover el archivo a la carpeta de almacenamiento con el nuevo nombre
                    $storedFile = $file->storeAs('public/files', $fileName);

                    // Obtener la URL del archivo almacenado
                    $url = Storage::url($storedFile);

                    // Devolver la URL del archivo almacenado
                    return $url;
                } else {
                    return false;
                }
            }

        } catch (ValidationException $e) {
            // Capturar los errores de validación y devolverlos en la respuesta
            return false;
        }
    }

    public function addImageWork(Request $request)
    {
        try {
            // Validar el archivo en la solicitud
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096', // Tamaño máximo ajustado a 20MB
            ]);

            // Verificar si el archivo está presente en la solicitud
            if ($request->hasFile('image')) {
                // Obtener el archivo del request
                $image = $request->file('image');

                // Verificar si el archivo es válido
                if ($image->isValid()) {
                    // Generar un nombre aleatorio para el archivo
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();

                    // Mover el archivo a la carpeta de almacenamiento con el nuevo nombre
                    $storedImage = $image->storeAs('public/image', $imageName);

                    // Obtener la URL del archivo almacenado
                    $url = Storage::url($storedImage);

                    // Devolver la URL del archivo almacenado
                    return $url;
                } else {
                    return false;
                }
            }

        } catch (ValidationException $e) {
            // Capturar los errores de validación y devolverlos en la respuesta
            return false;
        }
    }

    public function addWork(Request $request, $file = null, $image = null)
    {
        $scored = Percentages::whereHas('workType', function ($query) use ($request) {
                $query->where('name', $request->qualification);
            })
            ->with('workType')
            ->first();

        return $scored;

        $teacher = Teacher::where('email', auth()->user()->email)->first();

        Work::create([
            'title' => $request->title,
            'slug' => $request->title,
            'description' => $request->description,
            'scored' => floatval($scored->percentage),
            'mtcf' => $scored->workType->name,
            'course' => intval($request->course),
            'pdf' => $file,
            'img' => $image,
            'subject' => $teacher->subject,
            'deliver' => $request->deliver,
            'teacher_id' => intval($teacher->id),
        ]);

    }

    public function updateWork(Request $request)
    {

    }
}