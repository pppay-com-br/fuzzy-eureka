<?php

namespace App\Services;

use DateInterval;
use DateTimeInterface;
use App\Contracts\Services\CacheServiceInterface;
use App\Contracts\Repositories\CacheRepositoryInterface;

class CacheService implements CacheServiceInterface
{
    /**
     * The cache repository.
     *
     * @var \App\Contracts\Repositories\CacheRepositoryInterface
     */
    private CacheRepositoryInterface $cacheRepository;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cacheRepository = app(CacheRepositoryInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function remember(string $key, DateTimeInterface|DateInterval|int|null $ttl, callable $callback): mixed
    {
        return $this->cacheRepository->remember($key, $ttl, $callback);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return $this->cacheRepository->forget($key);
    }
}
