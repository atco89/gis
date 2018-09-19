<?php
declare(strict_types=1);

namespace GIS\Bitbucket;

use Exception;
use GIS\Badges;
use GIS\Http\HttpConnector;
use GIS\Response\ExceptionResponse;
use GIS\ScoreInterface;
use GuzzleHttp\Client;

/**
 * Class BitbucketScore
 * @package GIS\Bitbucket
 */
class BitbucketScore implements ScoreInterface
{

    /**
     * @param array $config
     * @return array
     * @throws Exception
     */
    public function run(array $config): array
    {
        $httpConnector = new HttpConnector(new Client([
            "auth" => [$config["username"], $config["password"]]
        ]));
        try {
            $user = new BitbucketUser($httpConnector);
        } catch (Exception $exception) {
            die((new ExceptionResponse($exception->getMessage()))->jsonSerialize());
        }
        $repositories = new Repositories($httpConnector, $user->getRepositories());
        $commits = new Commits($httpConnector, $repositories->getCommits(), $user->getUsername());
        $pullRequests = new PullRequests($httpConnector, $repositories->getPullRequests(), $user->getUsername());
        return [
            'commits'       => $commits->getCount() * Badges::COMMIT,
            'pull_requests' => $pullRequests->getCount() * Badges::PULL_REQUEST
        ];
    }

}