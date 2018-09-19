<?php
declare(strict_types=1);

namespace GIS\Bitbucket;

use GIS\Http\HttpConnector;

/**
 * Class RepositoriesReader
 * @package GIS\Bitbucket
 */
abstract class RepositoriesReader
{

    /** @var int */
    protected $count = 0;
    /** @var string */
    protected $username;
    /** @var HttpConnector */
    protected $httpConnector;

    /**
     * RepositoriesReader constructor.
     * @param HttpConnector $httpConnector
     * @param array $projects
     * @param string $username
     */
    public function __construct(HttpConnector $httpConnector, array $projects, string $username)
    {
        $this->username = $username;
        $this->httpConnector = $httpConnector;
        $this->loadProjects($projects);
    }

    /**
     * @param array $projects
     */
    private function loadProjects(array $projects): void
    {
        foreach ($projects as $project)
            if (isset($project->href))
                $this->readProject($project->href);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param string $url
     */
    abstract protected function readProject(string $url): void;

}