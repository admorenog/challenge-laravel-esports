<?php

namespace App\Exceptions\Game;

use Exception;
use Throwable;

class GameEmptyRecordException extends Exception
{
    const ERROR_EMPTY_RECORD = "errors.game.empty_record";

    public function __construct($filePath, $emptyRecords, $code = 0, Throwable $previous = null)
    {
        $index = array_key_first($emptyRecords);
        $message = trans(self::ERROR_EMPTY_RECORD, ['file' => $filePath, 'index' => $index]);
        parent::__construct($message, $code, $previous);
    }
}
