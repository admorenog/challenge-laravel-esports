<?php

namespace App\Exceptions\Game;

use Exception;
use Throwable;

class GameDuplicatedNickException extends Exception
{
    const ERROR_NICK_DUPLICATED = "errors.game.duplicated_nick";

    public function __construct($nickName, $file, $code = 0, Throwable $previous = null)
    {
        $message = trans(self::ERROR_NICK_DUPLICATED, ['nick' => $nickName, 'file' => $file]);
        parent::__construct($message, $code, $previous);
    }
}
