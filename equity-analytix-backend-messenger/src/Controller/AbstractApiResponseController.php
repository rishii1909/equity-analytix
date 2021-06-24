<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @package App\Controller
 */
abstract class AbstractApiResponseController extends AbstractController
{
	/**
	 * @return JsonResponse
	 */
	protected function hasNotTokenResponse(): JsonResponse
	{
		return $this->json([
			"status"   => "error",
			"response" => [
				'code'    => JsonResponse::HTTP_FORBIDDEN,
				'message' => 'Authorization header required',
			],
		], JsonResponse::HTTP_FORBIDDEN);
	}

	/**
	 * @return JsonResponse
	 */
	protected function hasNotValidOrFoundTokenResponse(): JsonResponse
	{
		return $this->json([
			"status"   => "error",
			"response" => [
				'code'    => JsonResponse::HTTP_FORBIDDEN,
				'message' => 'Token not valid or not found',
			],
		], JsonResponse::HTTP_FORBIDDEN);
	}

	/**
	 * @return JsonResponse
	 */
	protected function failureValidationResponse(): JsonResponse
	{
		return $this->json([
			"status"   => "error",
			"response" => [
				'code'    => JsonResponse::HTTP_BAD_REQUEST,
				'message' => 'Data validation failed.',
			],
		], JsonResponse::HTTP_BAD_REQUEST);
	}

	/***
	 * @param \Exception $exception
	 * @return JsonResponse
	 */
	protected function exceptionResponse(\Exception $exception): JsonResponse
	{
		return $this->json([
			"status"   => "error",
			"response" => [
				'code'    => $exception->getCode(),
				'message' => $exception->getMessage(),
			],
		], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
	}

    /**
     * @param mixed $data
     * @param array $groups
     * @return JsonResponse
     */
    public function successResponse($data, array $groups = ["read"]):JsonResponse
    {
        $context = ["groups" => $groups];

        return $this->json([
            "status"   => "success",
            "response" => $data,
        ], JsonResponse::HTTP_OK, [], $context);
    }

    /**
     * @param array   $errors
     * @param array   $response
     * @param integer $code
     * @return JsonResponse
     */
    public function errorResponse(array $errors = [], array $response = [], int $code = JsonResponse::HTTP_NOT_FOUND):JsonResponse
    {
        return $this->json([
            "status"   => "error",
            "errors"   => $errors,
            "response" => $response,
        ], $code);
    }
}
