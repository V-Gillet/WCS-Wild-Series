<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $programDurationMinutes = 0;
        $seasons = $program->getSeasons();

        foreach ($seasons as $season) {
            $episodes = $season->getEpisodes();
            foreach ($episodes as $episode) {
                $programDurationMinutes += $episode->getDuration();
            }
        }
        $programDurationMinutesRest = fmod($programDurationMinutes, 60);
        $programDurationHours = round($programDurationMinutes / 60);
        $programDurationDays = round($programDurationHours / 24);

        return $programDurationDays . ' jours ' . $programDurationHours . ' heures et ' . $programDurationMinutesRest . ' minutes ';
    }
}
