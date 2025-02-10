<?php

namespace App\Services;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Contracts\Repositories\RequestRepositoryInterface;
use App\Contracts\Services\{
    CacheServiceInterface,
    RequestServiceInterface,
};

class RequestService implements RequestServiceInterface
{
    /**
     * The request repository.
     *
     * @var \App\Contracts\Repositories\RequestRepositoryInterface
     */
    private RequestRepositoryInterface $requestRepository;

    /**
     * The cache service.
     *
     * @var \App\Contracts\Services\CacheServiceInterface
     */
    private CacheServiceInterface $cacheService;

    /**
     * The request cache TTL.
     *
     * @var int
     */
    private const REQUESTS_CACHE_TTL = 60 * 60 * 24; // 1 day

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cacheService = app(CacheServiceInterface::class);
        $this->requestRepository = app(RequestRepositoryInterface::class);
    }

    /**
     * Get all request for given username.
     *
     * @param string $username
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request>
     */
    public function allFor(string $username): Collection
    {
        $key = $this->getAllForUsernameCacheKey($username);

        return $this->cacheService->remember($key, self::REQUESTS_CACHE_TTL, function () use ($username) {
            return $this->requestRepository->allFor($username);
        });
    }

    /**
     * Create a new request.
     *
     * @param array<string, string> $data
     * @return \App\Models\Request
     */
    public function create(array $data): Request
    {
        $this->forgetAllForUsernameCache($data['github_username']);

        return $this->requestRepository->create($data);
    }

    /**
     * Forget the cache for given username.
     *
     * @param string $username
     * @return void
     */
    private function forgetAllForUsernameCache(string $username): void
    {
        $key = $this->getAllForUsernameCacheKey($username);

        $this->cacheService->forget($key);
    }

    /**
     * Get the all for user cache key.
     *
     * @param string $username
     * @return string
     */
    private function getAllForUsernameCacheKey(string $username): string
    {
        return "requests_for_$username";
    }
}
