<?php

error_reporting(E_ALL ^ E_NOTICE);

require(dirname(__FILE__) . './../include/config.php');

// parse the url
// /project/object/id

// got a project
if(isset($_GET['p']) && file_exists($config['project_root'] . '/' . $_GET['p'])){
  $project = get_project_info($config['project_root'] . '/' . $_GET['p']);
  print_r($project);

}else{ // no project, summary
  $projects = array();
  foreach(array_diff(scandir($config['project_root']), array('.','..')) as $project_dir) {
    if($project = get_project_info($config['project_root'] . '/' . $project_dir)){
      $projects[] = $project;
    }
  }
  print_r($projects);
}
print_r($config);
