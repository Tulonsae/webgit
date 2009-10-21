<?php

class Repository {

  private $path = '';
  private $projects = array();

  public function __construct() {
    $this->config = $GLOBALS['config'];
    $this->path = $this->config['project_root'];
    foreach(array_diff(scandir($this->path), array('.','..')) as $name) {
      $this->projects[$name] = new Project($name);
    }
  }

  public function getProjectList() {
    return $this->projects;
  }

  public function getProject($name) {
    return $this->projects[$name];
  }

}
