<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpainClient implements ClientInterface
{
    private \SoapClient $client;

    public function __construct()
    {
        $this->client = new \SoapClient('C:\Users\federico.ballarin\Desktop\project_utils\wsprod_de.wsdl');
    }


    public function sendOrderRequest(array $orderArray) {

        $client = new \SoapClient('C:\Users\federico.ballarin\Desktop\project_utils\wsprod_de.wsdl');
        $functions=$client->__getFunctions();
        //var_dump($functions);
        //$client->__soapCall("CreateSalesOrder", array(), );

    }



}