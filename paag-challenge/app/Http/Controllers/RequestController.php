<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequestResource;
use App\Http\Requests\RequestStoreRequest;
use App\Contracts\Services\RequestServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RequestController extends Controller
{
    /**
     * The request service.
     *
     * @var \App\Contracts\Services\RequestServiceInterface
     */
    private RequestServiceInterface $requestService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->requestService = app(RequestServiceInterface::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $username
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(string $username): AnonymousResourceCollection
    {
        return RequestResource::collection(
            $this->requestService->allFor($username),
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\RequestStoreRequest $request
     * @return void
     */
    public function store(RequestStoreRequest $request): void
    {
        /** @var array<string, mixed> $data */
        $data = $request->validated();

        $this->requestService->create($data);
    }
}
