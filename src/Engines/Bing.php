<?php

namespace AminulBD\Spider\Engines;

use AminulBD\Spider\Models\Content;
use AminulBD\Spider\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class Bing extends Base
{
    protected array $headers = [
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
        'Accept-Language' => 'en-GB,en;q=0.9',
    ];
    protected ?string $baseUri = 'https://www.bing.com/';
    private Collection $items;

    private function parseContent(string $html): void
    {
        $this->items = new Collection();

        $crawler = new Crawler($html);
        $crawler->filter('ol#b_results li.b_algo')->each(function (Crawler $node) {
            try {
                $link = $node->filter('h2 > a')->first();
                $description = $node->filter('.b_algoSlug')->first();
                $siteName = $node->filter('.tptxt > .tptt')->first();

                $this->items->add(new Content([
                    'siteName' => $siteName->text(''),
                    'title' => $link->text(),
                    'description' => $description->text(''),
                    'url' => $link->attr('href'),
                ]));
            } catch (\Exception $exception) {
                // TODO: handle errors.
            }
        });
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function find(array $query = []): self
    {
        $response = $this->http->request('GET', '/search', [
            'query' => [
                'q' => $query['q'] ?? null,
            ]
        ]);

        $this->parseContent($response->getContent());

        // TODO: update `currentPage` and `totalPage`, Handle next page.

        return $this;
    }

    public function next(): Collection
    {
        return $this->items;
    }
}
