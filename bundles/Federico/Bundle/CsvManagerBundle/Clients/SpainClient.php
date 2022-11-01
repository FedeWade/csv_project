<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use Exception;

class SpainClient implements ClientInterface
{
    private \SoapClient $client;

    public function __construct($wsdlPath)
    {
        $this->client = new \SoapClient($wsdlPath);
    }


    public function sendOrderRequest(array $orderArray)
    {
        $params = $this->generatePayload($orderArray);
        //var_dump($params);


        //$this->client->CreateSalesOrder(['request' => $params]);

        try {
            $this->client->CreateSalesOrder(['xMLDocRequest' => $params]);
            //echo "LAST REQUEST: " . PHP_EOL . $this->client->__getLastRequest() . PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo "REQUEST failed spain client:\n" . $this->client->__getLastRequest() . "\n";
        }
    }


    public function generatePayload(array $orderArray)
    {
        $params = new \stdClass();
        $params->Confirmed = '';
        $params->ExternalDocNo = '';
        $params->CustomerNo = '';
        $params->ShipTo = '';
        $params->OrderDate = '';
        $params->ShipMethodCode = '';
        $params->ShipAgentCode = '';
        $params->Note = '';


        $params->SalesLines = new \stdClass();
        $params->SalesLines->SalesLine = [];
        for($i =0; $i<count($orderArray); $i++) {
            $line = new \stdClass();
            $line->ItemNo = '';
            $line->Quantity = '';
            $line->UnitPrice = '';
            $line->ComposedDiscount = '';

            $params->SalesLines->SalesLine[] = $line;
        }
        return new \SoapVar($params, SOAP_ENC_OBJECT, 'SalesHeader', "urn:microsoft-dynamics-nav/xmlports/x50025", 'SalesHeader', "urn:microsoft-dynamics-nav/xmlports/x50025");
    }


}