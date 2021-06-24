<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Chat\User\User;
use App\ReadModel\Room\RoomFetcher;
use App\UseCases\Room;
use App\UseCases\Room\Create\Command as RoomCommand;
use App\Service\RedisService;
use DomainException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api/room")
 */
class RoomController extends AbstractApiResponseController
{
	/**@var RedisService*/
	private $redisService;
	/**@var ValidatorInterface*/
	private $validator;
	/** @var SerializerInterface*/
	private $serializer;

	/**
     * @param RedisService $redisService
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(
        RedisService $redisService,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
	    $this->redisService = $redisService;
	    $this->validator = $validator;
	    $this->serializer = $serializer;
    }

    /**
     * @Route("/create", name="room_create", methods={"POST"})
     *
     * @param Request $request
     * @param Room\Create\Handler $handler
     *
     * @throws Exception
     * @return Response
     */
    public function createRoom(Request $request, Room\Create\Handler $handler): Response
    {
        $token = $request->headers->get('Authorization');

        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        $requestData = $request->getContent();

        $command = $this->serializer->deserialize($requestData, RoomCommand::class, 'json');
        $errors  = $this->validator->validate($command);

        if (0 < count($errors)) {
            return $this->failureValidationResponse();
        }

        try {
            $room         = $handler->handle($command);
            $dataResponse = array_merge(
            	json_decode($request->getContent(), true),
	            ['token' => $room->getToken()]
            );

	        return $this->successResponse($dataResponse);
        } catch (DomainException $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    /**
     * @Route("/user/{id}", name="room.user", methods={"GET"})
     * @param Request     $request
     * @param User        $user
     * @param RoomFetcher $fetcher
     *
     * @return Response
     */
    public function getUserRoom(Request $request, User $user, RoomFetcher $fetcher): Response
    {
        $token = $request->headers->get('Authorization');

        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        try {
            $data['room_id'] = $fetcher->getUserRoom($user->getId());
            return $this->successResponse($data);
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
