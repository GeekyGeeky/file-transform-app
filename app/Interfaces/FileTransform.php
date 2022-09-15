<?php

namespace App\Interfaces;


interface FileTransform
{
    public function convert(string $filepath, string $sortBy = ''): string;
    public function getTypeAndExtension(): array;
}
