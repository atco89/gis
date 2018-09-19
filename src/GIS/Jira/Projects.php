<?php
declare(strict_types=1);

namespace GIS\Jira;

use GIS\Http\HttpConnector;

/**
 * Class Projects
 * @package GIS\Jira
 */
class Projects
{

    /** @var array */
    private $issues;
    /** @var HttpConnector */
    private $httpConnector;
    /** @var string */
    private $subDomain;

    /**
     * Projects constructor.
     * @param HttpConnector $httpConnector
     * @param string $subDomain
     */
    public function __construct(HttpConnector $httpConnector, string $subDomain)
    {
        $this->subDomain = $subDomain;
        $this->httpConnector = $httpConnector;
        $this->loadProjects();
    }

    /**
     * @return void
     */
    private function loadProjects(): void
    {
        $url = "https://$this->subDomain.atlassian.net/rest/api/2/project";
        $projects = json_decode($this->httpConnector->get($url)->getContents());
        foreach ($projects as $project)
            if (isset($project->id))
                $this->readProject($project->id);
    }

    /**
     * @param string $projectId
     */
    private function readProject(string $projectId): void
    {
        $url = "https://$this->subDomain.atlassian.net/rest/api/2/search?jql=project=\"{$projectId}\"";
        $projects = json_decode($this->httpConnector->get($url)->getContents());
        foreach ($projects->issues as $issue)
            if (isset($issue->id))
                $this->issues[] = $issue->id;
    }

    /**
     * @return array
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

}
