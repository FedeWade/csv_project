<?php

namespace Federico\Bundle\CsvManagerBundle\Manager;

use Symfony\Component\Serializer\SerializerInterface;

class CsvManager
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param string $path
     * @return array
     */
    public function getArrayFromCsv(string $path): false|array
    {
        if (file_exists($path)) {
            return $this->serializer->decode(file_get_contents($path), 'csv');
        } else {
            return false;
        }
    }
}