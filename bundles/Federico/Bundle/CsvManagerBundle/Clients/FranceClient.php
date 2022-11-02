<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use Exception;

class FranceClient implements ClientInterface
{
    private \SoapClient $client;

    /**
     * @param $wsdlPath
     * @throws \SoapFault
     */
    public function __construct($wsdlPath)
    {
        $this->client = new \SoapClient($wsdlPath, ['trace' => 1]);
    }

    /**
     * @param array $orderArray
     * @return void
     */
    public function sendOrderRequest(array $orderArray)
    {
        $params = $this->generatePayload($orderArray);

        try {
            $response = $this->client->CreateSalesOrder($params);
            echo PHP_EOL." - Request to France client SUCCESS.". PHP_EOL;
            var_dump($response);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo "REQUEST failed France client:\n". "ERROR:". PHP_EOL . $this->client->__getLastRequest() . "\n";
        }
    }

    /**
     * @param $orderArray
     * @return array
     */
    public function generatePayload($orderArray): array
    {
        return [];
    }

}