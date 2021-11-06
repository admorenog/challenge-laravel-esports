<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameNotRegisteredException extends Exception
{
    const ERROR_GAME_NOT_FOUND = "Cannot find %s game, maybe its not registered.";

    public function __construct($gameName, $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_GAME_NOT_FOUND, $gameName);
        parent::__construct($message, $code, $previous);
    }
}
