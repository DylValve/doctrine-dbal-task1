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

class ShowProductCommand extends Command
{
    protected static $defaultName = 'app:show-product';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('NAME: %s', $arg1));
        }
        else {
            $io->error('No name given.');
            return Command::FAILURE;
        }

        require_once 'vendor/autoload.php';

        $connectionParams = array(
            'url' => "mysql://root:@localhost/db_name?serverVersion=5.7",
        );

        try {
            $sql = "SELECT * FROM product WHERE name='$arg1'";;

            $conn = DriverManager::getConnection($connectionParams);

            $stmt = $conn->query($sql);

            print("$sql \n \n");

            while (($row = $stmt->fetchAssociative()) !== false) {
                print($row['id'] . ' - ' . $row['name'] . ' - ' . $row['price'] . "\n \n");
            }

            $io->success('Product found.');
            return Command::SUCCESS;
        }
        catch (Exception $e) {
            $io->error('Product not dound.');
            return Command::FAILURE;
        }
    }
}
