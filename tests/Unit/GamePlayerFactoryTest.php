<?php

namespace Tests\Unit;

use App\Exceptions\GameFieldsMismatchException;
use App\Exceptions\GameNotRegisteredException;
use App\Models\GamePlayer\LeagueOfLegends;
use App\Models\GamePlayer\Valorant;
use App\Models\GamePlayerFactory;
use Exception;
use Tests\TestCase;


class GamePlayerFactoryTest extends TestCase
{
    /**
     * Testing the player factory (game matching and field assign)
     *
     * @return void
     */
    public function testGamePlayerFactoryLeagueOfLegends()
    {
        $gameName = "LEAGUE OF LEGENDS";
        $record = ["player 1", "nick1", "Team A", "true", "T", "10", "5", "2", "2000", "200"];
        $gamePlayer = GamePlayerFactory::create($gameName, $record);
        $this->assertInstanceOf(LeagueOfLegends::class, $gamePlayer);
    }

    /**
     * Testing the player factory (game matching and field assign)
     *
     * @return void
     */
    public function testGamePlayerFactoryValorant()
    {
        $gameName = "VALORANT";
        $record = ["player 1", "nick1", "Team A", "false", "2", "6"];
        $gamePlayer = GamePlayerFactory::create($gameName, $record);
        $this->assertInstanceOf(Valorant::class, $gamePlayer);
    }

    /**
     * Testing the player factory (game matching and field assign)
     *
     * @return void
     */
    public function testGamePlayerFactoryNotFound()
    {
        try {
            $gameName = "Dota 2";
            $record = ["player 1", "nick1", "Team A", "false", "2", "6"];
            GamePlayerFactory::create($gameName, $record);
        }
        catch(Exception $ex) {
            $this->assertInstanceOf(GameNotRegisteredException::class, $ex);
        }
    }

    /**
     * Testing the player factory (game matching and field assign)
     *
     * @return void
     */
    public function testGamePlayerFactoryFieldsMismatchLessThanExpected()
    {
        try {
            $gameName = "VALORANT";
            $record = ["player 1", "nick1", "Team A", "false", "2"];
            GamePlayerFactory::create($gameName, $record);
        }
        catch(Exception $ex) {
            $this->assertInstanceOf(GameFieldsMismatchException::class, $ex);
        }
    }
    /**
     * Testing the player factory (game matching and field assign)
     *
     * @return void
     */
    public function testGamePlayerFactoryFieldsMismatchMoreThanExpected()
    {
        try {
            $gameName = "VALORANT";
            $record = ["player 1", "nick1", "Team A", "false", "2", "4", "5"];
            GamePlayerFactory::create($gameName, $record);
        }
        catch(Exception $ex) {
            $this->assertInstanceOf(GameFieldsMismatchException::class, $ex);
        }
    }
}
