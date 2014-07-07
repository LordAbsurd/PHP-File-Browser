<?php
require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(ABSPATH . "db.php");
require_once(ABSPATH . "helpers.php");

//TODO: This code is a really bullshit. I should refactor it.

$directory = GetOptions("directory");
$uploaddir = ABSPATH . $directory . DIRECTORY_SEPARATOR . $_POST['location'] . DIRECTORY_SEPARATOR;
$reallocation;

if (!file_exists($uploaddir)) {
    mkdir($uploaddir, 0777, true);
}

var_dump("DIR EXIST: ".file_exists($uploaddir));
$uploaddir = realpath($uploaddir) . DIRECTORY_SEPARATOR;
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

//wtf is this?
if (mb_substr($reallocation = remove_dot_segments($_POST['location']), -1) == '/') {
    $reallocation = rtrim($reallocation, "/");
}

var_dump("FILE EXIST: ".$uploadfile);
var_dump("TMP NAME: ".$_FILES['file']['tmp_name']);
if (!file_exists($uploadfile)) {
    var_dump('TRYING TO MOVE');
    var_dump(move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile));
    AddFile($reallocation . "/" . $_FILES['file']['name'], $_POST['description']);
} else {
    var_dump('DONT TRYING TO MOVE');
    SetDescription($reallocation . "/" . $_FILES['file']['name'], $_POST['description']);
}