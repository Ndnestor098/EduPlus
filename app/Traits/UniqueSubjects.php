<?php

namespace App\Traits;

trait UniqueSubjects
{
    protected static $availableSubjects = [
        'matematicas', 'fisica', 'ciencia', 'edu_fisica', 'historia',
        'ingles', 'literatura', 'arte', 'computacion', 'quimica'
    ];

    protected static function getUniqueSubject()
    {
        if (empty(self::$availableSubjects)) {
            throw new \Exception('No more unique subjects available');
        }

        $key = array_rand(self::$availableSubjects);
        $subject = self::$availableSubjects[$key];

        // Remove the subject from the available list
        unset(self::$availableSubjects[$key]);

        // Re-index the array
        self::$availableSubjects = array_values(self::$availableSubjects);

        return $subject;
    }
}
