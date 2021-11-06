<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;

use Exception;
use App\Exceptions\Game\GameCSVNotFoundException;
use App\Exceptions\Game\GameDuplicatedNickException;
use App\Exceptions\Game\GameEmptyRecordException;
use App\Exceptions\Game\GameFolderNotFoundException;
use App\Exceptions\Game\GameNotRegisteredException;
use App\Exceptions\Game\GameTeamStatsMismatchException;

use App\Models\Game;

class BestPlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'best_player
        {folder=rankings_good : The possible values are: rankings_good, rankings_lol_fixed, rankings_valorant_field_fixed, rankings_valorant_field_rows_fixed and rankings_kills_death_fail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a command to calculate who is the best eSport player between the csv files provided';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GameCSVNotFoundException
     * @throws GameDuplicatedNickException
     * @throws GameEmptyRecordException
     * @throws GameFolderNotFoundException
     * @throws GameNotRegisteredException
     * @throws GameTeamStatsMismatchException
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $folder = $this->argument("folder");
        try {
            $players = Game::getPlayersOrderedByScores($folder);
            $bestPlayer = reset($players);

            if(app()->isLocal()) {
                $this->info("-- RANKING --");

                $this->table(["nickname", "score", "games"], self::getRowsFormatted($players));
            }

            $this->info("-- BEST PLAYER --");

            $this->table(["nickname", "score", "games"], self::getRowsFormatted([$bestPlayer]));
        }
        catch(Exception $ex) {
            $this->error($ex->getMessage());

            $verbosityLevel = $this->getOutput()->getVerbosity();
            if($verbosityLevel >= OutputInterface::VERBOSITY_DEBUG) {
                throw $ex;
            }
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Formats the rows to show in the table command function
     * @param $players
     * @return array
     */
    public static function getRowsFormatted($players): array
    {
        $rows = [];
        foreach($players as $player) {
            $gameScores = array_column($player["games"], "score", "game");

            $gameScores = array_map(function($game, $score) {
                return sprintf("%s (%s)", $game, $score);
            }, array_keys($gameScores), $gameScores);

            $record = [$player["nickname"], $player["total"], implode(", ", $gameScores)];
            $rows[] = $record;
        }

        return $rows;
    }
}
