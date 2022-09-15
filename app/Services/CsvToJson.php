<?php

namespace App\Services;

use App\Interfaces\FileTransform;

/**
 * This Strategy provides the service to convert a CSV file to JSON
 */
class CsvToJson implements FileTransform
{

    /**
     * @return array<string,string>
     */
    public function getTypeAndExtension(): array
    {
        return ['type' => 'application/json', 'extension' => 'json'];
    }


    public function convert(string $filepath, string $sortBy = ''): string
    {

        $data = $this->parseData($filepath);
        $keys = str_getcsv(array_shift($data));
        $splitKeys = array_map(function ($key) {
            return explode('_', $key);
        }, $keys);

        $mapData = array_map(function ($line) use ($splitKeys) {
            return $this->getJsonObject($line, $splitKeys);
        }, $data);

        return json_encode($mapData[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // return '';
        // return file_put_contents($path, $this->convert());
    }
    /**
     * @param       $line
     * @param       $splitKeys
     * @param array $jsonObject
     *
     * @return array
     */
    private function getJsonObject($line, $splitKeys, array $jsonObject = []): array
    {
        $values = str_getcsv($line);
        for ($valueIndex = 0, $count = \count($values); $valueIndex < $count; $valueIndex++) {
            if ($values[$valueIndex] === '') {
                continue;
            }
            $this->setJsonValue($splitKeys[$valueIndex], 0, $jsonObject, $values[$valueIndex]);
        }
        return $jsonObject;
    }

    /**
     * @param $splitKey
     * @param $splitKeyIndex
     * @param $jsonObject
     * @param $value
     */
    private function setJsonValue($splitKey, $splitKeyIndex, &$jsonObject, $value): void
    {
        $keyPart = $splitKey[$splitKeyIndex];
        if (\count($splitKey) > $splitKeyIndex + 1) {
            if (!array_key_exists($keyPart, $jsonObject)) {
                $jsonObject[$keyPart] = [];
            }
            $this->setJsonValue($splitKey, $splitKeyIndex + 1, $jsonObject[$keyPart], $value);
        } else {
            $jsonObject[$keyPart] = $value;
        }
    }

    /**
     * @return array
     */
    private function parseData($filepath): array
    {
        $data = file_get_contents($filepath);
        $data = explode("\n", $data);
        if (end($data) === '') {
            array_pop($data);
        }
        return $data;
    }
}
