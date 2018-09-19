<?php
declare(strict_types=1);

namespace GIS\Jira;

use Exception;
use GIS\Badges;
use GIS\Http\HttpConnector;
use GIS\ScoreInterface;
use GuzzleHttp\Client;

/**
 * Class JiraScore
 * @package GIS\Jira
 */
class JiraScore implements ScoreInterface
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
            $user = new JiraUser($httpConnector, $subDomain);
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
        $projects = new Projects($httpConnector, $subDomain);
        $comments = new Comments($httpConnector, $projects->getIssues(), $subDomain, $user->getAccountId());
        $changeLogs = new ChangeLog($httpConnector, $projects->getIssues(), $subDomain, $user->getAccountId());
        return [
            'closed_issues'    => $changeLogs->getClosedIssuesCount() * Badges::CLOSED_TASK,
            'changed_status'   => $changeLogs->getCount() * Badges::CHANGE_LOG,
            'comments_created' => $comments->getCount() * Badges::CHANGE_LOG
        ];
    }

}