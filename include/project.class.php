<?php

class Project implements ArrayAccess {

  public $data = array();
  private $config;

  public function __construct($name) {
    $this->config = $GLOBALS['config'];
    $this->data['name'] = $name;
    $this->data['path'] = $this->config['project_root'] . '/' . $name;
    if(file_exists($this->data['path'] . '/description')){
      $this->data['description'] = file_get_contents($this->data['path'] . '/description');
    }
  }

  public function isVisible() {
    if(!$this->isAvailable() || 
      ($this->config['export_ok'] && !file_exists($this->data['path'] . '/git-daemon-export-ok'))) {
        return false;
      }
    return true;
  }

  public function isAvailable() {
    if($this->config['strict_export'] && !file_exists($this->data['path'] . '/git-daemon-export-ok')) {
      return false;
    }
    return true;
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
