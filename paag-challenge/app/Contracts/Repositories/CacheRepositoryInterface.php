<?php

namespace App\Contracts\Repositories;

use DateInterval;
use DateTimeInterface;

interface CacheRepositoryInterface
{
    /**
     * Retrieve value from cache or get from callback.
     *
     * @param string $key
     * @param DateTimeInterface|DateInterval|int|null $ttl
     * @param callable $callback
     * @return mixed
     */
    public function remember(string $key, DateTimeInterface|DateInterval|int|null $ttl, callable $callback): mixed;

    /**
     * Forget a stored cache by key.
     *
     * @param string $key
     * @return bool
     */
    public function forget(string $key): bool;
}
