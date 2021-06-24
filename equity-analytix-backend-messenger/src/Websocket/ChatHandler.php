<?php
declare(strict_types = 1);

namespace App\Websocket;

use App\Entity\Chat\Room\Room;
use App\Entity\Chat\User\User;
use App\Enum\ChatMessageTypeEnum;
use App\Enum\UserEnum;
use App\Repository\UserRepository;
use App\UseCases\Message\Create\Handler\PrivateMessageHandler;
use App\UseCases\News;
use App\UseCases\Message;
use App\Service\RedisService;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;
use SplObjectStorage;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ChatHandler
 */
class ChatHandler implements MessageComponentInterface
{
    public const NEWS_SERIALIZATION        = ['messageType', 'id', 'status', 'text', 'createdAt'];
    public const ONLINE_USERS_MESSAGE_TYPE = 'onlineUsers';

    /**@var SplObjectStorage */
    protected $clients;
    /** @var RedisService */
    private $redisService;
    /** @var UserRepository */
    private $userRepository;
    /** @var Serializer */
    private $serializer;
    /** @var ValidatorInterface */
    private $validator;
    /** @var News\Create\Handler */
    private $newsHandler;
    /** @var PrivateMessageHandler */
    private $privateMessageHandler;

    /**
     * @param RedisService          $redisService
     * @param UserRepository        $userRepository
     * @param SerializerInterface   $serializer
     * @param ValidatorInterface    $validator
     * @param News\Create\Handler   $newsHandler
     * @param PrivateMessageHandler $privateMessageHandler
     */
    public function __construct(
        RedisService $redisService,
        UserRepository $userRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        News\Create\Handler $newsHandler,
        PrivateMessageHandler $privateMessageHandler
    ) {
        $this->clients        = new SplObjectStorage();
        $this->redisService   = $redisService;
        $this->userRepository = $userRepository;
        $this->serializer     = $serializer;
        $this->validator      = $validator;
        $this->newsHandler    = $newsHandler;
        $this->privateMessageHandler = $privateMessageHandler;
    }

    /**
     * {@inheritDoc}
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    /**
     * {@inheritDoc}
     */
    function onClose(ConnectionInterface $conn)
    {
        $data = [
            'messageType'  => ChatMessageTypeEnum::CONNECT,
            'onlineStatus' => UserEnum::CHAT_OFFLINE,
        ];

        foreach ($this->clients as $client) {
            if ($client === $conn) {
               $userId         = $this->clients->getInfo();
               $data['userId'] = $userId['userId'];
            }
        }

        $message = $this->serializer->encode(
            $data,
            'json'
        );

        foreach ($this->clients as $client) {
            $client->send($message);
        }

        $this->clients->detach($conn);
    }

    /**
     * {@inheritDoc}
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send($e->getCode());
        $conn->send($e->getMessage());
        $conn->close();
    }

    /**
     * {@inheritDoc}
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $messageData = json_decode(trim($msg), true);
        $token       = $messageData['token'] ?? null;
        $messageType = $messageData['messageType'] ?? null;

        if (null === $token || null === $messageType) {
            $this->onClose($from);
        }

        $token       = (string) $token;
        $messageType = (string) $messageType;

        if (! $this->redisService->isCorrectToken($token)) {
            $this->onClose($from);
        }

        if ($this->clients->contains($from)) {
            $isValidMessageType = ChatMessageTypeEnum::checkValue($messageType);

            if (! $isValidMessageType) {
                $this->onError($from, new \Exception('Message type is not valid.'));
            }

            switch ($messageType) {
                case ChatMessageTypeEnum::NEWS:
                    $this->handleNewsTypeMessage($from, $messageData);
                    break;
                case ChatMessageTypeEnum::PRIVATE:
                    $this->handlePrivateTypeMessage($from, $messageData);
                    break;
                case ChatMessageTypeEnum::CONNECT:
                    $this->handleConnectTypeMessage($from, $messageData);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param array               $msg The message received
     * @throws \Exception
     */
    private function handleNewsTypeMessage(ConnectionInterface $from, $msg):void
    {
        $msg = json_encode($msg);

        $command = $this->serializer->deserialize(
            $msg,
            News\Create\Command::class,
            'json'
        );

        $errors = $this->validator->validate($command);

        if (0 > count($errors)) {
            $this->onError($from, new \Exception('Data validation failed.'));
        }

        try {
            $savedNewsData = $this->newsHandler->handle($command);

            $news = $this->serializer->serialize(
                $savedNewsData,
                'json',
                [
                    'attributes' => self::NEWS_SERIALIZATION,
                ]
            );

            foreach ($this->clients as $connection) {
                $connection->send($news);
            }
        } catch (\DomainException $exception) {
            $this->onError($from, $exception);
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param array               $msg The message received
     * @throws \Exception
     */
    private function handleConnectTypeMessage(ConnectionInterface $from, $msg):void
    {
        $user = $this->getUserByToken($msg['token']);

        if (null === $user) {
            $this->onError($from, new \Exception('User not found by token.'));
        }

        foreach ($this->clients as $connection) {
            if ($connection === $from) {
                $this->clients->setInfo(['userId' => $user->getId()]);
            }
        }

        $alreadyConnected = [];
        foreach ($this->clients as $client) {
            $connectedUserId    = $this->clients->getInfo();
            $alreadyConnected[] = $connectedUserId['userId'];
        }

        $typeOfMessage = [
            'messageType' => self::ONLINE_USERS_MESSAGE_TYPE,
            'userIds'     => $alreadyConnected,
        ];

        $from->send(json_encode($typeOfMessage));

        $data = [
            'messageType'  => ChatMessageTypeEnum::CONNECT,
            'userId'       => $user->getId(),
            'onlineStatus' => UserEnum::CHAT_ONLINE,
        ];

        $message = $this->serializer->encode(
            $data,
            'json'
        );

        foreach ($this->clients as $connection) {
            if ($connection !== $from) {
                $connection->send($message);
            }
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param array               $msg The message received
     * @throws \Exception
     */
    private function handlePrivateTypeMessage(ConnectionInterface $from, $msg):void
    {
        $token       = $msg['token'] ?? null;
        $room_token  = $msg['room']['token'] ?? null;
        $message     = $msg['text'] ?? null;

        if (!$token ||
            !$room_token ||
            !$message
        ) {
            $this->onClose($from);
        }

        /**@var string $token*/
        /**@var string $room_token*/
        /**@var string $message*/
        $userId = (int) $this->redisService->getUserId($token);

        if (! $this->redisService->isCorrectToken($token) || ! $userId ) {
            $this->onClose($from);
        }

        if ($this->clients->contains($from)) {
            $command = new Message\Create\PrivateMessageCommand( $userId, $message, $room_token);
            $errors  = $this->validator->validate($command);

            if (0 > count($errors)) {
                $this->onError($from, new \Exception('Data validation failed.'));
            }

            try {
                $messageEntity = $this->privateMessageHandler->handle($command);

                if (null === $messageEntity) {
                    $this->onError($from, new \Exception('The message could not be processed.'));
                }

                /*$message = $this->serializer->serialize(
                    $messageEntity, 'json', [
                    'attributes' => self::MESSAGE_SERIALISATION,
                ]);*/

                foreach ($this->clients as $connection) {
                    $connectedUserId = $this->clients->getInfo();
                    if ($from !== $connection && in_array($connectedUserId['userId'], $messageEntity->getChatRoom()->getParticipantIds(), true)) {
                        $connection->send(
                            json_encode($msg)
                        );
                    }
                }
            } catch (\DomainException $exception) {
                $this->onError($from, $exception);
            }
        }
    }

    /**
     * @param string $token
     *
     * @throws \RedisException
     * @return User|null
     */
    private function getUserByToken(string $token): ?User
    {
        return $this->userRepository->findById(
            (int) $this->redisService->getUserId($token)
        );
    }
}