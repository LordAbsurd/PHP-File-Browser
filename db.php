<?php

require_once(ABSPATH."class.simpleDB.php");
require_once(ABSPATH."class.simpleMysqli.php");

$_SQL;


function SQLSide()
{
    global $_SQL;
    $settings = array(
        'server' => DB_HOST,
        'username' => DB_USER,
        'password' => DB_PASSWORD,
        'db' => DB_NAME,
        'port' => DB_PORT,
        'charset' => DB_CHARSET,
    );
    try {
        $_SQL = new simpleMysqli($settings);
    } catch (Exception $e) {
        printf("Connection error: %s\n", $e->getMessage());
        exit();
    }
}

function quote_smart($value)
{
    GLOBAL $_SQL;
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    if (!is_numeric($value)) {
        $value = mysqli_real_escape_string($_SQL->_getObject(), $value);
    }
    return $value;
}

function AddFile($filename, $description)
{
    global $_SQL;
    if (!isFileInDB($filename))
    {
        return $query = $_SQL->insert('INSERT INTO files (filename, description) VALUES ("'.quote_smart($filename).'","'. quote_smart($description).'")');
    }
    else
    {
        return SetDescription($filename, $description);
    }
}

function SetDescription($filename, $description)
{
    global $_SQL;
    if (isFileInDB($filename))
    {
        return $query = $_SQL->update('UPDATE files SET description="'.quote_smart($description).'"
     WHERE filename="' . quote_smart($filename) . '"');
    }
    else
    {
        return AddFile($filename, $description);
    }
}

function isFileInDB($filename)
{
    global $_SQL;
    $query = $_SQL->select('SELECT * FROM files WHERE filename="' . quote_smart($filename) . '"');
    if (!empty($query)) {
        return true;
    }
    return false;
}

function GetOptions($option)
{
    global $_SQL;

    $query = $_SQL->select('SELECT * FROM options WHERE option_name="' . quote_smart($option) . '"');
    if (!empty($query)) {
        $pidquery = $query[0];
        if ($pidquery['option_value'] != null) {
            return $pidquery['option_value'];
        }
    }
    return null;
}

function SetOption($option, $value)
{
    global $_SQL;
    return $query = $_SQL->update('UPDATE options SET option_value="'.quote_smart($value).'"
     WHERE option_name="' . quote_smart($option) . '"');
}

function SetForbiddenFolders($folders)
{
    SetOption("forbidden_folders", $folders);
}

function SetForbiddenExts($extensions)
{
    SetOption("forbidden_exts", $extensions);
}

function SetDefaultDirectory($directory)
{
    SetOption("directory", $directory);
}

function SetPageTitle($title)
{
    SetOption("title", $title);
}

function SetPageHeader($header)
{
    SetOption("header", $header);
}

function SetPreviewState($bool)
{
    SetOption("show_preview", $bool);
}

function SetPreviewWidth($width)
{
    SetOption("preview_width", $width);
}

function SetPreviewHeight($height)
{
    SetOption("preview_height", $height);
}

function SetForbiddenPrefix($prefix)
{
    SetOption("forbidden_prefix", $prefix);
}

function GetDescription($filename)
{
    global $_SQL;

    $query = $_SQL->select('SELECT * FROM files WHERE filename="' . quote_smart($filename) . '"');
    if (!empty($query)) {

        $pidquery = $query[0];
        if ($pidquery['description'] != null) {
            return $pidquery['description'];
        }
    }
    return "";
}

function GetFiles()
{
    global $_SQL;

    $query = $_SQL->select('SELECT * FROM files');
    return $query;
}

//TODO: Maybe I should add a check for writable
//is_writable() function is good for that
function RemoveFile($file)
{
    global $_SQL;
    unlink(realpath(ABSPATH.GetOptions("directory").DIRECTORY_SEPARATOR.$file));
    return $query = $_SQL->delete('DELETE FROM files WHERE filename="'.$file.'"');
}

SQLSide();