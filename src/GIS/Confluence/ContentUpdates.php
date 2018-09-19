<?php
declare(strict_types=1);

namespace GIS\Confluence;

use GIS\Http\HttpConnector;

/**
 * Class ContentUpdates
 * @package GIS\Confluence
 */
class ContentUpdates
{

    /** @var int */
    private $count = 0;
    /** @var string */
    private $subDomain;
    /** @var int */
    private $accountId;
    /** @var HttpConnector */
    private $httpConnector;

    /**
     * ContentUpdates constructor.
     * @param HttpConnector $httpConnector
     * @param string $subDomain
     * @param string $accountId
     * @param array $pages
     */
    public function __construct(HttpConnector $httpConnector, string $subDomain, string $accountId, array $pages)
    {
        $this->subDomain = $subDomain;
        $this->accountId = $accountId;
        $this->httpConnector = $httpConnector;
        $this->loadComments($pages);
    }

    /**
     * @param array $pages
     */
    private function loadComments(array $pages): void
    {
        foreach ($pages as $pageId)
            $this->readComments($pageId);
    }

    private function readComments(string $pageId): void
    {
        $url = "https://$this->subDomain.atlassian.net/wiki/rest/api/content/$pageId/child/comment?expand=history";
        $response = json_decode($this->httpConnector->get($url)->getContents());
        if (isset($response->results))
            foreach ($response->results as $page) {
                $createdBy = isset($page->history->createdBy->accountId) ? $page->history->createdBy->accountId : null;
                if ($createdBy === $this->accountId)
                    $this->count++;
            }
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

}
