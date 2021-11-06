<?php

namespace App\Exceptions\Game;

use Exception;
use Throwable;

class GameFieldsMismatchException extends Exception
{
    const ERROR_FIELDS_MISMATCH = "errors.game.fields_mismatch";

    public function __construct($gameName, $fieldNames, $fields, $code = 0, Throwable $previous = null)
    {
        $message = trans(self::ERROR_FIELDS_MISMATCH, [
            'game' => $gameName,
            'count_fieldNames' => count($fieldNames),
            'count_fields' => count($fields),
            'fieldNames' => json_encode($fieldNames),
            'fields' => json_encode($fields)
        ]);

        parent::__construct($message, $code, $previous);
    }
}
