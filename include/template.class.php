<?php

class Template {

  private $output = '';
  private $data = array();
  private $cacheKey = '';
  private $expiry = 3600;
  private $config;

  public function __construct($template, $key, $data) {
    $this->cacheKey = $key;
    $this->config = &$GLOBALS['config'];
    if($this->config['caching'] && $this->validCache()){
      $this->output = $this->readCache();
    } else {
      $this->data = $data;
      $this->output = $this->loadTemplate('header');
      $this->output .= $this->loadTemplate($template);
      $this->output .= $this->loadTemplate('footer');
      $this->processTemplate();
      
      if($this->config['caching']){
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
      $file = $this->config['template_path'] . '/' . $file;
    }
    return file_get_contents($file.'.phtml');
  }

  private function cacheFile(){
    return $this->config['cache_path'] . '/' . $this->cacheKey . '.cache.txt';
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
    if($this->config['gzip_output'] && strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip') === true){
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

  // TODO
  public function link($query) {
    $ary = split_query_string($query);
    foreach($ary as $k => $v){
      $url[] = $k.'='.$v;
    }
    return '?'.implode(';',$url);
  }

  public function date($date,$abs=false) {
    if(!$abs && $this->config['relative_dates']) {
      return $this->relativeDate($date);
    } else {
      return strftime('%Y-%m-%d %H:%M', $date);
    }
  }

  private function plural($num) {
    if ($num != 1)
      return "s";
  }

  private function relativeDate($date) {
    if($date instanceof String) {
      $date = strtotime($date);
    }
    $diff = time() - $date;
    //if ($diff<60)
    //  return $diff . " second" . $this->plural($diff) . " ago";
    $diff = round($diff/60);
    if ($diff<60)
      return $diff . " minute" . $this->plural($diff) . " ago";
    $diff = round($diff/60);
    if ($diff<24)
      return $diff . " hour" . $this->plural($diff) . " ago";
    $diff = round($diff/24);
    if ($diff<31)
      return $diff . " day" . $this->plural($diff) . " ago";
    //$diff = round($diff/7);
    //if ($diff<4)
    //  return $diff . " week" . $this->plural($diff) . " ago";
    $diff = round($diff/31);
    if ($diff<12)
      return $diff . " month" . $this->plural($diff) . " ago";
    return "on " . date("Y M D", $date);
  }

}
