<?php
declare(strict_types=1);

namespace GIS\Confluence;

use GIS\Http\HttpConnector;

/**
 * Class Content
 * @package GIS\Confluence
 */
class Content
{

    /** @var array */
    private $pages = [];
    /** @var HttpConnector */
    private $httpConnector;

    /**
     * Content constructor.
     * @param string $subDomain
     * @param string $accountId
     * @param HttpConnector $httpConnector
     */
    public function __construct(HttpConnector $httpConnector, string $subDomain, string $accountId)
    {
        $this->httpConnector = $httpConnector;
        $this->loadPages($subDomain, $accountId);
    }

    /**
     * @param string $subDomain
     * @param string $accountId
     */
    private function loadPages(string $subDomain, string $accountId): void
    {
        $url = "https://$subDomain.atlassian.net/wiki/rest/api/content?expand=history";
        $response = json_decode($this->httpConnector->get($url)->getContents());
        if (isset($response->results))
            foreach ($response->results as $page) {
                $createdBy = isset($page->history->createdBy->accountId) ? $page->history->createdBy->accountId : null;
                if ($createdBy === $accountId)
                    $this->pages[] = $page->id;
            }
    }

    /**
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return count($this->pages);
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }

}
