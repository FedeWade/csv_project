<?php

namespace Federico\Bundle\CsvManagerBundle\Commands;

use Federico\Bundle\CsvManagerBundle\Manager\CsvManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:print-csv',description: 'Print Csv file content')]
class PrintCsvCommand extends Command
{
    /**
     * @var CsvManager
     */
    private CsvManager $csvManager;

    /**
     * @param CsvManager $csvManager
     */
    public function __construct(CsvManager $csvManager)
    {
        $this->csvManager = $csvManager;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'Enter the path to the file you want to read');
        $this->setHelp('This command allows you to print content of Csv file...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csvArray = $this->csvManager->getArrayFromCsv($input->getArgument('path'));

        if(!$csvArray) {
            $output->writeln("ERROR! No file found in: ".$input->getArgument('path') );
            return Command::FAILURE;
        }

        $output->writeln([
            '',
            'Printing CSV file:',
            '======================',
        ]);

        foreach ($csvArray as $line) {
            foreach ($line as $value) {
                $output->write("|  ". $value . "  |");
            }
            $output->writeln("");
        }
        return Command::SUCCESS;
    }

}