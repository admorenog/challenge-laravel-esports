<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GameFolderNotFoundException extends Exception
{
    const ERROR_FOLDER_NOT_FOUND = "errors.game.folder_not_found";

    public function __construct($folder, $code = 0, Throwable $previous = null)
    {
        $message = trans(self::ERROR_FOLDER_NOT_FOUND, ['folder' => $folder]);
        parent::__construct($message, $code, $previous);
    }
}
