<?php

namespace App\Models\GamePlayer;

use App\Models\GamePlayer;

class Valorant extends GamePlayer
{
    public ?string $name = "VALORANT";

    protected array $fieldNames = [
        "player name",
        "nickname",
        "team name",
        "winner",
        "kills",
        "deaths",
    ];

    function getDeaths() : int
    {
        return (int)$this->fields["deaths"];
    }

    function getKills() : int
    {
        return (int)$this->fields["kills"];
    }

    function getKDScore() : float
    {
        // 00_Code Test-Best Multi-eSports Player.docx: 0 Deaths is not a valid value
        return $this->getDeaths() != 0 ? $this->getKills() / $this->getDeaths() : 0;
    }

    function getScore() : float
    {
        return $this->getKDScore() + $this->getWinnerBonusScore();
    }
}
