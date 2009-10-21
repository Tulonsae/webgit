<?php

$config['base_path'] = realpath(dirname(__FILE__) . '/../');
$config['include_path'] = $config['base_path'] . '/include';

$default_config = array(
  'project_root'  => "/var/cache/git",
  'site_name'     => 'WebGIT',
  'list'          => false,
  'export_ok'     => false,
  'strict_export' => false,
  'base_url'      => 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . '/',
  'template_path' => $config['base_path'] . '/templates',
  'gzip_output'   => false,
  'caching'       => false,
  'cache_path'    => $config['base_path'] .'/cache',
  'max_history'   => 10,
  'relative_dates'=> true,
);

require($config['include_path'] . '/functions.php');
require($config['include_path'] . '/template.class.php');
require($config['include_path'] . '/repository.class.php');
require($config['include_path'] . '/project.class.php');
require($config['include_path'] . '/glip/lib/glip.php');

$config = array_merge($config, $default_config);

$local_config = array();
include($config['include_path'] . '/local.inc');
$config = array_merge($config, $local_config);

