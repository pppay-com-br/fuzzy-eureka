<?php

namespace App\Repositories;

use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;
use App\Contracts\Repositories\CacheRepositoryInterface;

class CacheRepository implements CacheRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function remember(string $key, DateTimeInterface|DateInterval|int|null $ttl, callable $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return Cache::forget($key);
    }
}
