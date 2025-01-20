<?php

namespace App\Repositories;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Contracts\Repositories\RequestRepositoryInterface;

class RequestRepository implements RequestRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function allFor(string $username): Collection
    {
        return Request::query()
            ->select(['id', 'commit_hash', 'github_username', 'created_at'])
            ->where('github_username', $username)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): Request
    {
        return Request::query()->create($data);
    }
}
