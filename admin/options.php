<?php
require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(ABSPATH . "db.php");

SetDefaultDirectory($_POST['directory']);
SetForbiddenExts($_POST['extensions']);
SetForbiddenFolders($_POST['folders']);
SetForbiddenPrefix($_POST['prefix']);

if (!file_exists(ABSPATH . $_POST['directory'])) {
    mkdir(ABSPATH . $_POST['directory'], 0777, true);
}
