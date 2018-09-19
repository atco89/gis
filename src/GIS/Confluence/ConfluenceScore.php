<?php
declare(strict_types=1);

namespace GIS\Confluence;

use Exception;
use GIS\Badges;
use GIS\Http\HttpConnector;
use GIS\Response\ExceptionResponse;
use GIS\ScoreInterface;
use GuzzleHttp\Client;

/**
 * Class ConfluenceScore
 * @package GIS\Confluence
 */
class ConfluenceScore implements ScoreInterface
{

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public function run(array $config): array
    {
        $httpConnector = new HttpConnector(new Client([
            "auth" => [$config["username"], $config["apiToken"]]
        ]));
        try {
            $subDomain = $config["subDomain"];
            $user = new ConfluenceUser($httpConnector, $subDomain);
        } catch (Exception $exception) {
            die((new ExceptionResponse($exception->getMessage()))->jsonSerialize());
        }
        $content = new Content($httpConnector, $subDomain, $user->getAccountId());
        $contentUpdates = new ContentUpdates($httpConnector, $subDomain, $user->getAccountId(), $content->getPages());
        return [
            "new_pages" => $content->getNumberOfPages() * Badges::NEW_PAGE,
            "updates"   => $contentUpdates->getCount() * Badges::CONTENT_UPDATE
        ];
    }

}