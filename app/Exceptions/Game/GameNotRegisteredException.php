<?php

namespace App\Exceptions\Game;

use Exception;
use Throwable;

class GameNotRegisteredException extends Exception
{
    const ERROR_GAME_NOT_FOUND = "errors.game.game_not_found";

    public function __construct($gameName, $code = 0, Throwable $previous = null)
    {
        $message = trans(self::ERROR_GAME_NOT_FOUND, ['game' => $gameName]);
        parent::__construct($message, $code, $previous);
    }
}
