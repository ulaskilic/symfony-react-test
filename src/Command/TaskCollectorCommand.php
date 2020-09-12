<?php

namespace App\Command;

use App\Services\Task\DeveloperTaskServiceFactory;
use App\Services\Task\Provider2Adapter;
use App\Services\Task\TaskService;
use App\Services\Task\TaskServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TaskCollectorCommand extends BaseCommand
{
    protected static $defaultName = 'task:collect';

    /**
     * Supported provider list
     */
    private const SUPPORTED_PROVIDERS = [
        'mock1' => [
            'defaultUri' => 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa',
            'service' => TaskService::class
        ],
        'mock2' => [
            'defaultUri' => 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7',
            'service' => Provider2Adapter::class
        ]
    ];

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setDescription('Collect jobs from providers')
            ->addOption('provider', null, InputOption::VALUE_OPTIONAL, 'Select provider: mock1, mock2')
            ->addOption('uri', null, InputOption::VALUE_OPTIONAL, 'Provider URL')
            ->addOption('all', null, InputOption::VALUE_OPTIONAL, 'Collect from all providers (It will proceed with default URLs)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->input = $input;
        $this->output = $output;

        $selectedProvider = $input->getOption('provider');
        if(!empty($selectedProvider)) {
            if(!in_array($selectedProvider, array_keys(self::SUPPORTED_PROVIDERS))) {
                $this->error('Provider is not supported!');
                return 1;
            }
            $uri = self::SUPPORTED_PROVIDERS[$selectedProvider]['defaultUri'];
            if(!empty($input->getOption('uri'))) {
                $uri = $input->getOption('uri');
            }

            $class = self::SUPPORTED_PROVIDERS[$selectedProvider]['service'];
            /**
             * @var $provider TaskServiceInterface
             */
            $provider = new $class($uri);
            $output->write(print_r($provider, true));

            $output->write(print_r($provider->getTasks(), true));
        }

        return 0;
    }
}
