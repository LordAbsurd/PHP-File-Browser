<?php

/***************************************************************************
 *
 *             Unnamed Simple File Browser
 *
 *             Author : Vladislav 'Sn' Kovalev
 *
 *             Homepage: me.snouwer.ru
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This is free software and it's distributed under GPL Licence.
 *
 *   Unnamed Simple File Browser has NO WARRANTY and when you use it,
 *   the author is not responsible
 *   for how it works (or doesn't).
 *
 *   The icon images are designed by Mark James (http://www.famfamfam.com)
 *   and distributed under the Creative Commons Attribution 3.0 License.
 *
 *   Some code was taken from
 *   css-tricks.com/snippets/php/display-styled-directory-contents/
 *   with permissions.
 ***************************************************************************/

require_once("config.php");
require_once(ABSPATH."db.php");
require_once(ABSPATH."helpers.php");

setlocale(LC_ALL, 'ru_RU.UTF8');
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
header('Content-type: text/html; charset=utf-8');

$_RAWDIRECTORY = GetOptions("directory");
$_RELDIRECTORY = "";

if (!file_exists(ABSPATH.$_RAWDIRECTORY)) {
    mkdir(ABSPATH.$_RAWDIRECTORY, 0777, true);
}

if (!isset($_GET['dir']) && empty($_GET['dir']))
{
    $_DIRECTORY = realpath(ABSPATH.$_RAWDIRECTORY).DIRECTORY_SEPARATOR;
}
else
{
    $dir = realpath(ABSPATH.$_RAWDIRECTORY.DIRECTORY_SEPARATOR.$_GET['dir']);
    if (strpos($dir = realpath(ABSPATH.$_RAWDIRECTORY.DIRECTORY_SEPARATOR.$_GET['dir']), ABSPATH) === 0) {
        $_DIRECTORY = $dir.DIRECTORY_SEPARATOR;
        $_RELDIRECTORY = $_GET['dir'];
    } else {
        $_DIRECTORY = realpath(ABSPATH.$_RAWDIRECTORY);
    }
}

$forbiddenExts = explode(",", GetOptions("forbidden_exts"));
$forbiddenFolders = explode(",", GetOptions("forbidden_folders"));
$forbiddenPrefix = GetOptions("forbidden_prefix");
$pageTitle = GetOptions("title");
$pageHeader = GetOptions("header");
$showPreview = GetOptions("show_preview");
$previewWidth = GetOptions("preview_width");
$previewHeight = GetOptions("preview_height");
$dirArray = [];
$filesArray = [];

$phpToJsVars = [
    'show_preview' => $showPreview,
    'preview_width' => $previewWidth,
    'preview_height' => $previewHeight,
];

$myDirectory = opendir($_DIRECTORY);

// Gets each entry
while ($entryName = readdir($myDirectory)) {
    if(is_dir($_DIRECTORY.$entryName)) {
        if (!in_array($entryName,$forbiddenFolders)) {
            $dirArray[] = $entryName;
        }
    }
    else {
        $exts = pathinfo($entryName, PATHINFO_EXTENSION);
        if (!in_array($exts, $forbiddenExts)) {
            $filesArray[] = $entryName;
        }
    }
}

// Closes directory
closedir($myDirectory);

//Sorting
sort($dirArray);
sort($filesArray);
$elementsArray = array_merge($dirArray, $filesArray);

// Counts elements in array
$indexCount = count($elementsArray);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./favicon.ico">
    <title><?php echo $pageTitle ?></title>

    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/style.css">

    <script src="./js/jquery.min.js"></script>
    <script src="./js/sorttable.js"></script>
    <script type="text/javascript">
        var phpVars = {
            <?php
              foreach ($phpToJsVars as $key => $value) {
                echo '  ' . $key . ': ' . '"' . $value . '",' . "\n";
              }
            ?>
        };
    </script>
    <script src="./js/script.js"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?php echo $pageHeader ?></h1>
                <ol class="breadcrumb">
				<?php
				$allDirs = "";
				if (!isset($_GET['dir']) && empty($_GET['dir'])) {
    				echo "<li class='active'><a href='?dir=$allDirs'>Root</a></li>";
				} else {
    				echo "<li><a href='?dir=$allDirs'>Root</a></li>";
    				$curDirs = explode("/", $_GET['dir']);

    				for($index = 0; $index < count($curDirs)-1; $index++) {
        				$allDirs .= $curDirs[$index]."/";
        				if ($index == count($curDirs)-2) {
            				echo "<li class='active'>$curDirs[$index]</li>";
      					} else {
            				echo "<li><a href='?dir=$allDirs'>$curDirs[$index]</a></li>";
        				}
					}
				}
				?>
                </ol>
                <div class="panel panel-primary">
                    <table class="table sortable table-striped ">
                        <thead>
                            <tr>
                                <th class="icon"></th>
                                <th>Filename</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Date Modified</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Loops through the array of files
                        for ($index = 0; $index < $indexCount; $index++) {
                            // Decides if hidden files should be displayed, based on query above.
                            if (substr("$elementsArray[$index]", 0, strlen($forbiddenPrefix)) != $forbiddenPrefix ||
                            strlen($forbiddenPrefix) == 0) {

                                // Resets Variables
                                $favicon = "";
                                $class = "file";

                                // Gets File Names
                                $name = $elementsArray[$index];
                                $namehref = $elementsArray[$index];

                                // Gets Date Modified
                                $modtime = date("M j Y g:i A", filemtime($_DIRECTORY.$elementsArray[$index]));
                                $timekey = date("YmdHis", filemtime($_DIRECTORY.$elementsArray[$index]));


                                // Separates directories, and performs operations on those directories
                                if (is_dir($_DIRECTORY.$elementsArray[$index])) {
                                    $extension = "&lt;Directory&gt;";
                                    $extensionImg = DIRS_IMAGE;
                                    $size = "&lt;Directory&gt;";
                                    $sizekey = "0";
                                    $class = "dir";

                                    // Cleans up . and .. directories
                                    if ($name == ".") {
                                        $name = ". (Current Directory)";
                                        $extension = "&lt;System Dir&gt;";
                                    }
                                    if ($name == "..") {
                                        $name = ".. (Parent Directory)";
                                        $extension = "&lt;System Dir&gt;";
                                    }

                                    $cleanDir = remove_dot_segments($_RELDIRECTORY.$namehref."/");
                                    if ($cleanDir == "/") {
                                        $cleanDir = "";
                                    }

                                    echo "
                                    <tr class='$class'>
                                        <td><img src='$extensionImg' /></td>
                                        <td><a href='?dir=$cleanDir' class='name'>$name</a></td>
                                        <td>$extension</td>
                                        <td sorttable_customkey='$sizekey'>$size</td>
                                        <td sorttable_customkey='$timekey'>$modtime</td>
                                        <td></td>
                                    </tr>";
                                } // File-only operations
                                else {
                                    // Gets file extension
                                    $extension = pathinfo($_DIRECTORY.$elementsArray[$index], PATHINFO_EXTENSION);
                                    $extensionDescr = "";
                                    $extensionImg = FILES_IMAGE;
                                    // Prettifies file type
                                    switch ($extension) {
                                        case "png":
                                            $extensionDescr = "PNG Image";
                                            $extensionImg = IMAGES_IMAGE;
                                            break;
                                        case "jpg":
                                            $extensionDescr = "JPEG Image";
                                            $extensionImg = IMAGES_IMAGE;
                                            break;
                                        case "jpeg":
                                            $extensionDescr = "JPEG Image";
                                            $extensionImg = IMAGES_IMAGE;
                                            break;
                                        case "svg":
                                            $extensionDescr = "SVG Image";
                                            $extensionImg = IMAGES_IMAGE;
                                            break;
                                        case "gif":
                                            $extensionDescr = "GIF Image";
                                            $extensionImg = IMAGES_IMAGE;
                                            break;
                                        case "ico":
                                            $extensionDescr = "Windows Icon";
                                            $extensionImg = IMAGES_IMAGE;
                                            break;

                                        case "txt":
                                            $extensionDescr = "Text File";
                                            $extensionImg = FILES_IMAGE;
                                            break;
                                        case "log":
                                            $extensionDescr = "Log File";
                                            $extensionImg = FILES_IMAGE;
                                            break;
                                        case "htm":
                                            $extensionDescr = "HTML File";
                                            $extensionImg = WEB_IMAGE;
                                            break;
                                        case "html":
                                            $extensionDescr = "HTML File";
                                            $extensionImg = WEB_IMAGE;
                                            break;
                                        case "xhtml":
                                            $extensionDescr = "HTML File";
                                            $extensionImg = WEB_IMAGE;
                                            break;
                                        case "shtml":
                                            $extensionDescr = "HTML File";
                                            $extensionImg = WEB_IMAGE;
                                            break;
                                        case "php":
                                            $extensionDescr = "PHP Script";
                                            $extensionImg = WEB_IMAGE;
                                            break;
                                        case "js":
                                            $extensionDescr = "Javascript File";
                                            $extensionImg = SCRIPTS_IMAGE;
                                            break;
                                        case "css":
                                            $extensionDescr = "Stylesheet";
                                            $extensionImg = CSS_IMAGE;
                                            break;

                                        case "pdf":
                                            $extensionDescr = "PDF Document";
                                            $extensionImg = FILES_IMAGE;
                                            break;
                                        case "xls":
                                            $extensionDescr = "Spreadsheet";
                                            $extensionImg = DOCS_IMAGE;
                                            break;
                                        case "xlsx":
                                            $extensionDescr = "Spreadsheet";
                                            $extensionImg = DOCS_IMAGE;
                                            break;
                                        case "doc":
                                            $extensionDescr = "Microsoft Word Document";
                                            $extensionImg = DOCS_IMAGE;
                                            break;
                                        case "docx":
                                            $extensionDescr = "Microsoft Word Document";
                                            $extensionImg = DOCS_IMAGE;
                                            break;

                                        case "zip":
                                            $extensionDescr = "ZIP Archive";
                                            $extensionImg = ZIPS_IMAGE;
                                            break;
                                        case "htaccess":
                                            $extensionDescr = "Apache Config File";
                                            $extensionImg = FILES_IMAGE;
                                            break;
                                        case "exe":
                                            $extensionDescr = "Windows Executable";
                                            $extensionImg = FILES_IMAGE;
                                            break;

                                        default:
                                            if ($extension != "") {
                                                $extensionDescr = strtoupper($extension) . " File";
                                            } else {
                                                $extensionDescr = "Unknown";
                                            }
                                            break;
                                    }

                                    // Gets and cleans up file size
                                    $size = pretty_filesize($_DIRECTORY.$elementsArray[$index]);
                                    $sizekey = filesize($_DIRECTORY.$elementsArray[$index]);

                                    $description = GetDescription($_RELDIRECTORY.$name);
                                    echo "
                                    <tr class='$class'>
                                        <td><img src='$extensionImg' /></td>
                                        <td><a data-extension='$extensionDescr' href='$_RAWDIRECTORY/$_RELDIRECTORY$namehref' $favicon class='name'>$name</a></td>
                                        <td>$extensionDescr</td>
                                        <td sorttable_customkey='$sizekey'>$size</td>
                                        <td sorttable_customkey='$timekey'>$modtime</td>
                                        <td>$description</td>
                                    </tr>";
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="footer text-center">Powered by <a href="http://github.com/SnoUweR/php-file-browser"><strong>Unnamed Simple File Browser<strong></a></div>
        <img id="thumbnail" style="display:none" src="" />
    </div>
</body>
</html>
