<?php
declare(strict_types=1);

namespace GIS\Bitbucket;

use Exception;
use GIS\Http\HttpConnector;
use stdClass;

/**
 * Class BitbucketUser
 * @package GIS\Bitbucket
 */
class BitbucketUser
{

    /** @var HttpConnector */
    private $httpConnector;
    /** @var string */
    private $username;
    /** @var stdClass */
    private $repositories;

    /**
     * BitbucketUser constructor.
     * @param HttpConnector $httpConnector
     * @throws Exception
     */
    public function __construct(HttpConnector $httpConnector)
    {
        $this->httpConnector = $httpConnector;
        $this->load();
    }

    /**
     * @throws Exception
     */
    private function load(): void
    {
        $response = json_decode($this->httpConnector->get("https://api.bitbucket.org/2.0/user")->getContents());
        if (!isset($response->username) || !isset($response->links->repositories))
            throw new Exception("Cannot find username or repositories for Bitbucket account.");
        $this->username = $response->username;
        $this->repositories = $response->links->repositories;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return stdClass
     */
    public function getRepositories(): stdClass
    {
        return $this->repositories;
    }

}