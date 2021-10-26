<?php

namespace App\Models;

use App\Exceptions\GameFieldsMismatchException;
use ValueError;

abstract class GamePlayer
{
    const ERROR_FIELDS_MISMATCH = "Processing %s file there was an error\n a record doesn't have the required fields: the game has %s field names and the record has %s fields \n%s\n%s";

    protected array $fields = [];

    /**
     * Matchs the record fields with the field names provided in $this->fieldNames protected property.
     * @param $record
     * @throws GameFieldsMismatchException
     */
    function __construct($record)
    {
        try {
            $this->fields = array_combine($this->fieldNames, $record);
        }
        catch(ValueError) {
            throw new GameFieldsMismatchException(sprintf(self::ERROR_FIELDS_MISMATCH,
                $this->name, count($this->fieldNames), count($record), json_encode($this->fieldNames), json_encode($record)));
        }
    }

    /**
     * Returns the nickname of the player.
     * This field is mandatory in every subclass
     * @return string
     */
    public function getNickname() :string
    {
        return $this->fields["nickname"];
    }

    /**
     * returns the name of the team of this player.
     * This field is mandatory in every subclass
     * @return string
     */
    public function getTeam() :string
    {
        return $this->fields["team name"];
    }

    /**
     * Kills in game, used to calc scores
     * @return int
     */
    public function getKills() : int
    {
        return (int)$this->fields["kills"];
    }

    /**
     * Deaths in match, used to calc scores.
     * @return int
     */
    public function getDeaths() : int
    {
        return (int)$this->fields["deaths"];
    }

    /**
     * Returns if the player is in the winner team, used to calc scores.
     * @return bool
     */
    protected function isWinner() : bool
    {
        return $this->fields["winner"] == "true";
    }

    /**
     * The bonus for being in a winner team.
     * @return int
     */
    protected function getWinnerBonusScore() : int
    {
        return $this->isWinner() ? 10 : 0;
    }

    /**
     * The main function that every subclass should implements to calc the score.
     * @return float
     */
    function getScore() : float
    {
        return 0;
    }
}
