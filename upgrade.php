<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . "/engine/start.php" );

run_sql_script('schema/upgrades/2012021701.sql');
// Upgrade core
include($CONFIG->path . 'engine/lib/upgrades/2012021701.php');
forward(REFERER);
