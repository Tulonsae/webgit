<?php

error_reporting(E_ALL);

require(dirname(__FILE__) . './../include/config.php');

$payload = array('config' => $config);
$repository = new Repository();

// parse the url
$payload['request'] = get_request();

if(isset($payload['request']['p'])) {
  $project = $repository->getProject($payload['request']['p']);

  switch($payload['request']['a']) {
  case 'shortlog':
    $payload['history'] = $project->getHistory();
    $payload['project'] = $project;
    $template = new Template('shortlog',$payload['request']['p'],$payload);
    break;
  default:
    $payload['history'] = $project->getHistory();
    $payload['project'] = $project;
    $template = new Template('summary',$payload['request']['p'],$payload);
  }
} else {
  $payload['projects'] = $repository->getProjectList();
  $template = new Template('repository','repository',$payload);
}
echo $template->getHTML();
