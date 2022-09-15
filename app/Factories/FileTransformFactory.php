<?php

namespace App\Factories;

use App\Interfaces\FileTransform;
use App\Services\CsvToJson;
use App\Services\JsonToCsv;

/**
 * This class helps to produce a proper strategy object for handling a payment.
 */
class FileTransformFactory
{
    /**
     * Get a payment method by its ID.
     *
     * @param $id
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