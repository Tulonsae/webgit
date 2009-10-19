<?php

$default_config = array(
  'project_root'      => "/var/cache/git",
  'site_name'         => 'WebGIT',
  'list'             => false,
  'export_ok'        => false,
  'strict_export'    => false,
  'base_url'         => $_SERVER['SERVER_NAME'],
  'caching'          => true,
);

$config['base_path'] = realpath(dirname(__FILE__) . '/../');
$config['include_path'] = $config['base_path'] . '/include';

require($config['include_path'] . '/functions.php');

$config = array_merge($config, $default_config);


$local_config = array();
include($config['include_path'] . '/local.inc');
$config = array_merge($config, $local_config);

require($config['include_path'] . '/glip/lib/glip.php');
