<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Factories\FileTransformFactory;


class FileTransformTest extends TestCase
{
    /**
     * unit test for transforming csv file to json
     *
     * @return void
     */
    public function test_csv_to_json()
    {
        $transformMethod = FileTransformFactory::getConversionMethod('csv');
        $typeAndExtension =  $transformMethod->getTypeAndExtension();
        $this->assertTrue(is_string($transformMethod->convert(__DIR__ . DIRECTORY_SEPARATOR . '_1.csv')));
        $this->assertContains('json', $typeAndExtension);
    }
       
    /**
     * unit test for transforming json file to csv
     *
     * @return void
     */

    public function test_json_to_csv()
    {
        $transformMethod = FileTransformFactory::getConversionMethod('json');
        $typeAndExtension =  $transformMethod->getTypeAndExtension();
        $this->assertTrue(is_string($transformMethod->convert(__DIR__ . DIRECTORY_SEPARATOR . '_1.json')));
        $this->assertContains('csv', $typeAndExtension);
    } 
}
