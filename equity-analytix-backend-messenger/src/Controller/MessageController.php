<?php

namespace App\Controller;

use App\Entity\Chat\Room\Room;
use App\Service\RedisService;
use App\UseCases\Message;
use App\ReadModel\Message\MessageFetcher;
use App\ReadModel\User\UserFetcher;
use Doctrine\ORM\EntityNotFoundException;
use DomainException;
use Exception;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use RedisException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class MessageController
 *
 * @package App\Controller
 * @Route("/api/message")
 */
class MessageController extends BaseController
{
    /**
     * @var MessageFetcher
     */
    protected $messages;

    /**
     * @var UserFetcher
     */
    private $users;

    /**
     * MessageController constructor.
     *
     * @param MessageFetcher      $messages
     * @param UserFetcher         $users
     * @param RedisService        $redisService
     * @param SerializerInterface $serializer
     * @param ValidatorInterface  $validator
     */
    public function __construct(
        MessageFetcher $messages,
        UserFetcher $users,
        RedisService $redisService,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct(
            $redisService,
            $serializer,
            $validator
        );

        $this->messages = $messages;
        $this->users    = $users;
    }

    /**
     * @Route("/check_redis", methods={"POST"})
     * @return Response
     */
    public function checkRedis()
    {
        try {
            $this->redisService->isConnects();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return new JsonResponse(
            [
                'status' => 'ok',
                'data'   => 'Redis connected',
            ], JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route ("/chat/{roomId}", name="get_chat_room_messages", methods={"GET"})
     * @param Request $request
     * @param int     $roomId
     *
     * @return JsonResponse|Response
     */
    public function getChatRoomMessages(Request $request, int $roomId)
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        try {
            $messages = $this->messages->getRoomMessages($roomId);
            return $this->successResponse($messages);
        } catch (DomainException $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    /**
     * @Route ("/chat/{id}/view", name="chat.room.message.view", methods={"POST"})
     * @param Request              $request
     * @param Room                 $room
     * @param Message\View\Handler $handler
     *
     * @return Response
     */
    public function createViewedMessage(Request $request, Room $room, Message\View\Handler $handler): Response
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        try {
            $messages = $request->request->get('messages');

            foreach ($messages as $message) {
                $command = new Message\View\Command($this->redisService->getUserId($token), $room->getId());
                $command->id = $message;

                $errors = $this->validator->validate($command);

                if (0 < count($errors)) {
                    return $this->failureValidationResponse();
                }

                $handler->handle($command);
            }
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return $this->successResponse($messages);
    }

    /**
     * @Route ("/chat/{id}/count", name="chat.room.message.count", methods={"GET"})
     *
     * @param Request        $request
     * @param Room           $room
     * @param MessageFetcher $messages
     *
     * @return JsonResponse
     */
    public function count(Request $request, Room $room, MessageFetcher $messages): Response
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        try {
            $data['count'] = $messages->getNotViewedCount($this->redisService->getUserId($token), $room->getId());
            return $this->successResponse($data);
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
