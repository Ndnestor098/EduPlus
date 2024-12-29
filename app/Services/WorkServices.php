<?php
namespace App\Services;

use App\Models\Percentages;
use App\Models\Teacher;
use App\Models\Work;
use App\Models\WorkType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class WorkServices
{
    // Método para agregar un archivo de trabajo
    public function addFileWork(Request $request)
    {
        // Guardar rutas de archivos y de imágenes
        $filePaths = [];
        
        foreach ($request->file('files') as $files) {
            if ($files->isValid()) {
                $fileName = uniqid() . '.' . $files->getClientOriginalExtension();
                $filePath = $files->storeAs('public/files', $fileName);
                $filePaths[] = Storage::url($filePath);
            } else {
                return false;
            }
        }

        return $filePaths;
    }

    // Método para agregar una imagen de trabajo
    public function addImageWork(Request $request)
    {
        $imagePaths = [];
        
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/image', $imageName);
                $imagePaths[] = Storage::url($imagePath);
            } else {
                return false;
            }
        }

        return $imagePaths;
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

        // Crear un nuevo trabajo
        $work = Work::create([
            'title' => $request->title,
            'slug' => $request->title,
            'description' => $request->description,
            'scored' => floatval($scored->percentage),
            'course' => intval($request->course),
            'file' =>  $file ? json_encode($file) : null,
            'image' => $image ? json_encode($image) : null,
            'deliver' => $request->deliver,
            'subject' => $request->subject,
            'work_type_id' => WorkType::where('name', $request->qualification)->first()->id,
            'public' => $request->public
        ]);

        return $work;
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
            'course' => intval($request->course),
            'deliver' => $request->deliver,
            'work_type_id' => WorkType::where('name', $request->qualification)->first()->id,
            'public' => $request->public,
            'image' => $image ? json_encode($image) : $work->image,
            'file' => $file ? json_encode($file) : $work->file
        ];

        // Actualizar el trabajo con los cambios
        $work->update($updates);
    }
}