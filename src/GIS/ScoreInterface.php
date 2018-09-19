<?php
declare(strict_types=1);

namespace GIS;

/**
 * Interface ScoreInterface
 * @package GIS
 */
interface ScoreInterface
{

    /**
     * This method should use for running application API and returning aggregated response represented as assoc array.
     * @param array $config
     * parameters for config array:
     *      string username - mandatory for all applications.
     *      string password - mandatory for Bitbucket application.
     *      string apiToken - mandatory for Jira and Confluence.
     *      string subDomain - mandatory for Jira and Confluence.
     * @return array
     */
    public function run(array $config): array;

}