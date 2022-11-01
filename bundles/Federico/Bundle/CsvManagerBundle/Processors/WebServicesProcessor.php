<?php

namespace Federico\Bundle\CsvManagerBundle\Processors;

use Federico\Bundle\CsvManagerBundle\Clients\ClientInterface;
use Federico\Bundle\CsvManagerBundle\Clients\GermanyClient;
use Federico\Bundle\CsvManagerBundle\Clients\ItalyClient;
use Federico\Bundle\CsvManagerBundle\Clients\SpainClient;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

class WebServicesProcessor implements ProcessorInterface
{

    private ClientInterface $italyClient;
    private ClientInterface $germanyClient;
    private ClientInterface $spainClient;

    private SerializerInterface $serializer;

    private array $csvOptionConfiguration;

    /**
     * @param SerializerInterface $serializer
     * @param ItalyClient $client
     */
    public function __construct($csvOptionConfiguration, SerializerInterface $serializer, ItalyClient $italyClient, GermanyClient $germanyClient, SpainClient $spainClient)
    {
        $this->csvOptionConfiguration = $csvOptionConfiguration;
        $this->italyClient = $italyClient;
        $this->germanyClient = $germanyClient;
        $this->spainClient = $spainClient;
        $this->serializer = $serializer;
    }

    /**
     * @param string $directoryPath
     * @param OutputInterface $output
     * @return int
     */
    public function executeCommand(string $directoryPath, OutputInterface $output): int
    {
        $filesInFolder = glob($directoryPath. "/*.csv");
        if (!$filesInFolder) {
            $output->writeln("No valid file found in the directory.");
            return 1; //command failed
        }


        foreach ($filesInFolder as $filePath) {
            $orderArray = $this->serializer->decode(file_get_contents($filePath), 'csv', $this->csvOptionConfiguration);
            if ($orderArray[0]["ReferenceOffice"]== "Italy")
                $this->italyClient->sendOrderRequest($orderArray);
            if ($orderArray[0]["ReferenceOffice"]== "Germany")
                $this->germanyClient->sendOrderRequest($orderArray);
            //if ($orderArray[0]["ReferenceOffice"]== "Spain")
                //$this->spainClient->sendOrderRequest($orderArray);
        }

        return 0; //command success
    }


}