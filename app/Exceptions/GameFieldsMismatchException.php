<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameFieldsMismatchException extends Exception
{
    const ERROR_FIELDS_MISMATCH = "Processing %s file there was an error\n a record doesn't have the required fields: the game has %s field names and the record has %s fields \n%s\n%s";

    public function __construct($gameName, $fieldNames, $record, $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_FIELDS_MISMATCH,
            $gameName, count($fieldNames), count($record), json_encode($fieldNames), json_encode($record)
        );
        parent::__construct($message, $code, $previous);
    }
}
