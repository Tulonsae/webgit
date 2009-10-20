<?php

class Template {

  private $output = '';
  private $data = array();
  private $cacheKey = '';
  private $expiry = 3600;

  public function __construct($template, $key, $data) {
    $this->cacheKey = $key;
    if($GLOBALS['config']['caching'] && $this->validCache()){
      $this->output = $this->readCache();
    } else {
      $this->data = $data;
      $this->output = $this->loadTemplate('header');
      $this->output .= $this->loadTemplate($template);
      $this->output .= $this->loadTemplate('footer');
      $this->processTemplate();
      
      if($GLOBALS['config']['caching']){
        $this->writeCache();
      }
    }
    //$this->sendEncodingHeader();
  }

  private function validCache(){
    // TODO check against date of config file
    if(file_exists($this->cacheFile()) && filemtime($this->cacheFile()) > (time() - $this->expiry)){
      return true;
    }
    return false;
  }

  private function processTemplate(){
    foreach($this->data as $k => $v) { 
      $$k = $v; 
    } 
    ob_start(); 
    eval("?>" . $this->output . "<?"); 
    $this->output = ob_get_contents(); 
    ob_end_clean();
  }

  private function loadTemplate($file) {
    if(strpos($file,'/') !== 0) {
      $file = $GLOBALS['config']['template_path'] . '/' . $file;
    }
    return file_get_contents($file.'.phtml');
  }

  private function cacheFile(){
    return $GLOBALS['config']['cache_path'] . '/' . $this->cacheKey . '.cache.txt';
  }

  private function writeCache(){
    if(!$fp = fopen($this->cacheFile(),'w')){
      throw new Exception('Error writing data to cache file: '.$this->cacheFile());
    }
    fwrite($fp, $this->getHTML());
    fclose($fp);
  }

  private function readCache(){
    if(!$cacheContents = file_get_contents($this->cacheFile())){
      throw new Exception('Error reading data from cache file: '.$this->cacheFile());
    }
    return $cacheContents;
  }

  // return overall output
  public function getHTML(){
    // return compressed output
    if($GLOBALS['config']['gzip_output'] && strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip') === true){
      ob_start();
      echo $this->output;
      // crunch (X)HTML content & compress it with gzip
      $this->output = gzencode(preg_replace("/(rn|n)/","",ob_get_contents()),9);
      ob_end_clean();
    }
    return $this->output;
  }

  // send gzip encoding http header
  public function sendEncodingHeader(){
    header('Content-Encoding: gzip');
  }
}
