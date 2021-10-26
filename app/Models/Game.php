<?php

namespace App\Models;

use App\Exceptions\GameDuplicatedNickException;
use App\Exceptions\GameTeamStatsMismatchException;
use App\Models\Game\DataSourceCsv;

class Game
{
    const FIELD_GAME_NAME = 0;

    const ERROR_NICK_DUPLICATED = "The %s nickname is not unique in %s file";
    const ERROR_TEAMS_SCORES_NOT_MATCH = "The teams scores doesn't match in %s, %s %s %s %s %s %s, %s %s %s %s %s %s";

    public ?string $name = null;
    protected array $players = [];

    /**
     * Instantiate the players of the detected game and checks if the stats match and if the nick is unique in the game.
     * @param string $filePath
     * @throws GameDuplicatedNickException
     * @throws GameTeamStatsMismatchException
     */
    function __construct(string $filePath)
    {
        $records = $this->getRecords($filePath);

        $this->name = $this->getGameName($records);
        $this->players = $this->getPlayers($records);
        $this->checkStatsMatch();
    }

    /**
     * Returns a list of players ordered by their score
     * @param string|null $folder
     * @return array
     */
    public static function getPlayersOrderedByScores(?string $folder = null) : array
    {
        $players = self::getPlayersWithScores($folder);
        usort($players, function($playerA, $playerB) {
            return $playerA["total"] < $playerB["total"];
        });
        return $players;
    }

    /**
     * Returns a list of players with their scores
     * @param string|null $folder
     * @return array
     */
    public static function getPlayersWithScores(?string $folder = null) : array
    {
        /**
         * @var Game[]
         */
        $gamesWithPlayers = Game::get($folder);

        $players = [];
        foreach($gamesWithPlayers as $gameWithPlayers)
        {
            $playersInGame = $gameWithPlayers->getScores();
            foreach($playersInGame as $playerInGame)
            {
                $nickname = $playerInGame["nickname"];
                if(!isset($players[$nickname]))
                {
                    $players[$nickname] = ["games" => [], "total" => 0];
                }

                $players[$nickname]["games"][] = $playerInGame;
                $players[$nickname]["nickname"] = $nickname;
                $players[$nickname]["total"] += $playerInGame["score"];
            }
        }
        return $players;
    }

    /**
     * Returns an array with instances of Games with players of this game.
     *
     * @param string|null $folder
     * @return array
     * @throws \App\Exceptions\GameCSVNotFoundException
     * @throws \App\Exceptions\GameFolderNotFoundException
     */
    public static function get(?string $folder = 'rankings_good') : array
    {
        $filePaths = DataSourceCsv::getFilePaths($folder);
        $games = [];
        foreach($filePaths as $filePath) {
            $game = new Game($filePath);
            $games[] = $game;
        }

        return $games;
    }

    /**
     * Connects to the custom csv file reader and returns the records.
     * @param string $filePath
     * @return array
     * @throws \App\Exceptions\GameEmptyRecordException
     */
    private function getRecords(string $filePath) : array
    {
        $records = DataSourceCsv::getRecords($filePath);
        return $records;
    }

    /**
     * Returns the game name retrieved by the first line of the csv file
     * @param array $records
     * @return string
     */
    private function getGameName(array &$records) : string
    {
        $gameNameRecord = array_shift($records);
        return $gameNameRecord[self::FIELD_GAME_NAME];
    }

    /**
     * Return the list of players instantiated by the game name and the records retrieved by the csv file
     * @param array $records
     * @return array
     * @throws GameDuplicatedNickException
     * @throws \App\Exceptions\GameNotRegisteredException
     */
    private function getPlayers(array $records) : array
    {
        $players = [];
        foreach($records as $record)
        {
            $player = GamePlayerFactory::create($this->name, $record);

            if(in_array($player->getNickname(), array_keys($players))) {
                throw new GameDuplicatedNickException(sprintf(self::ERROR_NICK_DUPLICATED, $player->getNickname(), $this->name));
            }
            $players[$player->getNickname()] = $player;
        }
        return $players;
    }

    /**
     * Validates if the Team A and Team B scores match, kills of Team A should match with deaths of Team B,
     * and otherwise
     * @throws GameTeamStatsMismatchException
     */
    private function checkStatsMatch() : void
    {
        $teamsStats = [];
        foreach($this->players as $player) {
            if(!isset($teamsStats[$player->getTeam()]))
            {
                $teamsStats[$player->getTeam()] = ["kills" => 0, "deaths" => 0, 'team' => ''];
            }
            $teamsStats[$player->getTeam()]['kills'] += $player->getKills();
            $teamsStats[$player->getTeam()]['deaths'] += $player->getDeaths();
            $teamsStats[$player->getTeam()]['team'] = $player->getTeam();
        }

        $teamsStats = array_values($teamsStats);
        if($teamsStats[0]['kills'] != $teamsStats[1]['deaths']
        || $teamsStats[1]['kills'] != $teamsStats[0]['deaths'])
        {
            throw new GameTeamStatsMismatchException(sprintf(self::ERROR_TEAMS_SCORES_NOT_MATCH,
                $this->name,
                $teamsStats[0]['team'], 'kills', $teamsStats[0]['kills'],
                $teamsStats[1]['team'], 'deaths', $teamsStats[1]['deaths'],
                $teamsStats[1]['team'], 'kills', $teamsStats[1]['kills'],
                $teamsStats[0]['team'], 'deaths', $teamsStats[0]['deaths']
            ));
        }
    }

    /**
     * returns the array list of every player with its score.
     * @return array
     */
    public function getScores() : array
    {
        $scores = [];

        foreach($this->players as $player) {
            $scores[] = [
                "nickname" => $player->getNickname(),
                "game" => $this->name,
                "score" => $player->getScore()
            ];
        }

        return $scores;
    }
}
