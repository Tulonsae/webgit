<?php

function get_project_info($path) {
  global $config;

  $project = null;

  // valid repo TODO
  if(file_exists($path . '/HEAD')){

    if(project_is_available($path)) {
      if(project_is_visible($path)) {
        $project['visible'] = true;
      } else {
        $project['visible'] = false;
      }
      $project['git'] = new Git($path);
      $project['name'] = basename($path);
      if(file_exists($path . '/description')){
        $project['description'] = file_get_contents($path . '/description');
      }
    }
  }
  return $project;
}

function project_is_visible($path) {
  global $config;

  if(!project_is_available($path) || 
    ($config['export_ok'] && !file_exists($path . '/git-daemon-export-ok'))) {
      return false;
    }
  return true;
}

function project_is_available($path) {
  global $config;

  if($config['strict_export'] && !file_exists($path . '/git-daemon-export-ok')) {
    return false;
  }
  return true;
}

function get_project_list() {
  global $config;

  $projects = array();

  if(isset($config['repo_list'])) {
  } else {
    foreach(array_diff(scandir($config['project_root']), array('.','..')) as $project_dir) {
      $projects[] = $project_dir;
    }
  }
  return $project;
}
