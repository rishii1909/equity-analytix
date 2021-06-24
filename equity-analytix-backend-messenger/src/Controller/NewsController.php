<?php

namespace App\Controller;

use App\ReadModel\News\NewsFetcher;
use App\Service\RedisService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCases\News\Archive\Command as NewsArchiveCommand;
use App\UseCases\News\ArchiveAllForDay\Command as NewsArchiveAllCommand;
use App\UseCases\News\RestoreAllForDay\Command as NewsRestoreAllCommand;
use App\UseCases\News;
use App\UseCases\News\Viewed\Create\Command as NewsViewedCommand;
use App\UseCases\News\Viewed\Create\Handler as NewsViewedHandler;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api/news")
 * Class NewsController
 *
 * @package   App\Controller
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class NewsController extends BaseController
{
    public const LIMIT              = 50;
    public const NEWS_SERIALIZATION = ['id', 'status', 'text', 'createdAt'];

    /**
     * @var NewsFetcher
     */
    private $newsFetcher;

    /**
     * NewsController constructor.
     *
     * @param RedisService        $redisService
     * @param NewsFetcher         $newsFetcher
     * @param ValidatorInterface  $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(
        RedisService $redisService,
        NewsFetcher $newsFetcher,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        parent::__construct(
            $redisService,
            $serializer,
            $validator
        );
        $this->newsFetcher = $newsFetcher;
    }


    /**
     * @Route("/archive", name="news_archive", methods={"POST"})
     * @param Request              $request
     * @param News\Archive\Handler $handler
     *
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function archiveAdminNews(
        Request $request,
        News\Archive\Handler $handler
    )
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
            NewsArchiveCommand::class,
            'json'
        );
        $errors  = $this->validator->validate($command);

        if (0 < count($errors)) {
            return $this->failureValidationResponse();
        }

        try {
            $handler->handle($command);
        } catch (\DomainException $exception) {
            return $this->exceptionResponse($exception);
        }

        return new JsonResponse(
            [
                'message' => 'Archived.',
            ], JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/archive/all", name="news_archive_all", methods={"POST"})
     * @param Request                       $request
     * @param News\ArchiveAllForDay\Handler $handler
     *
     * @return JsonResponse
     */
    public function archiveAllNews(
        Request $request,
        News\ArchiveAllForDay\Handler $handler): JsonResponse
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

            $command = new NewsArchiveAllCommand($userId);
            $errors  = $this->validator->validate($command);

            if (0 < count($errors)) {
                return $this->failureValidationResponse();
            }

            $handler->handle($command);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return new JsonResponse(
            [
                'message' => 'Archived.',
            ], JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/restore/all", name="news_restore_all", methods={"POST"})
     *
     * @param Request                       $request
     * @param News\RestoreAllForDay\Handler $handler
     *
     * @return JsonResponse
     */
    public function restoreAllNewsForDay(
        Request $request,
        News\RestoreAllForDay\Handler $handler
    ): JsonResponse
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

            $command = new NewsRestoreAllCommand($userId);
            $errors  = $this->validator->validate($command);

            if (0 < count($errors)) {
                return $this->failureValidationResponse();
            }

            $handler->handle($command);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return new JsonResponse(
            [
                'message' => 'Restored.',
            ], JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/create", name="news_create", methods={"POST"})
     * @param Request             $request
     * @param News\Create\Handler $handler
     *
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function createNews(Request $request, News\Create\Handler $handler): JsonResponse
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
            News\Create\Command::class,
            'json'
        );

        $errors = $this->validator->validate($command);

        if (0 > count($errors)) {
            return $this->failureValidationResponse();
        }

        try {
            $handler->handle($command);
        } catch (\DomainException $exception) {
            return $this->exceptionResponse($exception);
        }

        $data = json_decode($request->getContent());
        return $this->createdResponse($data);
    }

    /**
     * @Route("/admin", name="get_all_news_for_admin", methods={"GET"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAllNewsForAdmin(Request $request)
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }
        try {
            $offset = $request->query->getInt('offset', 1);
            $news   = $this->newsFetcher->findAll($offset, self::LIMIT, 'desc');
            return $this->successResponse(array_reverse($news));
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    /**
     * @Route("/{timestamp}", name="news_latest", methods={"GET"})
     * @param Request $request
     * @param int     $timestamp
     *
     * @return JsonResponse
     */
    public function getLatestNews(Request $request, int $timestamp)
    {
        $data  = [];
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }
        try {
            $userId             = $this->redisService->getUserId($token);
            $cutoffDate         = (new \DateTimeImmutable())->setTimestamp($timestamp)
                ->setTimezone(new \DateTimeZone('EST'))
                ->format('Y-m-d');

            $data ['viewed']    = $this->newsFetcher->findLastViewedNews($cutoffDate, $userId);
            $data ['notViewed'] = $this->newsFetcher->findLastNotViewedNews($cutoffDate, $userId);
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return $this->successResponse($data);
    }

    /**
     * @Route("/search/{query}", name="search_news", methods={"GET"})
     * @param Request $request
     * @param string  $query
     *
     * @return Response
     */
    public function searchMessage(
        Request $request,
        string $query
    )
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }

        try {
            $data         = [];
            $data['news'] = $this->newsFetcher->searchNews($query);

        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return $this->successResponse($data);
    }

    /**
     * @Route("/{timestamp}", name="add_viewed_news", methods={"POST"})
     *
     * @param Request           $request
     * @param NewsViewedHandler $handler
     *
     * @return JsonResponse
     */
    public function createViewedNews(Request $request, NewsViewedHandler $handler): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return $this->hasNotTokenResponse();
        }

        if (!$this->redisService->isCorrectToken($token)) {
            return $this->hasNotValidOrFoundTokenResponse();
        }
        try {

            $news = json_decode($request->getContent());

            foreach ($news as $newsUnit) {
                $newsUnit = json_encode($newsUnit);
                $command  = $this->serializer->deserialize(
                    $newsUnit,
                    NewsViewedCommand::class,
                    'json'
                );

                $errors = $this->validator->validate($command);

                if (0 < count($errors)) {
                    return $this->failureValidationResponse();
                }

                $handler->handle($command);

            }
        } catch (\Exception $exception) {
            return $this->exceptionResponse($exception);
        }

        return $this->successResponse($news);
    }
}
