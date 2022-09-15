<?php

namespace App\Factories;

use App\Interfaces\FileTransform;
use App\Services\CsvToJson;
use App\Services\JsonToCsv;

/**
 * This class helps to produce a proper strategy object for handling a file transformation
 * It determines the strategy to be called by the filetype passed to the method
 */
class FileTransformFactory
{
    /**
     * Get a transformation method by its filetype.
     *
     * @param string $filetype
     * @return FileTransform
     * @throws \Exception
     */
    public static function getConversionMethod(string $filetype): FileTransform
    {
        switch ($filetype) {
            case "json":
                return new JsonToCsv();
            case "csv":
                return new CsvToJson();
            default:
                throw new \Exception("Unknown File Transform Method");
        }
    }
}