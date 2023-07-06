<?php

/**
*@package request
*/

include_once 'head.php';
include_once "config/config.php";
include_once $config[lang];
session_start();
session_destroy();
print('<meta HTTP-EQUIV="REFRESH" content="0; url=index.php?message='.$lang[Signout].'&&class=message">');
include_once 'foot.php';
?>
