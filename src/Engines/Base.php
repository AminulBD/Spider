<?php

namespace AminulBD\Spider\Engines;

use AminulBD\Spider\Contracts\Engine;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class Base implements Engine
{
    protected HttpClientInterface $http;
    public int $totalPages = 0;
    public int $currentPage = 0;
    protected array $config;
    protected array $headers = [
        'User-Agent' => 'Spider/1.0.0',
    ];
    protected ?string $baseUri = null;

    public function __construct(array $config = [])
    {
        $this->config = $config;

        $this->http = HttpClient::create([
            'base_uri' => $this->config['base_uri'] ?? $this->baseUri,
            'headers' => array_merge($this->headers, $this->config['headers'] ?? []),
        ]);
    }

    public function hasNext(): bool
    {
        return $this->totalPages > $this->currentPage;
    }
}
