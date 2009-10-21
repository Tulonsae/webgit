<?php

class Project implements ArrayAccess {

  public $data = array();
  private $path;
  private $config;
  private $git;

  public function __construct($name) {
    $this->config = &$GLOBALS['config'];
    $this->path = $this->config['project_root'] . '/' . $name;

    // fill data
    $this->data['name'] = $name;
    if(file_exists($this->path . '/description')){
      $this->data['description'] = file_get_contents($this->path . '/description');
    }
    
    $this->git = new Git($this->path);
    $this->data['head'] = $this->git['master']->getTip(true);
  }

  public function isVisible() {
    if(!$this->isAvailable() || 
      ($this->config['export_ok'] && !file_exists($this->path . '/git-daemon-export-ok'))) {
        return false;
      }
    return true;
  }

  public function isAvailable() {
    if($this->config['strict_export'] && !file_exists($this->path . '/git-daemon-export-ok')) {
      return false;
    }
    return true;
  }

  public function getHistory($branch='master') {
    $head = $this->git['master']->getTip(true);
    $commits = array();
    $history = array_reverse($head->getHistory());
    for ($i = 0; $i < min($GLOBALS['config']['max_history'], count($history)); $i++) {
      $cur = $history[$i];

      $commits[$i]['commit_id'] = $cur->getSha();
      $commits[$i]['author'] = $cur->author->name;
      $commits[$i]['email'] = $cur->author->email;
      $commits[$i]['time'] = $cur->committer->time;
      $commits[$i]['summary'] = $cur->summary;
      $commits[$i]['detail'] = $cur->detail;
    }
    return $commits;
  }

  public function offsetExists($key) {
    return array_key_exists($key, $this->data);
  }

  public function offsetGet($key) {
    return $this->data[$key];
  }

  public function offsetSet($key, $data) {
    //$this->data[$key] = $data;
    throw new Exception('Read only access');
  }

  public function offsetUnset($key) {
    throw new Exception('Read only access');
  }
}
