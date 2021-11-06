<?php

namespace App\Exceptions\Game;

use Exception;
use Throwable;

class GameTeamStatsMismatchException extends Exception
{
    const ERROR_TEAMS_SCORES_NOT_MATCH = "errors.game.stats_mismatch";

    public function __construct($file, $aTeamStats, $bTeamStats, $code = 0, Throwable $previous = null)
    {
        $message = trans(self::ERROR_TEAMS_SCORES_NOT_MATCH, [
            'file' => $file,

            'team_a' => $aTeamStats['team'],
            'team_a_kills' => $aTeamStats['kills'],
            'team_a_deaths' => $aTeamStats['deaths'],

            'team_b' => $bTeamStats['team'],
            'team_b_kills' => $bTeamStats['kills'],
            'team_b_deaths' => $bTeamStats['deaths']
        ]);

        parent::__construct($message, $code, $previous);
    }

}
