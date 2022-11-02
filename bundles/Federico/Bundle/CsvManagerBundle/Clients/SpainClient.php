<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use Exception;

class SpainClient implements ClientInterface
{
    private \SoapClient $client;

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
            echo PHP_EOL." - Request to Germany client SUCCESS.". PHP_EOL;
            var_dump($response);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo "REQUEST failed spain client:\n". "ERROR:". PHP_EOL . $this->client->__getLastRequest() . "\n";
        }
    }

    /**
     * @param array $orderArray
     * @return \SoapVar[][]
     */
    public function generatePayload(array $orderArray)
    {
            $params = new \stdClass();
            $params->Confirmed = '';
            $params->ExternalDocNo = $orderArray[0]['No'];
            $params->CustomerNo = $orderArray[0]['SellToCustomerNo'];
            $params->ShipTo = $orderArray[0]['ShipToAddress'];
            $params->OrderDate = $orderArray[0]['ShipmentDate'];
            $params->ShipMethodCode = '';
            $params->ShipAgentCode = '';
            $params->Note = '';

            $params->SalesLines = new \stdClass();
            $params->SalesLines->SalesLine = [];
            for($i =0; $i<count($orderArray); $i++) {
                $line = new \stdClass();
                $line->ItemNo = $orderArray[$i]['ItemNo'];
                $line->Quantity = $orderArray[$i]['Quantity'];
                $line->UnitPrice = $orderArray[$i]['UnitPrice'];
                $line->ComposedDiscount = '';

                $params->SalesLines->SalesLine[] = $line;
            }

        $body = new \SoapVar($params, SOAP_ENC_OBJECT, 'SalesHeader', "urn:microsoft-dynamics-nav/xmlports/x50025", null, "urn:microsoft-dynamics-nav/xmlports/x50025");
        return ['xMLDocRequest' => ['SalesHeader' => $body]];
    }

}







/*
        $params = [
            'Confirmed' => '',
            'ExternalDocNo' => '',
            'CustomerNo' => '',
            'ShipTo' => '',
            'OrderDate' => '',
            'ShipMethodCode' => '',
            'ShipAgentCode' => '',
            'Note' => '',
            'SalesLines' => new \stdClass()
        ];

        $params['SalesLines']->salesLine= [];

        for($i =0; $i<count($orderArray); $i++) {
            $line =[];
            $line['ItemNo'] = '';
            $line['Quantity'] = '';
            $line['UnitPrice'] = '';
            $line['ComposedDiscount'] = '';

            $params['SalesLines']->salesLine[]= $line;
        }
        var_dump($params);*/