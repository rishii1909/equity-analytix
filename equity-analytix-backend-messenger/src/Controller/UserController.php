<?php

declare(strict_types=1);

namespace App\Controller;

use App\ReadModel\User\UserFetcher;
use App\Service\RedisService;
use App\UseCases\User;
use DomainException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 * Class UserController
 *
 * @package App\Controller
 */
class UserController extends BaseController
{
    /**
     * @var UserFetcher
     */
    private $users;

    /**
     * MessageController constructor.
     *
     * @param RedisService $redisService
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param UserFetcher $users
     */
    public function __construct(
        RedisService $redisService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserFetcher $users
    ) {
        parent::__construct(
            $redisService,
            $serializer,
            $validator
        );

        $this->users = $users;
    }

    /**
     * @Route("/user/all", name="get_all_users_for_admin", methods={"GET"})
     *
     * @param Request $request
     *
     * @throws Exception
     * @return Response
     */
    public function getAllUsersForAdmin(Request $request)
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        $tokenValue = $this->redisService->getTokenJson($token);

        if ($tokenValue['role'][0] !== 'administrator') {
            return $this->userIsNotAdminResponse();
        }

        try {
            $data[] = $this->users->findAllUsers();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return $this->successResponse($data);
    }

    /**
     * @Route("/user/info", name="get_user_by_token", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getUserByToken(Request $request)
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }
        try {
            $tokenValue = $this->redisService->getTokenJson($token);

            $data = [];
            $data['role'] = $tokenValue['role'][0];
            // $data['role'] = "administrator";
            $data['info'] = $this->users->findUserById((int)$tokenValue['id']);
            // $data['info'] = $this->users->findUserById(1);

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return $this->successResponse($data);
    }

    /**
     * @Route("/user/create", name="create_user_from_wp", methods={"POST"})
     * @param Request $request
     * @param User\Create\Handler $handler
     *
     * @throws Exception
     * @return JsonResponse|Response
     */
    public function createUserFromWp(Request $request, User\Create\Handler $handler)
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        $command = $this->serializer->deserialize(
            $request->getContent(),
            User\Create\Command::class,
            'json'
        );
        $errors  = $this->validator->validate($command);

        if (0 < count($errors)) {
            return $this->failureValidationResponse();
        }

        try {
            $handler->handle($command);
        } catch (DomainException $exception) {
            return $this->exceptionResponse($exception);
        }

        $data = json_decode($request->getContent());
        return $this->successResponse($data);

    }
}
