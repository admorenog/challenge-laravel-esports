<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameEmptyRecordException extends Exception
{
    const ERROR_EMPTY_RECORD = "In the file %s, the record %s is empty.";

    public function __construct($filePath, $emptyRecords, $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_EMPTY_RECORD, $filePath, array_key_first($emptyRecords));
        parent::__construct($message, $code, $previous);
    }
}
