<?php

namespace App\Command;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Resource\Task\TaskResource;
use App\Services\Task\DeveloperTaskServiceFactory;
use App\Services\Task\Provider2Adapter;
use App\Services\Task\TaskService;
use App\Services\Task\TaskServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TaskCollectorCommand extends BaseCommand
{
    protected static $defaultName = 'task:collect';

    /**
     * Symfony container
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

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

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setDescription('Collect jobs from providers')
            ->addOption('provider', null, InputOption::VALUE_OPTIONAL, 'Select provider: mock1, mock2')
            ->addOption('uri', null, InputOption::VALUE_OPTIONAL, 'Provider URL')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Collect from all providers (It will proceed with default URLs)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->input = $input;
        $this->output = $output;

        $selectedProvider = $input->getOption('provider');
        if(!empty($selectedProvider)) {
            $this->info('Data collecting from provider ' . $selectedProvider);
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

            foreach ($provider->getTasks() as $taskResource) {
                $this->saveEntity($taskResource);
            }
            $this->success('Completed!');
            return 0;
        }

        if($input->getOption('all') !== false) {
            $this->info('Data collecting all providers');
            foreach (self::SUPPORTED_PROVIDERS as $supportedProvider) {
                /**
                 * @var $provider TaskServiceInterface
                 */
                $provider = new $supportedProvider['service']($supportedProvider['defaultUri']);

                foreach ($provider->getTasks() as $taskResource) {
                    $this->saveEntity($taskResource);
                }
            }
            $this->success('Completed!');
            return 0;
        }

        $this->error('You need to provide one option that --all or --provider=mock1 or mock2');
        return 1;
    }

    /**
     * Cleanup tasks
     * TODO: overwrite tasks instead of cleaning up all
     *
     * @param $provider
     */
    private function cleanUp($provider)
    {
        /**
         * @var $repository TaskRepository
         */
        $repository = $this->container->get('doctrine')->getRepository(Task::class);

        $repository->deleteTasksByProvider($provider);
    }

    /**
     * @param TaskResource $task
     */
    private function saveEntity(TaskResource $task)
    {
        /**
         * @var $entityManager EntityManagerInterface
         */
        $entityManager = $this->container->get('doctrine')->getManager();

        /**
         * @var $repository TaskRepository
         */
        $repository = $entityManager->getRepository(Task::class);

        $taskEntity = new Task();

        $existsTask = $repository->findOneBy(['identifier' => $task->id, 'provider' => $task->provider]);
        if($existsTask) {
            $taskEntity = $existsTask;
        }

        $taskEntity->setIdentifier($task->id);
        $taskEntity->setComplexity($task->complexity);
        $taskEntity->setEstimation($task->estimation);
        $taskEntity->setProvider($task->provider);
        $entityManager->persist($taskEntity);
        $entityManager->flush();
    }
}
