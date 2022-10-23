<?php

namespace Federico\Bundle\CsvManagerBundle\Manager;

use Federico\Bundle\CsvManagerBundle\Manager\CsvEncoderFactory\CsvEncoderFactory;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class CsvManager
{
    /**
     * @var CsvEncoder
     */
    private CsvEncoder $csvEncoder;

    /**
     * @param CsvEncoderFactory $csvEncoderFactory
     */
    public function __construct(CsvEncoderFactory $csvEncoderFactory)
    {
        $this->csvEncoder = $csvEncoderFactory->createCsvEncoder();
    }

    /**
     * @param string $path
     * @return array
     */
    public function getArrayFromCsv(string $path): false|array
    {
        if (file_exists($path)) {
            $inputContent = file_get_contents($path);
            return $this->csvEncoder->decode($inputContent, 'csv', ['csv_key_separator'=> '^']);
        } else {
            return false;
        }
    }
}