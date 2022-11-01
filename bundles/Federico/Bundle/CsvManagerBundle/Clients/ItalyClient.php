<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use MongoDB\Driver\Exception\ServerException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ItalyClient implements ClientInterface
{
    private array $recurringKeys;
    private array $integerKeys;
    private array $tokenRequestBody;
    private string $tokenRequestURL;
    private string $orderRequestURL;

    private HttpClientInterface $client;

    /**
     * @param $recurringKeys
     * @param $tokenRequestBody
     * @param HttpClientInterface $client
     */
    public function __construct($recurringKeys, $tokenRequestBody, $orderRequestURL, $tokenRequestURL, HttpClientInterface $client)
    {
        $this->recurringKeys = $recurringKeys;
        $this->integerKeys = ["AmountIncludingVAT", "Quantity", "UnitPrice", "Amount", "VATPerc"];
        $this->tokenRequestBody = $tokenRequestBody;
        $this->tokenRequestURL = $tokenRequestURL;
        $this->orderRequestURL = $orderRequestURL;
        $this->client = $client;
    }

    /**
     * @param array $orderArray
     * @return void
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function sendOrderRequest(array $orderArray)
    {
        $payload = $this->generateJsonPayload($orderArray);

        $this->client = $this->client->withOptions([
                'body' => $payload,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'Content-Type' => 'application/json'
                ]]
        );
        try {
            $response = $this->client->request('POST', $this->orderRequestURL);
            $response->getContent();
            echo PHP_EOL." - Request to Italy client SUCCESS.". PHP_EOL;
        } catch (\Exception $e) {
            $response = json_decode($response->getContent(false), true);
            echo PHP_EOL." - Request to Italy client FAILED. ERROR: ". PHP_EOL;
            echo $response['error']['message'];
        }
    }

    /**
     * @param $orderArray
     * @return string
     */
    public function generateJsonPayload($orderArray): string
    {
        $payload = $orderArray[0];
        $payload =array_splice($payload,0, -9);

        var_dump($payload);
        /*
        $firstLine = $orderArray[0];


        $payload = array();
        foreach ($firstLine as $key => $value) {
            if ($key == 'ReferenceOffice') continue;
            if ($key == "LineNo") break;
            $payload[$key] = $value;
        }

        foreach ($orderArray as $line) {
            $orderLine = array();
            foreach ($line as $key => $value) {
                if (in_array($key, $this->integerKeys))
                    $orderLine[$key] = (int)$value;
                elseif (in_array($key, $this->recurringKeys)) {
                    $orderLine[$key] = $value;
                }
            }
            $SalesOrderLines[] = $orderLine;
        }
        $payload["salesOrderLines"] = $SalesOrderLines;
        $payload['AmountIncludingVAT'] = (int)$payload['AmountIncludingVAT'];
*/
        return json_encode($payload);
    }

    /**
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getToken(): string
    {
        $this->client = $this->client->withOptions($this->tokenRequestBody);
        $response = $this->client->request('POST', $this->tokenRequestURL);
        $response = json_decode($response->getContent(), true);
        return $response["access_token"];
    }
}
