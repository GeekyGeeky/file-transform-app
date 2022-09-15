<?php

namespace App\Interfaces;

/** 
 * The base interface for the file transform strategy
*/
interface FileTransform
{
    public function convert(string $filepath, string $sortBy = ''): string;
    public function getTypeAndExtension(): array;
}
