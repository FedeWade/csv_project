<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use Exception;

class GermanyClient implements ClientInterface
{

    private \SoapClient $client;

    private array $testCredentials;

    private string $headerNamespace;

    private string $bodyNamespace;

    /**
     * @param $wsdlPath
     * @param $testCredentials
     * @param $headerNamespace
     * @param $bodyNamespace
     * @throws \SoapFault
     */
    public function __construct($wsdlPath, $testCredentials, $headerNamespace, $bodyNamespace)
    {
        $this->client = new \SoapClient($wsdlPath, array('trace' => 1));
        $this->testCredentials= $testCredentials;
        $this->headerNamespace = $headerNamespace;
        $this->bodyNamespace = $bodyNamespace;
    }

    /**
     * @param array $orderArray
     * @return void
     */
    public function sendOrderRequest(array $orderArray): void
    {
        $header = (object)array(
            'DatasourceID' => $this->testCredentials['test_data_source_id'],
            'MandantID' => $this->testCredentials['test_mandant_id'],
            'Password' => $this->testCredentials['test_password'],
            'UserID' => $this->testCredentials['test_user']
        );
        $header = new \SoapHeader($this->headerNamespace, 'Context', $header);
        $this->client->__setSoapHeaders($header);

        $params = $this->generatePayload($orderArray);
        try {
            $response = $this->client->CreateSalesOrder($params);
            echo PHP_EOL." - Request to Germany client SUCCESS.". PHP_EOL;
            var_dump($response);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo "REQUEST failed germany client:\n" . $this->client->__getLastRequest() . "\n";
            //echo $response['error']['message'];
        }
    }

    /**
     * @param $orderArray
     * @return \SoapVar
     */
    public function generatePayload($orderArray): array
    {
        $params = new \stdClass();
        $params->Email = $this->testCredentials['test_email'];
        $params->Password = $this->testCredentials['test_email_password'];
        $params->AdditionalNotice = '';

        $params->DeliveryAddress = new \stdClass();
        $params->DeliveryAddress->AuxiliaryText = '';
        $params->DeliveryAddress->City = $orderArray[0]['ShipToCity'];
        $params->DeliveryAddress->Country = $orderArray[0]['ShipToCountryRegionCode'];
        $params->DeliveryAddress->Name1 = $orderArray[0]['BillToName'];
        $params->DeliveryAddress->Name2 = $orderArray[0]['BillToName2'];
        $params->DeliveryAddress->Street = $orderArray[0]['ShipToAddress'];
        $params->DeliveryAddress->ZIP = $orderArray[0]['ShipToPostCode'];

        $params->DeliveryDate = '2022-12-10T00:00:00';
        $params->DeliveryType = 'Regular';

        $params->OrderPositionItems = new \stdClass();
        $params->OrderPositionItems->OrderPosition = [];
        for($i =0; $i<count($orderArray); $i++) {
            $line = new \stdClass();
            $line->AdditionalNotice = '';
            $line->Amount = $orderArray[$i]['Amount'];
            $line->ArticleItemID = $orderArray[$i]['ItemNo'];
            $params->OrderPositionItems->OrderPosition[] = $line;
        }
        $params->Reference = 'Frig Air Deutschland GmbH';
        $params->ReferenceNumber = '10001';

        $params= new \SoapVar($params, SOAP_ENC_OBJECT, 'OrderRequest', $this->bodyNamespace, 'request', $this->bodyNamespace);
        return ['request' => $params];
    }
}
