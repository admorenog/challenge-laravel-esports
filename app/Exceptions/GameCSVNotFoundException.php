<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameCSVNotFoundException extends Exception
{
    const ERROR_NO_CSV_FOUNDS = "Cannot find csv files in the folder %s";

    public function __construct($folder = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_NO_CSV_FOUNDS, $folder);
        parent::__construct($message, $code, $previous);
    }
}
