<?php
declare(strict_types=1);

namespace GIS\Confluence;

use Exception;
use GIS\Http\HttpConnector;
use GIS\Response\ExceptionResponse;

/**
 * Class ConfluenceUser
 * @package GIS\Confluence
 */
class ConfluenceUser
{

    /** @var string */
    private $accountId;
    /** @var string */
    private $subDomain;
    /** @var HttpConnector */
    private $httpConnector;

    /**
     * ConfluenceUser constructor.
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
            die((new ExceptionResponse($exception->getMessage()))->jsonSerialize());
        }
    }

    /**
     * @throws Exception
     */
    private function loadUser(): void
    {
        $url = "https://$this->subDomain.atlassian.net/wiki/rest/api/user/current";
        $response = json_decode($this->httpConnector->get($url)->getContents());
        if (!isset($response->accountId))
            throw new Exception("Cannot find account id for confluence account");
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
