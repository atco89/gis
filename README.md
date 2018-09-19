# Gamification Integration Script

### DESCRIPTION

We would like to integrate 3 internal systems:

- JIRA
- Confluence
- Bitbucket

with our internal gamification platform. The main purpose of this task is to motivate 
developers to execute their tasks in high quality and reward them with badgets.

We would like to integrate these actions:

#### Bitbucket
- Each commit achieves 1 point;
- Each pull request commit achieves 2 points.

#### JIRA
- Each closed task achieves 5 points;
- Each comment, new attachment, status change (open in progress, resolved, closed etc.) achieves 1 point.

#### Confluence
- Each new page achieves 10 points;
- Each content update or comment achieves 1 point.

### REQUIREMENTS

- Apache web server;
- PHP 7.1+;
- Create API token key for JIRA and Confluence account (https://confluence.atlassian.com/cloud/api-tokens-938839638.html).

### INSTALLATION

All you need to do is to clone repository on your server or local machine.

### USAGE

Make request using POST method and next query parameters (you must send all parameters):

`
POST https://yourdomain.com/api/score
- bitbucket_username [string, Bitbucket account username]
- bitbucket_password [string, Bitbucket account password]
- jira_username [string, JIRA account username]
- jira_api_key [string, JIRA account api token]
- jira_sub_domain [string, JIRA account sub domain]
- confluence_username [string, Confluence username]
- confluence_api_key [string, Confluence api token]
- confluence_sub_domain [string, Confluence sub domain]
`
