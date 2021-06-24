<?php

namespace App\Service;


use Redis;
use RedisException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RedisService
 *
 * @package   App\Service
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class RedisService
{

    /**
     * @var Redis
     */
    private $redis;

    /**
     * RedisService constructor.
     */
    public function __construct()
    {
        $this->redis = new Redis();
    }

    /**
     * @return bool
     */
    public function isConnects(): bool
    {
        if (!$this->connectRedis()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isCorrectToken(string $token): bool
    {
        $this->connectRedis();
        $value = $this->redis->get($token);
        if (!$value) {
            return false;
        }

        return true;
    }

    /**
     * @param string $token
     * @return array|mixed|object
     * @throws RedisException
     */
    public function getTokenJson(string $token)
    {
        $this->connectRedis();
        $value = $this->redis->get($token);
        $data  = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RedisException( 'Invalid json',Response::HTTP_BAD_REQUEST);
        }

        return $data;
    }

    /**
     * @return Redis
     */
    public function getRedis(): Redis
    {
        return $this->redis;
    }

    /**
     * @param string $token
     * @return bool|mixed|string
     * @throws RedisException
     */
    public function getUserId(string $token)
    {
        return $this->getTokenJson($token)['id'];
    }

    /**
     * @return bool
     */
    public function connectRedis(): bool
    {
        return $this->redis->connect($_ENV['EA_REDIS_HOST'], $_ENV['EA_REDIS_PORT']);
    }
}