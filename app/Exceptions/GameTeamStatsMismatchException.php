<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameTeamStatsMismatchException extends Exception
{
    const ERROR_TEAMS_SCORES_NOT_MATCH = "The teams scores doesn't match in %s, %s %s %s %s %s %s, %s %s %s %s %s %s";

    public function __construct($file, $stats, $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_TEAMS_SCORES_NOT_MATCH,
            $file,
            $stats[0]['team'], 'kills', $stats[0]['kills'],
            $stats[1]['team'], 'deaths', $stats[1]['deaths'],
            $stats[1]['team'], 'kills', $stats[1]['kills'],
            $stats[0]['team'], 'deaths', $stats[0]['deaths']
        );
        parent::__construct($message, $code, $previous);
    }

}
