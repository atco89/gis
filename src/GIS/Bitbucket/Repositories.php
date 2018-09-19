<?php
declare(strict_types=1);

namespace GIS\Bitbucket;

use GIS\Http\HttpConnector;
use stdClass;

/**
 * Class Repositories
 * @package App\Bitbucket
 */
class Repositories
{

    /** @var array */
    private $commits;
    /** @var array */
    private $pullRequests;
    /** @var HttpConnector */
    private $httpConnector;

    /**
     * Repositories constructor.
     * @param HttpConnector $httpConnector
     * @param stdClass $repositories
     */
    public function __construct(HttpConnector $httpConnector, stdClass $repositories)
    {
        $this->httpConnector = $httpConnector;
        $this->loadRepositories($repositories);
    }

    /**
     * @param stdClass $repositories
     */
    private function loadRepositories(stdClass $repositories): void
    {
        if ($repositories !== null)
            foreach ($repositories as $repositoryUrl)
                $this->loadProject($repositoryUrl);
    }

    /**
     * @param string $url
     */
    private function loadProject(string $url): void
    {
        $response = json_decode($this->httpConnector->get($url)->getContents());
        if (isset($response->values))
            foreach ($response->values as $project) {
                if (isset($project->links->commits))
                    $this->commits[] = $project->links->commits;
                if (isset($project->links->pullrequests))
                    $this->pullRequests[] = $project->links->pullrequests;
            }
    }

    /**
     * @return array
     */
    public function getCommits(): array
    {
        return $this->commits;
    }

    /**
     * @return array
     */
    public function getPullRequests(): array
    {
        return $this->pullRequests;
    }

}