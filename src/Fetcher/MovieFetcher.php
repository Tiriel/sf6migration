<?php

namespace App\Fetcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MovieFetcher
{
    public const TYPE_ID = 'i';
    public const TYPE_TITLE = 't';
    public const TYPES = [
        'id' => self::TYPE_ID,
        'title' => self::TYPE_TITLE
    ];

    /**
     * @var HttpClientInterface
     */
    private $omdbApi;

    public function __construct(HttpClientInterface $omdbApi)
    {
        $this->omdbApi = $omdbApi;
    }

    public function fetch(string $type, string $value)
    {
        if (!array_key_exists($type, self::TYPES)) {
            throw new \InvalidArgumentException(sprintf("Invalid type provided to %s. Must be one of %s, %s given.",
                __METHOD__,
                implode(' or ', self::TYPES),
                $type
            ));
        }

        $data = $this->omdbApi->request(
            Request::METHOD_GET,
            '',
            ['query' => [self::TYPES[$type] => $value]]
        )->toArray();

        if (array_key_exists('Response', $data) && $data['Response'] === 'False') {
            throw new NotFoundHttpException();
        }

        return $data;
    }
}