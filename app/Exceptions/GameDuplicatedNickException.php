<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameDuplicatedNickException extends Exception
{
    const ERROR_NICK_DUPLICATED = "The %s nickname is not unique in %s file";

    public function __construct($nickName, $file, $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_NICK_DUPLICATED, $nickName, $file);
        parent::__construct($message, $code, $previous);
    }
}
