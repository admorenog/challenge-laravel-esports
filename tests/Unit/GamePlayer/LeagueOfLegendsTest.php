<?php

namespace Tests\Unit\GamePlayer;

use App\Exceptions\GameFieldsMismatchException;
use App\Models\GamePlayer\LeagueOfLegends;
use Tests\TestCase;


class LeagueOfLegendsTest extends TestCase
{

    /**
     * @throws GameFieldsMismatchException
     */
    public function testDocScoreIsWrong(): void
    {
        /**
         * E.g. a player playing as a Mid with 10 kills, 5 deaths and no assists will be granted with 2 KDA points ((10 + 0) / 5 ).
         * Aggregating 2000 damage deal and 200 of healing (2 + 2000*0.03 + 200*0.01), the final result is 10 rating points.
         */
        $fields = [
            "player name" => "Example",
            "nickname" => "wrong",
            "team name" => "test",
            "winner" => false,
            "position" => "M",
            "kills" => 10,
            "deads" => 5,
            "assists" => 0,
            "damage deal" => 2000,
            "heal deal" => 200,
        ];

        $exampleWrong = new LeagueOfLegends($fields);
        $ratingPointsFromDoc = 10;
        $this->assertNotEquals($ratingPointsFromDoc, $exampleWrong->getScore());
    }

    /**
     * @throws GameFieldsMismatchException
     */
    public function testDocScoreWithCalculations(): void
    {
        /**
         * E.g. a player playing as a Mid with 10 kills, 5 deaths and no assists will be granted with 2 KDA points ((10 + 0) / 5 ).
         * Aggregating 2000 damage deal and 200 of healing (2 + 2000*0.03 + 200*0.01), the final result is 10 rating points.
         */
        $fields = [
            "player name" => "Example",
            "nickname" => "right",
            "team name" => "test",
            "winner" => false,
            "position" => "M",
            "kills" => 10,
            "deads" => 5,
            "assists" => 0,
            "damage deal" => 2000,
            "heal deal" => 200,
        ];

        $exampleRight = new LeagueOfLegends($fields);

        $kda = ((10 + 0) / 5);
        $ratingPointsCalcFromDoc = $kda + 2000*0.03 + 200*0.01;
        $this->assertEquals($ratingPointsCalcFromDoc, $exampleRight->getScore());
    }

    /**
     * @throws GameFieldsMismatchException
     */
    public function testScoreNoDeaths(): void
    {
        /**
         * E.g. a player playing as a Mid with 10 kills, 5 deaths and no assists will be granted with 2 KDA points ((10 + 0) / 5 ).
         * Aggregating 2000 damage deal and 200 of healing (2 + 2000*0.03 + 200*0.01), the final result is 10 rating points.
         */
        $fields = [
            "player name" => "Example",
            "nickname" => "right",
            "team name" => "test",
            "winner" => false,
            "position" => "M",
            "kills" => 10,
            "deads" => 0,
            "assists" => 0,
            "damage deal" => 2000,
            "heal deal" => 200,
        ];

        $exampleRight = new LeagueOfLegends($fields);

        $ratingPointsNoDeaths = 0;
        $this->assertEquals($ratingPointsNoDeaths, $exampleRight->getScore());
    }

    /**
     * @throws GameFieldsMismatchException
     */
    public function testScoreCalcTeamWinner(): void
    {
        /**
         * E.g. a player playing as a Mid with 10 kills, 5 deaths and no assists will be granted with 2 KDA points ((10 + 0) / 5 ).
         * Aggregating 2000 damage deal and 200 of healing (2 + 2000*0.03 + 200*0.01), the final result is 10 rating points.
         */
        $fields = [
            "player name" => "Example",
            "nickname" => "right",
            "team name" => "test",
            "winner" => true,
            "position" => "M",
            "kills" => 10,
            "deads" => 5,
            "assists" => 0,
            "damage deal" => 2000,
            "heal deal" => 200,
        ];

        $exampleRight = new LeagueOfLegends($fields);

        $kda = ((10 + 0) / 5);
        $ratingPointsCalcFromDoc = $kda + 2000*0.03 + 200*0.01;
        $ratingPointsCalcFromDoc += 10;
        $this->assertEquals($ratingPointsCalcFromDoc, $exampleRight->getScore());
    }
}
