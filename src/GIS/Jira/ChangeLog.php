<?php
declare(strict_types=1);

namespace GIS\Jira;

use GIS\Http\HttpConnector;

/**
 * Class ChangeLog
 * @package App\Jira
 */
class ChangeLog extends IssuesReader
{

    /** @var int */
    protected $closedIssuesCount = 0;

    /**
     * ChangeLog constructor.
     * @param HttpConnector $httpConnector
     * @param array $issues
     * @param string $subDomain
     * @param string $accountId
     */
    public function __construct(HttpConnector $httpConnector, array $issues, string $subDomain, string $accountId)
    {
        parent::__construct($httpConnector, $issues, $subDomain, $accountId);
    }

    /**
     * @param string $issueId
     */
    protected function readIssue(string $issueId): void
    {
        $url = "https://$this->subDomain.atlassian.net/rest/api/2/issue/$issueId/changelog?maxResults=999&startAt=0";
        $httpResponse = $this->httpConnector->get($url);
        $projects = json_decode($httpResponse->getContents());
        foreach ($projects->values as $value)
            if ($value->author->accountId === $this->accountId)
                $this->counter($value->items);
    }

    /**
     * @param array $items
     */
    private function counter(array $items)
    {
        foreach ($items as $item)
            if (in_array($item->field, ['Attachment', 'status']))
                $this->count++;
            else if ($item->field === 'resolution' && $item->toString === 'Done')
                $this->closedIssuesCount++;
    }

    /**
     * @return int
     */
    public function getClosedIssuesCount(): int
    {
        return $this->closedIssuesCount;
    }

}