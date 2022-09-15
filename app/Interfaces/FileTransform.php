<?php

namespace App\Interfaces;


interface FileTransform
{
    public function convert(string $filepath): string;
    public function getTypeAndExtension(): array;
}
