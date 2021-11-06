<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameFolderNotFoundException extends Exception
{

    const ERROR_FOLDER_NOT_FOUND = "Cannot find the folder %s";
    public function __construct($folder = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf(self::ERROR_FOLDER_NOT_FOUND, $folder);
        parent::__construct($message, $code, $previous);
    }
}
