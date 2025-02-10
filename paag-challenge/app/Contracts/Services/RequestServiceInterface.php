<?php

namespace App\Contracts\Services;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

interface RequestServiceInterface
{
    /**
     * Get all request for given username.
     *
     * @param string $username
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request>
     */
    public function allFor(string $username): Collection;

    /**
     * Create a new request.
     *
     * @param array<string, string> $data
     * @return \App\Models\Request
     */
    public function create(array $data): Request;
}
