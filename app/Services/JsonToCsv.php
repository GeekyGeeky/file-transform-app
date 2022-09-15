<?php

namespace App\Services;

use App\Interfaces\FileTransform;

/**
 * This Strategy provides the service to convert a JSON file to CSV
 */
class JsonToCsv implements FileTransform
{

    public function getTypeAndExtension(): array
    {
        return ['type' => 'text/csv', 'extension' => 'csv'];
    }

    public function convert(string $filepath): string
    {

        $flattened = array_map(function ($d) {
            if (is_array($d))
                return $this->flatten($d);
            else return [$d];
        }, json_decode(file_get_contents($filepath), true));
        // create an array with all of the keys where each has a null value
        $default = $this->getArrayOfNulls($flattened);
        // merge default with the actual data so that non existent keys will have null values
        return $this->toCsvString(array_map(function ($d) use ($default) {
            return array_merge($default, $d);
        }, $flattened));
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function toCsvString(array $data): string
    {
        $f = fopen('php://temp', 'wb');
        fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($f, array_keys(current($data)));
        array_walk($data, function ($row) use (&$f) {
            fputcsv($f, $row);
        });
        rewind($f);
        $csv = stream_get_contents($f);
        fclose($f);
        return !\is_bool($csv) ? $csv : '';
    }

    /**
     * @param array  $array
     * @param string $prefix
     * @param array  $result
     *
     * @return array
     */
    protected function flatten(array $array = [], $prefix = '', array $result = []): array
    {
        // if (\is_array($value)) {}
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $prefix . $key . '_'));
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $flattened
     *
     * @return array
     */
    protected function getArrayOfNulls($flattened): array
    {
        $flattened = array_values($flattened);
        $keys = array_keys(array_merge(...$flattened));
        return array_fill_keys($keys, null);
    }
}
