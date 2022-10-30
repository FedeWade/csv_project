<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

interface ClientInterface
{
    public function sendOrderRequest(array $orderArray);
}