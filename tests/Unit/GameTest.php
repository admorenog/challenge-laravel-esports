<?php

namespace Tests\Unit;

use App\Exceptions\GameCSVNotFoundException;
use App\Exceptions\GameDuplicatedNickException;
use App\Exceptions\GameEmptyRecordException;
use App\Exceptions\GameFieldsMismatchException;
use App\Exceptions\GameTeamStatsMismatchException;

use Exception;
use App\Models\Game;
use Tests\TestCase;


class GameTest extends TestCase
{
    /**
     * Testing the validations
     *
     * @return void
     */
    public function testValidations()
    {
        $folders = [
            "rankings_good" => null,
            "rankings" => GameFieldsMismatchException::class,
            "rankings_empty" => GameCSVNotFoundException::class,
            "rankings_lol_fixed" => GameEmptyRecordException::class,
            "rankings_valorant_fields_fixed" => GameEmptyRecordException::class,
            "rankings_valorant_fields_rows_fixed" => GameDuplicatedNickException::class,
            "rankings_kills_death_fail" => GameTeamStatsMismatchException::class,
        ];
        foreach($folders as $folder => $exceptionClass)
        {
            try {
                Game::getPlayersOrderedByScores($folder);
                $this->assertNull($exceptionClass);
            }
            catch(Exception $ex) {
                $this->assertInstanceOf($exceptionClass, $ex);
            }
        }
    }

    public function testFinalResult()
    {
        $scores = Game::getPlayersOrderedByScores("rankings_good");
        $this->assertIsArray($scores);
        foreach($scores as $score)
        {
            $this->assertArrayHasKey("total", $score);
            $this->assertArrayHasKey("nickname", $score);
            $this->assertArrayHasKey("games", $score);
        }
    }
}
