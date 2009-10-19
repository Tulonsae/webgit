<?php

function get_repo_info($path) {
  global $config;

  $project = null;

  // valid repo TODO
  if(file_exists($path . '/HEAD')){
    
    $project['visible'] = true;
    // check publishing status
    if($config['export_ok']) {
      if(!file_exists($path . '/git-daemon-export-ok')) {
        if($config['strict_export']) {
          // not available
          return null;
        }
        // available but not visible
        $project['visible'] = false;
      }
    }
    $project['git'] = new Git($path);
    $project['name'] = basename($path);
    if(file_exists($path . '/description')){
      $project['description'] = file_get_contents($path . '/description');
    }
  }
  return $project;
}
