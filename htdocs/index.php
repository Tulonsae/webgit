<?php

error_reporting(E_ALL ^ E_NOTICE);

require(dirname(__FILE__) . './../include/config.php');

$payload = array('config' => $config);

// parse the url
$payload['request'] = get_request();

// got a project
if(isset($payload['request']['p']) && file_exists($config['project_root'] . '/' . $payload['request']['p'])){
  $payload['project'] = get_project_info($config['project_root'] . '/' . $request['p']);

}else{ // no project, summary
  $payload['projects'] = array();
  foreach(array_diff(scandir($config['project_root']), array('.','..')) as $project_dir) {
    if($project = get_project_info($config['project_root'] . '/' . $project_dir)){
      $payload['projects'][] = $project;
    }
  }
}
switch($payload['request']['a']){
case 'xxsummary':
default:
  $template = new Template('repository','repository',$payload);
}
echo $template->getHTML();
