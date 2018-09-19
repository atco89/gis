<?php
declare(strict_types=1);

namespace GIS\Http;

use Exception;
use GIS\Response\ExceptionResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\AggregateException;
use Psr\Http\Message\StreamInterface;

/**
 * Class HttpConnector
 * @package GIS\Bitbucket
 */
class HttpConnector
{

    /** @var Client */
    private $client;

    /**
     * HttpConnector constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param array $options
     * @return StreamInterface
     */
    public function get(string $url, array $options = []): StreamInterface
    {
        try {
            return $this->client->get($url, $options)->getBody();
        } catch (AggregateException|Exception $exception) {
            die((new ExceptionResponse($exception->getMessage()))->jsonSerialize());
        }
    }

}