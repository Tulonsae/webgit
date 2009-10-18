<?php

require(realpath(dirname(__FILE__) . '/../config/default.inc'));

$local_config = array();
include(realpath(dirname(__FILE__) . '/../config/local.inc'));

$config = array_merge($default_config, $local_config);

require('setup.php');

?>
