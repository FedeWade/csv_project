<?php

namespace Federico\Bundle\CsvManagerBundle\Manager\CsvEncoderFactory;

use Symfony\Component\Serializer\Encoder\CsvEncoder;

class CsvEncoderFactory
{
    /**
     * @return CsvEncoder
     */
    public static function createCsvEncoder(): CsvEncoder
    {
        return new CsvEncoder();
    }

}