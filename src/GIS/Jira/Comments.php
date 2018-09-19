<?php
declare(strict_types=1);

namespace GIS\Jira;

use GIS\Http\HttpConnector;

/**
 * Class Comments
 * @package App\Jira
 */
class Comments extends IssuesReader
{

    /**
     * Comments constructor.
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
        $url = "https://$this->subDomain.atlassian.net/rest/api/2/issue/$issueId/comment?maxResults=999&startAt=0";
        $httpResponse = $this->httpConnector->get($url);
        $projects = json_decode($httpResponse->getContents());
        foreach ($projects->comments as $comment)
            if (in_array($this->accountId, [$comment->author->accountId, $comment->updateAuthor->accountId]))
                $this->count++;
    }

}