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
    protected $players = [];

    function __construct(string $filePath)
    {
        $records = $this->getRecords($filePath);

        $this->name = $this->getGameName($records);
        $this->players = $this->getPlayers($records);
        $this->checkStatsMatch();
    }

    public static function getPlayersOrderedByScores(?string $folder = null) : array
    {
        $players = self::getPlayersWithScores($folder);
        usort($players, function($playerA, $playerB) {
            return $playerA["total"] < $playerB["total"];
        });
        return $players;
    }

    public static function getPlayersWithScores(?string $folder = null) : array
    {
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

    public static function get(?string $folder = 'rankings_good')
    {
        $filePaths = DataSourceCsv::getFilePaths($folder);
        $games = [];
        foreach($filePaths as $filePath) {
            $game = new Game($filePath);
            $games[] = $game;
        }

        return $games;
    }

    private function getRecords(string $filePath) : array
    {
        $records = DataSourceCsv::getRecords($filePath);
        return $records;
    }

    private function getGameName(array &$records) : string
    {
        $gameNameRecord = array_shift($records);
        return $gameNameRecord[self::FIELD_GAME_NAME];
    }

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
