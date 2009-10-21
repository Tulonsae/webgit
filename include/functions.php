<?php

function get_request() {
  $default_request = array('a' => 'summary');
  $actual_request = array();
  if(isset($_SERVER['PATH_INFO'])) {
    list($null,$actual_request['p'],$actual_request['a'],$actual_request['h'],$actual_request['ext']) = explode('/', $_SERVER['PATH_INFO']);
  } else {
    if($_SERVER['QUERY_STRING'] != '') {
      $actual_request = split_query_string($_SERVER['QUERY_STRING']);
    }
  }
  return array_merge($default_request, $actual_request);
}

function split_query_string($query) {
  $qvars = explode(';', $query);
  foreach($qvars as $qvar) {
    list($k,$v) = explode('=',$qvar);
    $output[$k] = $v;
  }
  return $output;
}
