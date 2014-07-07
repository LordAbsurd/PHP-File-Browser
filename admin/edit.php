<?php
require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(ABSPATH . "db.php");

SetDescription($_POST['filename'], $_POST['description']);