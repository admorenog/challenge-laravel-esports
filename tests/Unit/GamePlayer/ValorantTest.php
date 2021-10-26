<?php

namespace Tests\Unit\GamePlayer;

use App\Models\GamePlayer\Valorant;
use Tests\TestCase;


class ValorantTest extends TestCase
{

    public function testScoreCalc(): void
    {
        $fields = [
            "player name" => "Example",
            "nickname" => "wrong",
            "team name" => "test",
            "winner" => false,
            "kills" => 10,
            "deads" => 2,
        ];

        $exampleWrong = new Valorant($fields);
        $ratingPoints = 5;
        $this->assertEquals($ratingPoints, $exampleWrong->getScore());
    }

    public function testScoreCalcTeamWinner(): void
    {
        $fields = [
            "player name" => "Example",
            "nickname" => "wrong",
            "team name" => "test",
            "winner" => true,
            "kills" => 10,
            "deads" => 2,
        ];

        $exampleWrong = new Valorant($fields);
        $ratingPoints = 15;
        $this->assertEquals($ratingPoints, $exampleWrong->getScore());
    }

    public function testWrongDeaths(): void
    {
        $fields = [
            "player name" => "Example",
            "nickname" => "wrong",
            "team name" => "test",
            "winner" => false,
            "kills" => 10,
            "deads" => 0,
        ];

        $exampleWrong = new Valorant($fields);
        $ratingPointsIfNoDeaths = 0;
        $this->assertEquals($ratingPointsIfNoDeaths, $exampleWrong->getScore());
    }
}
