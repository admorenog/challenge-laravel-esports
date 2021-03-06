<?php

namespace App\Models;

use App\Exceptions\Game\GameNotRegisteredException;
use App\Models\GamePlayer\LeagueOfLegends;
use App\Models\GamePlayer\Valorant;

class GamePlayerFactory
{
    /**
     * @var string[] the Class mapping for every game, this can be used to instantiate just by a text
     */
    private static array $gamePlayerModels = [
        "LEAGUE OF LEGENDS" => LeagueOfLegends::class,
        "VALORANT" => Valorant::class,
    ];

    /**
     * Returns an instance of the game with the fields assigned
     * @param $gameName
     * @param $record
     * @return mixed
     * @throws GameNotRegisteredException
     */
    public static function create($gameName, $record) {

        if(in_array($gameName, array_keys(self::$gamePlayerModels))) {
            $gamePlayer = new self::$gamePlayerModels[$gameName]($record);
            $gamePlayer->name = $gameName;
        }
        else {
            throw new GameNotRegisteredException($gameName);
        }

        return $gamePlayer;
    }
}
