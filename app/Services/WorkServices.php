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
    // Método para agregar un archivo de trabajo
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

    // Método para agregar una imagen de trabajo
    public function addImageWork(Request $request)
    {
        try {
            // Validar la imagen en la solicitud
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096', // Tamaño máximo ajustado a 4MB
            ]);

            // Verificar si la imagen está presente en la solicitud
            if ($request->hasFile('image')) {
                // Obtener la imagen del request
                $image = $request->file('image');

                // Verificar si la imagen es válida
                if ($image->isValid()) {
                    // Generar un nombre aleatorio para la imagen
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();

                    // Mover la imagen a la carpeta de almacenamiento con el nuevo nombre
                    $storedImage = $image->storeAs('public/images', $imageName);

                    // Obtener la URL de la imagen almacenada
                    $url = Storage::url($storedImage);

                    // Devolver la URL de la imagen almacenada
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

    // Método para agregar un trabajo
    public function addWork(Request $request, $file = null, $image = null)
    {
        // Obtener el porcentaje de puntuación del tipo de trabajo
        $scored = Percentages::whereHas('workType', function ($query) use ($request) {
                $query->where('name', $request->qualification);
            })
            ->with('workType')
            ->first();

        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Crear un nuevo trabajo
        Work::create([
            'title' => $request->title,
            'slug' => $request->title,
            'description' => $request->description,
            'scored' => floatval($scored->percentage),
            'mtcf' => $scored->workType->name,
            'course' => intval($request->course),
            'file' => $file,
            'image' => $image,
            'subject' => $teacher->subject,
            'deliver' => $request->deliver,
            'teacher_id' => intval($teacher->id),
            'public' => $request->public
        ]);
    }

    // Método para actualizar un trabajo
    public function updateWork(Request $request, $file = null, $image = null)
    {
        // Obtener el porcentaje de puntuación del tipo de trabajo
        $scored = Percentages::whereHas('workType', function ($query) use ($request) {
            $query->where('name', $request->qualification);
        })
        ->with('workType')
        ->first();

        // Encontrar el trabajo por su ID
        $work = Work::find($request->id);

        // Definir los campos de actualización
        $updates = [
            'title' => $request->title,
            'slug' => $request->title,
            'description' => $request->description,
            'scored' => floatval($scored->percentage),
            'mtcf' => $scored->workType->name,
            'course' => intval($request->course),
            'deliver' => $request->deliver,
            'public' => $request->public
        ];

        // Si se proporciona una nueva imagen, actualizarla
        if($image){
            $updates['image'] = $image;
        }

        // Si se proporciona un nuevo archivo, actualizarlo
        if($file){
            $updates['file'] = $file;
        }

        // Actualizar el trabajo con los cambios
        $work->update($updates);
    }
}