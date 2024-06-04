<?php
namespace App\Services;

use App\Models\Percentages;
use App\Models\Teacher;
use App\Models\WorkType;
use Illuminate\Http\Request;

class TeacherServices
{
    // Función para contar el porcentaje total de evaluación
    public function countPercentage(Request $request)
    {
        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener los porcentajes de evaluación para el curso del profesor
        $percentages = Percentages::with('workType')->where('teacher_id', $teacher->id)->where('course', $request->course)->get();

        $totalPercentage = 0;
        foreach ($percentages as $key) {
            // Sumar los porcentajes de todos los tipos de trabajo, excepto el tipo de trabajo actual si se proporciona
            if($request->value == $key->id){
                $totalPercentage += intval($request->percentage); 
                continue;
            }

            $totalPercentage += intval($key->percentage);
        }

        // Si no se proporciona ningún valor (actualización), se suma el porcentaje proporcionado
        if(!$request->value){
            $totalPercentage += intval($request->percentage); 
        }

        // Si el total de porcentaje supera el 100%, retorna verdadero, de lo contrario, falso
        if($totalPercentage > 100){
            return true;
        }

        return false;
    }

    // Función para verificar si el tipo de trabajo ya existe para el curso del profesor
    public function existWork(Request $request)
    {
        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Obtener los porcentajes de evaluación para el curso del profesor
        $percentages = Percentages::with('workType')->where('teacher_id', $teacher->id)->where('course', $request->course)->get();

        // Verificar si el tipo de trabajo ya está asignado para el curso del profesor
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

    // Función para crear un nuevo porcentaje de evaluación
    public function createPercentage(Request $request)
    {
        // Obtener el profesor actualmente autenticado
        $teacher = Teacher::where('email', auth()->user()->email)->first();

        // Crear una nueva instancia de porcentaje de evaluación
        $percentage = new Percentages();

        // Asignar valores proporcionados
        $percentage->percentage = $request->percentage;
        $percentage->course = $request->course;
        $percentage->work_type_id = WorkType::where('name', $request->workType)->first()->id;
        $percentage->teacher_id = $teacher->id;

        // Guardar el nuevo porcentaje de evaluación en la base de datos
        $percentage->save();
    }

    // Función para actualizar un porcentaje de evaluación existente
    public function updatePercentage(Request $request)
    {
        // Encontrar el porcentaje de evaluación por su ID
        $percentage = Percentages::find($request->value);

        // Actualizar los valores proporcionados
        $percentage->percentage = $request->percentage;
        $percentage->course = $request->course;
        $percentage->work_type_id = WorkType::where('name', $request->workType)->first()->id;

        // Guardar los cambios en la base de datos
        $percentage->save();
    }
}