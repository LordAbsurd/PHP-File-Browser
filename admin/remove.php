<?php
require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(ABSPATH . "db.php");

RemoveFile($_POST['filename']);