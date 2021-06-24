<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RedisService;
use App\Service\Uploader\FileUploader;
use App\UseCases\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FileController
 *
 * @package App\Controller
 *
 * @Route("api/files")
 */
class FileController extends BaseController
{
    /**
     * FileController constructor.
     *
     * @param RedisService $redisService
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(
        RedisService $redisService,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        parent::__construct(
            $redisService,
            $serializer,
            $validator
        );
    }

    /**
     * @Route("/upload", name="files.upload", methods={"POST"})
     *
     * @param Request             $request
     * @param File\Upload\Handler $handler
     * @param FileUploader        $uploader
     *
     * @return Response
     */
    public function files(Request $request, File\Upload\Handler $handler, FileUploader $uploader): Response
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        $files = [];

        foreach ($request->files->get('files') as $file) {
            $uploaded = $uploader->upload($file);
            $files[]  = new File\Upload\File(
                $uploaded->getPath(),
                $uploaded->getName(),
                $uploaded->getSize()
            );
        }

        $command = new File\Upload\Command($files);

        $errors = $this->validator->validate($command);

        if (0 > count($errors)) {
            return $this->failureValidationResponse();
        }

        try {
            $handler->handle($command);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return new JsonResponse([
            'data' => array_map(function (File\Upload\File $file) {
                return ['url' => $file->path . '/' . $file->name];
            }, $files),
        ]);
    }
}
