<?php


namespace App\Controller;


use App\ReadModel\User\UserFetcher;
use App\Service\RedisService;
use App\UseCases\User\Setting\Create;
use App\UseCases\User\Setting\Edit;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/user/chat/setting")
 * Class SettingController
 *
 * @package   App\Controller
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class SettingController extends BaseController
{
    public const SETTINGS_SERIALIZATION = ['id', 'name', 'signification'];

    /**
     * @var UserFetcher
     */
    private $userFetcher;

    /**
     * SettingController constructor.
     *
     * @param RedisService $redisService
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param UserFetcher $userFetcher
     */
    public function __construct(
        RedisService $redisService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserFetcher $userFetcher
    )
    {
        parent::__construct(
            $redisService,
            $serializer,
            $validator
        );
        $this->userFetcher = $userFetcher;
    }

    /**
     * @Route("/", name="get_chat_settings", methods={"GET"})
     * @param Request $request
     */
    public function getUserSettings(Request $request)
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        try {
            $userId = $this->redisService->getUserId($token);
            $settings = $this->userFetcher->getUserSettings($userId);

            return $this->successResponse($settings);

        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }


    /**
     * @Route("/create", name="chat_setting_create", methods={"POST"})
     * @param Request $request
     * @param Create\Handler $handler
     *
     * @return JsonResponse
     */
    public function create(Request $request, Create\Handler $handler): JsonResponse
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
            Create\Command::class,
            'json'
        );

        $errors = $this->validator->validate($command);

        if (0 < count($errors)) {
            return $this->failureValidationResponse();
        }
        try {

            $setting = $handler->handle($command);
            $data = $this->serializer->serialize($setting,'json',[
                'attributes' => self::SETTINGS_SERIALIZATION
            ]);

            $data = json_decode($data);
            return $this->createdResponse($data);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

    }

    /**
     * @Route("/edit", name="chat_setting_edit", methods={"POST"})
     * @param Request $request
     * @param Edit\Handler $handler
     *
     * @return JsonResponse
     */
    public function edit(Request $request, Edit\Handler  $handler): JsonResponse
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
            Edit\Command::class,
            'json'
        );

        $errors = $this->validator->validate($command);

        if (0 < count($errors)) {
            return $this->failureValidationResponse();
        }
        try {
            $handler->handle($command);
            $data = json_decode($request->getContent());

            return $this->successResponse($data);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

    }

}
