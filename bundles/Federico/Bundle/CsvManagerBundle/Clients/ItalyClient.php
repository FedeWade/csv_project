<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ItalyClient implements ClientInterface
{
    private array $recurringKeys;

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
        $response = $this->client->request('POST', $this->orderRequestURL);
        $response = json_decode($response->getContent(false), true);
        var_dump($response);
        //echo $response . PHP_EOL;
    }

    /**
     * @param $orderArray
     * @return string
     */
    public function generateJsonPayload($orderArray): string
    {
        $firstLine = $orderArray[0];

        $payload = array();
        foreach ($firstLine as $key => $value) {
            if ($key == "LineNo") break;
            $payload[$key] = $value;
        }

        $SalesOrderLines = array();
        foreach ($orderArray as $line) {
            $orderLine = array();
            foreach ($line as $key => $value) {
                if (in_array($key, $this->recurringKeys)) {
                    $orderLine[$key] = $value;
                }
            }
            $SalesOrderLines[] = $orderLine;
        }

        $payload["salesOrderLines"] = $SalesOrderLines;
        //unset($payload["ReferenceOffice"]);
        $payload = json_encode($payload);

        //echo PHP_EOL;
        //echo $payload . PHP_EOL . PHP_EOL;
        return $payload;
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
