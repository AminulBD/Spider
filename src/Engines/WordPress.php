<?php

namespace AminulBD\Spider\Engines;

use AminulBD\Spider\Models\Content;
use AminulBD\Spider\Support\Collection;

class WordPress extends Base
{
    private Collection $items;

    /**
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['base_uri']) || !filter_var($config['base_uri'], FILTER_VALIDATE_URL)) {
            throw new \Exception("The `base_uri` is required and must be a valid WordPress API url.");
        }

        parent::__construct($config);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function find(array $query = []): self
    {
        $this->items = new Collection();

        $response = $this->http->request('GET', 'v2/posts', [
            'query' => [
                'search' => $query['q'] ?? null,
                '_fields' => 'excerpt,title,link'
            ]
        ]);

        $siteName = parse_url($this->config['base_uri'], PHP_URL_HOST);

        $items = json_decode($response->getContent(), true);
        foreach ((array)$items as $item) {
            $this->items->add(new Content([
                'siteName' => $siteName,
                'title' => $item['title']['rendered'],
                'description' => $item['excerpt']['rendered'],
                'url' => $item['link'],
            ]));
        }
        // TODO: update `currentPage` and `totalPage`, Handle next page.

        return $this;
    }

    public function next(): Collection
    {
        return $this->items;
    }
}
