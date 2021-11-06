<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameCSVNotFoundException extends Exception
{
    const ERROR_NO_CSV_FOUNDS = "errors.game.csv_not_found";

    public function __construct($folder = "", $code = 0, Throwable $previous = null)
    {
        $message = trans(self::ERROR_NO_CSV_FOUNDS, ['folder' => $folder]);
        parent::__construct($message, $code, $previous);
    }
}
