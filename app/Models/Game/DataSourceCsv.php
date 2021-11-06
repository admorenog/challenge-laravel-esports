<?php

namespace App\Models\Game;

use App\Exceptions\Game\GameCSVNotFoundException;
use App\Exceptions\Game\GameEmptyRecordException;
use App\Exceptions\Game\GameFolderNotFoundException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class DataSourceCsv {

    const FILE_CSV_EOL = PHP_EOL;
    const FILE_CVS_SEPARATOR = ";";
    const FILE_CSV_EXTENSION = '/\.csv$/i';

    /**
     * Returns all CSV files in the requested folder
     * @param string $folder
     * @return string[]
     * @throws GameCSVNotFoundException
     * @throws GameFolderNotFoundException
     */
    public static function getFilePaths(string $folder): array {
        if(!Storage::disk('local')->exists($folder)) {
            throw new GameFolderNotFoundException($folder);
        }

        $filePaths = Storage::disk('local')->files($folder);

        $filePaths = preg_grep(self::FILE_CSV_EXTENSION, $filePaths);

        if(count($filePaths) == 0) {
            throw new GameCSVNotFoundException($folder);
        }
        return $filePaths;
    }

    /**
     * Returns the records split by EOL and CSV separator (;)
     * @param string $filePath
     * @return array
     * @throws GameEmptyRecordException
     * @throws FileNotFoundException
     */
    public static function getRecords(string $filePath): array {
        $content = Storage::disk('local')->get($filePath);
        $rawRecords = explode(self::FILE_CSV_EOL, rtrim($content));
        $emptyRecords = array_filter($rawRecords, function($rawRecord) {
            return $rawRecord == "";
        });
        if(count($emptyRecords) > 0) {
            throw new GameEmptyRecordException($filePath, $emptyRecords);
        }
        $records = array_map(function($rawRecord) {
            return explode(self::FILE_CVS_SEPARATOR, $rawRecord);
        }, $rawRecords);
        return $records;
    }
}
