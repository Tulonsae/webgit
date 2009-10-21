<?php

error_reporting(E_ALL);

require(dirname(__FILE__) . './../include/config.php');

$payload = array('config' => $config);
$repository = new Repository();

// parse the url
$payload['request'] = get_request();

// got a project
if(isset($payload['request']['p']) && file_exists($config['project_root'] . '/' . $payload['request']['p'])){
  $payload['project'] = $repository->getProject($payload['request']['p']);

}else{ // no project, summary
  $payload['projects'] = $repository->getProjectList();
}

if(isset($payload['request']['p'])) {
  switch($payload['request']['a']) {
  default:
    $template = new Template('summary',$payload['request']['p'],$payload);
  }
} else {
  $template = new Template('repository','repository',$payload);
}
echo $template->getHTML();
