<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\DBAL\DriverManager;

use \Doctrine\DBAL\Exception;

class CreateProductCommand extends Command
{
    protected static $defaultName = 'app:create-product';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('arg2', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        $arg2 = $input->getArgument('arg2');

        if ($arg1) {
            $io->note(sprintf('NAME: %s', $arg1));
        }
        else {
            $io->error('No name given.');
            return Command::FAILURE;
        }

        if ($arg2) {
            $io->note(sprintf('PRICE: %s', $arg2));
        }
        else {
            $io->error('No price given.');
            return Command::FAILURE;
        }

        require_once 'vendor/autoload.php';

        $connectionParams = array(
            'url' => "mysql://root:@localhost/db_name?serverVersion=5.7",
        );

        try {
            $sql = "INSERT INTO product (name, price) VALUES ('$arg1', $arg2)";

            $conn = DriverManager::getConnection($connectionParams);

            $stmt = $conn->query($sql);

            print("$sql \n \n");

            $io->success('You have created a new product.');
            return Command::SUCCESS;
        }
        catch (Exception $e) {
            $io->error('Could not create product.');
            return Command::FAILURE;
        }
    }
}
