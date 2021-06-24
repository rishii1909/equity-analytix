<?php
declare(strict_types = 1);

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\RedisService;
use App\UseCases\Message\Create\Handler\PrivateMessageHandler;
use App\UseCases\News\Create\Handler as NewsCommandHandler;
use App\UseCases\Message\Create\PrivateMessageHandler as MessageCommandHandler;
use App\Websocket\ChatHandler;
use Ratchet\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class WebSocketServerCommand
 */
class WebSocketServerCommand extends Command
{
	/** @var string*/
    protected static $defaultName = 'run:websocket-server';

    /**@var RedisService */
    private $redisService;
    /** @var UserRepository */
    private $userRepository;
    /** @var SerializerInterface */
    private $serializer;
    /** @var ValidatorInterface */
    private $validator;
    /** @var NewsCommandHandler */
    private $newsHandler;
    /** @var PrivateMessageHandler */
    private $privateMessageHandler;

    /**
     * @param RedisService          $redisService
     * @param UserRepository        $userRepository
     * @param SerializerInterface   $serializer
     * @param ValidatorInterface    $validator
     * @param NewsCommandHandler    $chatHandler
     * @param PrivateMessageHandler $privateMessageHandler
     * @param string|null $name
     */
    public function __construct(
        RedisService $redisService,
        UserRepository $userRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        NewsCommandHandler $chatHandler,
        PrivateMessageHandler $privateMessageHandler,
        string $name = null
    ) {
        parent::__construct($name);

        $this->redisService          = $redisService;
        $this->userRepository        = $userRepository;
        $this->serializer            = $serializer;
        $this->validator             = $validator;
        $this->newsHandler           = $chatHandler;
        $this->privateMessageHandler = $privateMessageHandler;
    }

	/**
	 * {@inheritDoc}
	 */
    protected function configure()
    {
        $this
	        ->setDescription('This command run websocket server');
    }

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @return integer
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $_ENV['EA_WS_PORT'];
        $output->writeln(sprintf('Starting server on port %s', $port));
        try {
            $server = new App($_ENV['EA_WS_SERVER'], $port, $_ENV['EA_WS_ADDRESS']);

            $chatHandler = new ChatHandler(
                $this->redisService,
                $this->userRepository,
                $this->serializer,
                $this->validator,
                $this->newsHandler,
                $this->privateMessageHandler
            );

            $server->route('/chat', $chatHandler, ['*']);
            $server->run();

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            $output->writeln($exception->getCode());

            return Command::FAILURE;
        }
    }
}