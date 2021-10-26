<?php

namespace App\Models\Game;

use App\Exceptions\GameCSVNotFoundException;
use App\Exceptions\GameEmptyRecordException;
use App\Exceptions\GameFolderNotFoundException;
use Illuminate\Support\Facades\Storage;

class DataSourceCsv {

    const FILE_CSV_EOL = PHP_EOL;
    const FILE_CVS_SEPARATOR = ";";
    const FILE_CSV_EXTENSION = '/\.csv$/i';

    const ERROR_EMPTY_RECORD = "In the file %s, the record %s is empty.";
    const ERROR_FOLDER_NOT_FOUND = "Cannot find the folder %s";
    const ERROR_NO_CSV_FOUNDS = "Cannot find csv files in the folder %s";

    /**
     * Returns all CSV files in the requested folder
     * @param string $folder
     * @return array
     * @throws GameCSVNotFoundException
     * @throws GameFolderNotFoundException
     */
    public static function getFilePaths(string $folder): array {
        if(!Storage::disk('local')->exists($folder)) {
            throw new GameFolderNotFoundException(sprintf(self::ERROR_FOLDER_NOT_FOUND, $folder));
        }

        $filePaths = Storage::disk('local')->files($folder);

        $filePaths = preg_grep(self::FILE_CSV_EXTENSION, $filePaths);

        if(count($filePaths) == 0) {
            throw new GameCSVNotFoundException(sprintf(self::ERROR_NO_CSV_FOUNDS, $folder));
        }
        return $filePaths;
    }

    /**
     * Returns the records splitted by EOL and CSV separator (;)
     * @param string $filePath
     * @return array
     * @throws GameEmptyRecordException
     */
    public static function getRecords(string $filePath): array {
        $content = Storage::disk('local')->get($filePath);
        $rawRecords = explode(self::FILE_CSV_EOL, rtrim($content));
        $emptyRecords = array_filter($rawRecords, function($rawRecord) {
            return $rawRecord == "";
        });
        if(count($emptyRecords) > 0) {
            throw new GameEmptyRecordException(sprintf(self::ERROR_EMPTY_RECORD, $filePath, array_key_first($emptyRecords)));
        }
        $records = array_map(function($rawRecord) {
            return explode(self::FILE_CVS_SEPARATOR, $rawRecord);
        }, $rawRecords);
        return $records;
    }
}
