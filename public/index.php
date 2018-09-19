<?php
declare(strict_types=1);

use GIS\Bitbucket\BitbucketScore;
use GIS\Confluence\ConfluenceScore;
use GIS\Jira\JiraScore;
use GIS\Response\ExceptionResponse;
use GIS\Response\Response;
use GIS\ScoreFactory;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$requiredParams = [
    "bitbucket_username",
    "bitbucket_password",
    "jira_username",
    "jira_api_key",
    "jira_sub_domain",
    "confluence_username",
    "confluence_api_key",
    "confluence_sub_domain"
];

try {

    if (empty($_POST))
        throw new Exception("Cannot find user request.");
    $config = $_POST;

    $keys = array_filter($requiredParams, function ($key) use ($config) {
        if (!array_key_exists($key, array_keys($config)))
            return $key;
        return null;
    }, ARRAY_FILTER_USE_KEY);

    if (!empty($keys))
        throw new Exception("Missing next params: " . implode(", ", $keys));

    echo (new Response([
        "score" => [
            "bitbucket"  => (new ScoreFactory(BitbucketScore::class))->productionLine()
                ->run([
                    "username" => $config["bitbucket_username"],
                    "password" => $config["bitbucket_password"]
                ]),
            "jira"       => (new ScoreFactory(JiraScore::class))->productionLine()
                ->run([
                    "username"  => $config["jira_username"],
                    "apiToken"  => $config["jira_api_key"],
                    "subDomain" => $config["jira_sub_domain"]
                ]),
            "confluence" => (new ScoreFactory(ConfluenceScore::class))->productionLine()
                ->run([
                    "username"  => $config["confluence_username"],
                    "apiToken"  => $config["confluence_api_key"],
                    "subDomain" => $config["confluence_sub_domain"]
                ])
        ]
    ]))->jsonSerialize();
} catch (Exception $exception) {
    echo (new ExceptionResponse($exception->getMessage()))->jsonSerialize();
}