<?php
namespace App\Services;

use App\Models\Percentages;
use App\Models\Qualification;
use App\Models\WorkStudent;
use App\Models\WorkType;

class NoteServices
{
    // Método para calcular las notas
    public function calculateNotes($student, $method)
    {
        // Lista de materias
        $subjects = [
            'matematicas', 'fisica', 'ciencia', 'edu_fisica', 'historia',
            'ingles', 'literatura', 'arte', 'computacion', 'quimica'
        ];

        // Array para almacenar los trabajos calificados y las calificaciones finales
        $qualifiedWorks = [];
        $finalGrades = [];

        // Itera sobre cada materia
        foreach ($subjects as $subject) {
            // Obtiene los trabajos del estudiante para una materia específica
            $works = WorkStudent::whereHas('work', function ($query) use ($subject) {
                $query->where("subject", $subject);
            })
            ->where('student_id', $student->id)
            ->whereNotNull('qualification') // Asegúrate de que la calificación no sea nula
            ->get();

            // Si hay trabajos calificados, los agrega al array
            if ($works->count() > 0) {
                $qualifiedWorks[$subject] = $works;
            }
        }

        // Itera sobre cada materia nuevamente
        foreach ($subjects as $subject) {
            // Verifica si hay trabajos calificados para la materia
            if (isset($qualifiedWorks[$subject])) {
                $totalPercentage = 0; // Inicializa el total de porcentaje
                $countTask = 0; // Inicializa el conteo de tareas
                $qualification = 0; // Inicializa la suma de las calificaciones
                $totalNotes = 0; // Inicializa la nota total

                // Itera sobre los trabajos calificados de la materia
                foreach ($qualifiedWorks[$subject] as $value) {
                    $workType = WorkType::find($value->work->work_type_id); // Obtiene el tipo de trabajo

                    // Si el tipo de trabajo es igual al método
                    if ($workType->name == $method) {
                        $countTask += 1; // Incrementa el conteo con la cantidad de evaluaciones
                        $qualification += floatval($value->qualification); // Suma la calificación del trabajo

                        // Obtiene el porcentaje del trabajo
                        $percentageRecord = Percentages::where('work_type_id', $workType->id)
                            ->where('subject', $subject)
                            ->first();

                        // Establece el porcentaje total
                        $totalPercentage = floatval($percentageRecord->percentage);
                    }
                }
                
                // Calcula la nota total
                if($qualification && $countTask){
                    $totalNotes = $qualification / $countTask;
                }

                // Si la nota total es mayor que 0, calcula la nota final
                if ($totalNotes > 0) {
                    $finalGrades[$subject] = round($totalNotes * ($totalPercentage / 100), 2);
                } else {
                    $finalGrades[$subject] = 0; // O maneja esto de alguna manera adecuada para tu lógica
                }
            }
        }

        // Devuelve las calificaciones finales para cada materia
        return $finalGrades;
    }

    // Método para calcular la calificación final
    public function calculateQualification($finalGrades)
    {
        // Array para combinar las calificaciones por materia
        $combinedGrades = [];
    
        // Inicializar las materias con 0
        if (!empty($finalGrades)) {
            $subjects = array_keys(reset($finalGrades));
            $combinedGrades = [];

            foreach ($subjects as $subject) {
                $combinedGrades[$subject] = 0;
            }

            // Sumar las calificaciones por materia de todos los tipos
            foreach ($finalGrades as $type => $grades) {
                foreach ($grades as $subject => $grade) {
                    $combinedGrades[$subject] += $grade;
                }
            }

            // Redondear a dos decimales
            foreach ($combinedGrades as $subject => $grade) {
                $combinedGrades[$subject] = round($grade, 2);
            }
        }

        return $combinedGrades;
    }

    // Método para actualizar las calificaciones finales en la tabla
    public function updateQualification($student)
    {
        $method = [
            'Tarea', 'Proyecto', 'Exposicion', 'Participacion', 'Conducta', 'Examen oral', 'Examen escrito'
        ];

        foreach($method as $value){
            $finalGrades[$value] = $this->calculateNotes($student, $value);
        }

        $notes = $this->calculateQualification($finalGrades);

        foreach ($notes as $subject => $note) {
            Qualification::updateOrCreate(
                ['student_id' => $student->id],
                [$subject => $note]
            );
        }
    }
}
