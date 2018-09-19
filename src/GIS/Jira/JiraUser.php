<?php
declare(strict_types=1);

namespace GIS\Jira;

use Exception;
use GIS\Http\HttpConnector;

/**
 * Class JiraUser
 * @package GIS\Jira
 */
class JiraUser
{

    /** @var string */
    private $accountId;
    /** @var string */
    private $subDomain;
    /** @var HttpConnector */
    private $httpConnector;

    /**
     * JiraUser constructor.
     * @param HttpConnector $httpConnector
     * @param string $subDomain
     */
    public function __construct(HttpConnector $httpConnector, string $subDomain)
    {
        $this->subDomain = $subDomain;
        $this->httpConnector = $httpConnector;
        try {
            $this->loadUser();
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function loadUser(): void
    {
        $url = "https://$this->subDomain.atlassian.net/rest/api/2/myself";
        $response = json_decode($this->httpConnector->get($url)->getContents());
        if (!isset($response->accountId))
            throw new Exception("Cannot find account id for jira account");
        $this->accountId = $response->accountId;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

}
