<?php
declare(strict_types=1);

namespace GIS\Bitbucket;

use GIS\Http\HttpConnector;

/**
 * Class Commits
 * @package GIS\Bitbucket
 */
class Commits extends RepositoriesReader
{

    /**
     * Commits constructor.
     * @param HttpConnector $httpConnector
     * @param array $projects
     * @param string $username
     */
    public function __construct(HttpConnector $httpConnector, array $projects, string $username)
    {
        parent::__construct($httpConnector, $projects, $username);
    }

    /**
     * @param string $url
     */
    protected function readProject(string $url): void
    {
        $response = json_decode($this->httpConnector->get($url)->getContents());
        if (isset($response->values))
            foreach ($response->values as $commits) {
                $author = isset($commits->author->user->username) ? $commits->author->user->username : null;
                if ($author === $this->username)
                    $this->count++;
            }
    }

}