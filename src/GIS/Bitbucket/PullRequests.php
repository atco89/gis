<?php
declare(strict_types=1);

namespace GIS\Bitbucket;

use GIS\Http\HttpConnector;

/**
 * Class PullRequests
 * @package GIS\Bitbucket
 */
class PullRequests extends RepositoriesReader
{

    /**
     * PullRequests constructor.
     * @param HttpConnector $httpConnector
     * @param array $projects
     * @param string $username
     */
    public function __construct(HttpConnector $httpConnector, array $projects, string $username)
    {
        parent::__construct($httpConnector, $projects, $username);
        $this->username = $username;
    }

    /**
     * @param string $url
     */
    protected function readProject(string $url): void
    {
        $response = json_decode($this->httpConnector->get($url)->getContents());
        if (isset($response->values))
            foreach ($response->values as $pullRequest) {
                $author = isset($pullRequest->author->username) ? $pullRequest->author->username : null;
                if ($author === $this->username)
                    $this->count++;
            }
    }

}