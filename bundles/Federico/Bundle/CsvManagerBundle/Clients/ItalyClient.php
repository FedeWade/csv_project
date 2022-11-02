<?php

namespace Federico\Bundle\CsvManagerBundle\Clients;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ItalyClient implements ClientInterface
{
    private array $floatKeys;
    private array $tokenRequestBody;
    private string $tokenRequestURL;
    private string $orderRequestURL;

    private HttpClientInterface $client;

    /**
     * @param $tokenRequestBody
     * @param HttpClientInterface $client
     */
    public function __construct($floatKeys, $tokenRequestBody, $orderRequestURL, $tokenRequestURL, HttpClientInterface $client)
    {
        $this->floatKeys = $floatKeys;
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
        unset($payload["ReferenceOffice"]);
        $payload =array_splice($payload,0, -8);

        foreach ($orderArray as &$row) {
            array_splice($row, 0, -9 );
        }

        array_walk_recursive($orderArray, function(&$value, $key) {
            if (in_array($key, $this->floatKeys))
                $value = (float)$value;
        });
        $payload["salesOrderLines"] =$orderArray;
        $payload['AmountIncludingVAT'] = (float)$payload['AmountIncludingVAT'];
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
