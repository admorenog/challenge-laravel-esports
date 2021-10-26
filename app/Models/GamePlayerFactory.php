<?php

namespace App\Models;

use App\Exceptions\GameNotRegisteredException;
use App\Models\GamePlayer\LeagueOfLegends;
use App\Models\GamePlayer\Valorant;

class GamePlayerFactory
{
    private static $gamePlayerModels = [
        "LEAGUE OF LEGENDS" => LeagueOfLegends::class,
        "VALORANT" => Valorant::class,
    ];

    public static function create($gameName, $record) {

        if(in_array($gameName, array_keys(self::$gamePlayerModels))) {
            $gamePlayer = new self::$gamePlayerModels[$gameName]($record);
            $gamePlayer->name = $gameName;
        }
        else {
            throw new GameNotRegisteredException(sprintf("Cannot find %s game, maybe its not registered.", $gameName));
        }

        return $gamePlayer;
    }
}
