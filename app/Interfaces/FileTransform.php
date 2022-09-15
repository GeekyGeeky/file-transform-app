<?php

namespace App\Interfaces;


interface FileTransform
{


    // public function __construct($filepath)
    // {
    //     $this->data =  file_get_contents($filepath);
    // }

    public function convert(string $filepath): string;
    public function getTypeAndExtension(): array;
}
