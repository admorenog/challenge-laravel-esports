<?php

namespace App\Models;

use App\Exceptions\GameFieldsMismatchException;
use ValueError;

abstract class GamePlayer
{
    const ERROR_FIELDS_MISMATCH = "Processing %s file there was an error\n a record doesn't have the required fields: the game has %s field names and the record has %s fields \n%s\n%s";

    protected $fields = [];
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

    public function getNickname() :string
    {
        return $this->fields["nickname"];
    }

    public function getTeam() :string
    {
        return $this->fields["team name"];
    }

    public function getKills() : int
    {
        return (int)$this->fields["kills"];
    }

    public function getDeaths() : int
    {
        return (int)$this->fields["deaths"];
    }

    protected function isWinner() : bool
    {
        return $this->fields["winner"] == "true";
    }

    protected function getWinnerBonusScore() : int
    {
        return $this->isWinner() ? 10 : 0;
    }

    function getScore() : float
    {
        return 0;
    }
}
