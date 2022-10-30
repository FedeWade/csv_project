<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;


use Exception;

class GermanyClient implements ClientInterface
{

    private \SoapClient $client;

    public function __construct()
    {
        $this->client = new \SoapClient('C:\Users\federico.ballarin\Desktop\project_utils\wsprod_de.wsdl', array('trace' => 1));
    }


    public function sendOrderRequest(array $orderArray)
    {
        $header = (Object)array(

                'DatasourceID' => 'FrigAirCSTest',
                'MandantID' => '1',
                'Password' => 'svc2018',
                'UserID' => 'WebshopSvc'
        );

        $header= new \SoapHeader('lalloooo','Context', $header);
        $this->client->__setSoapHeaders($header);
        $params = array(
            'Email' => 'info@kuehler-peukert.de',
            'Password' => '10001',
            'AdditionalNotice' => 'test',
/*
            'DeliveryAddress' => [
                'AuxiliaryText' => '10001',
                'City' => '10001',
                'Country' => '10001',
                'Name1' => '10001',
                'Name2' => '10001',
                'Street' => '10001',
                'ZIP' => '10001',
            ],

            'DeliveryDate' => '10001',
            'DeliveryType' => '10001',
/*
            'OrderPositionItems' => [//ci vanno le linee ordine
               // 'OrderPosition' => [],
               // 'OrderPosition' => [],

            ],*/


            'Reference' => '10001',
            'ReferenceNumber' => '10001'

        );

        $parameters= array("request"=>$params);

        try {
            $this->client->__soapCall("CreateSalesOrder", $params);

        } catch (Exception $e) {
            //echo $response->getMessage();
            echo "REQUEST:\n" . $this->client->__getLastRequest() . "\n";

        }

        //echo $response;

    }

}
