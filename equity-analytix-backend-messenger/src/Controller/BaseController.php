<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Service\RedisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseController
 */
class BaseController extends AbstractController
{
    /** @var RedisService */
    protected $redisService;
    /** @var SerializerInterface*/
    protected $serializer;
    /** @var ValidatorInterface*/
    protected $validator;

	/**
	 * @param RedisService $redisService
	 * @param SerializerInterface $serializer
	 * @param ValidatorInterface $validator
	 */
    protected function __construct(
        RedisService $redisService,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    )
    {
        $this->redisService = $redisService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @return JsonResponse
     */
    protected function hasNotTokenResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'data'   => [
                    'code'    => 0,
                    'message' => 'Authorization header required',
                ],
            ], JsonResponse::HTTP_FORBIDDEN
        );
    }

    /**
     * @return JsonResponse
     */
    protected function hasNotValidOrFoundTokenResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'data'   => [
                    'code'    => 0,
                    'message' => 'Token not valid or not found',
                ],
            ], JsonResponse::HTTP_FORBIDDEN
        );
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    protected function createdResponse($data): JsonResponse
    {
        return new JsonResponse(
            [
                'data'   => $data,
            ], JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @return JsonResponse
     */
    protected function failureValidationResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'data'   => [
                    'message' => 'Data validation failed.',
                ],
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /***
     * @param \Exception $exception
     * @return JsonResponse
     */
    protected function exceptionResponse(\Exception $exception): JsonResponse
    {
        return new JsonResponse(
            [
                'data'   => [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    protected function successResponse($data): JsonResponse
    {
        return new JsonResponse(
            [
                'data'   => $data,
            ], JsonResponse::HTTP_OK
        );
    }

    /**
     * @return JsonResponse
     */
    protected function userIsNotAdminResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'data'   => [
                    'code'    => 0,
                    'message' => 'User role is not admin. Check token',
                ],
            ],JsonResponse::HTTP_FORBIDDEN
        );
    }
}