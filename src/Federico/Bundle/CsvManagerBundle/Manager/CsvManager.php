<?php

namespace App\Federico\Bundle\CsvManagerBundle\Manager;


use Symfony\Component\Serializer\Encoder\CsvEncoder;

class CsvManager
{
    /**
     * @var CsvEncoder
     */
    private CsvEncoder $csvEncoder;


    public function __construct()
    {
        $this->csvEncoder = new CsvEncoder();
    }

    /**
     * @param string $path
     * @return array
     */
    public function getArrayFromCsv(string $path): array
    {
        if (file_exists($path)) {
            $inputContent = file_get_contents($path);
            return $this->csvEncoder->decode($inputContent, 'csv', ['csv_key_separator'=> '@']);
        } else {
            return array();
        }
    }
}