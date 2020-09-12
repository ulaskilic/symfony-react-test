<?php


namespace App\Command;

use App\Services\Task\DeveloperTaskServiceFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class BaseCommand extends Command
{
    protected InputInterface $input;
    protected OutputInterface $output;

    protected function info($msg)
    {
        $this->output->writeln($msg);
    }

    protected function error($msg)
    {
        $this->output->writeln("<error>$msg</error>");
    }

    protected function warning($msg)
    {
        $this->output->writeln("<comment>$msg</comment>");
    }

    protected function success($msg)
    {
        $this->output->writeln("<info>$msg</info>");
    }

}