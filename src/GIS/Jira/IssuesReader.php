<?php
declare(strict_types=1);

namespace GIS\Jira;

use GIS\Http\HttpConnector;

/**
 * Class IssuesReader
 * @package GIS\Jira
 */
abstract class IssuesReader
{

    /** @var int */
    protected $count = 0;
    /** @var string */
    protected $subDomain;
    /** @var string */
    protected $accountId;
    /** @var HttpConnector */
    protected $httpConnector;

    /**
     * IssuesReader constructor.
     * @param HttpConnector $httpConnector
     * @param array $issues
     * @param string $subDomain
     * @param string $accountId
     */
    public function __construct(HttpConnector $httpConnector, array $issues, string $subDomain, string $accountId)
    {
        $this->subDomain = $subDomain;
        $this->accountId = $accountId;
        $this->httpConnector = $httpConnector;
        $this->loadIssues($issues);
    }

    /**
     * @param array $issues
     */
    protected function loadIssues(array $issues): void
    {
        foreach ($issues as $issue)
            $this->readIssue($issue);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param string $issueId
     */
    abstract protected function readIssue(string $issueId): void;

}