<?php
//TODO: Initialize mysql database
require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(ABSPATH . "db.php");

$directory = GetOptions("directory");
$forbiddenExts = GetOptions("forbidden_exts");
$forbiddenFolders = GetOptions("forbidden_folders");
$pageTitle = GetOptions("title");
$pageHeader = GetOptions("header");
$showPreview = GetOptions("show_preview");
$previewWidth = GetOptions("preview_width");
$previewHeight = GetOptions("preview_height");
$forbiddenPrefix = GetOptions("forbidden_prefix");
$files = GetFiles();

setlocale(LC_ALL, 'ru_RU.UTF8');
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
header('Content-type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./favicon.ico">
    <title>Admin Panel | Unnamed Simple File Browser</title>

    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/jquery.tagsinput.css"/>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/jquery.tagsinput.js"></script>

    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/bootbox.min.js"></script>
    <script src="./js/script.js"></script>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Simple File Browser
            <small>admin panel</small>
        </h1>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Files in Database</h3>
        </div>
        <div class="panel-body">
            <table class="table sortable table-striped ">
                <thead>
                <tr>
                    <th>File</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($files as $value) {

                    echo "<tr><td>" . $value['filename'] . "</td>";
                    echo "<td>" . $value['description'] . "</td>";
                    echo '
                            <td><button data-toggle="modal" data-target="#editfile" data-button="' . $value['filename'] . '"
                            class="btn btn-info btn-xs editButton" href="#">
                                <span class="glyphicon glyphicon-edit"></span>
                                Edit
                            </button>
                            <button data-button="' . $value['filename'] . '" class="btn btn-danger btn-xs removeButton">
                                <span class="glyphicon glyphicon-remove"></span>
                                Remove
                            </button></td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">File Upload</h3>
        </div>
        <div class="panel-body">
            <form id="uploadForm" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <label for="fileToUpload">Choose file to upload</label>
                    <input type="file" id="fileToUpload">
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <input type="text" class="form-control" id="descriptionInput"
                           placeholder="simple description for file">
                </div>
                <div class="form-group">
                    <label for="locationInput">Location</label>
                    <input type="text" class="form-control" id="locationInput">
                    <span
                        class="help-block">File will be uploaded to the following location. Example: dir1/subdir2</span>
                </div>
                <label id="fileInformation"></label>
                <span class="help-block" id="fileName"></span>
                <span class="help-block" id="fileSize"></span>
                <span class="help-block" id="fileType"></span>
                <button id="uploadButton" type="button" class="btn btn-default">Upload</button>
                <hr>
                <div id="progressDiv" class="progress">
                    <div id="progressNumber" class="progress-bar progress-bar-striped active" role="progressbar"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Options</h3>
        </div>
        <div class="panel-body">
            <form id="optionsForm" method="post">
                <div id="directoryDiv" class="form-group">
                    <label for="directoryInput">Default Directory</label>
                    <input type="text" class="form-control" id="directoryInput" value="<?php echo $directory ?>">
                    <span id="directoryHelp" class="help-block">Directory with files.</span>
                </div>
                <div class="form-group">
                    <label for="forbiddenExtsInput">Forbidden Extensions</label>
                    <input type="text" class="form-control" id="forbiddenExtsInput"
                           placeholder="php,html,css,etc" value="<?php echo $forbiddenExts ?>">
                    <span class="help-block">Files with these extensions will not be displayed. Comma-Separated.</span>
                </div>
                <div class="form-group">
                    <label for="forbiddenFoldersInput">Forbidden Folders</label>
                    <input type="text" class="form-control" id="forbiddenFoldersInput"
                           placeholder="admin,src" value="<?php echo $forbiddenFolders ?>">
                    <span class="help-block">These folders will not be displayed. Comma-Separated.</span>
                </div>
                <div class="form-group">
                    <label for="forbiddenPrefixInput">Forbidden Prefix</label>
                    <input type="text" class="form-control" id="forbiddenPrefixInput"
                           placeholder="." value="<?php echo $forbiddenPrefix ?>">
                    <span class="help-block">Files that begin with these symbols will be skipped.</span>
                </div>
                <button id="saveOptionsButton" type="button" class="btn btn-default">Save</button>
            </form>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Appearance</h3>
        </div>
        <div class="panel-body">
            <form id="optionsForm" method="post">
                <div class="form-group">
                    <label for="pageTitleInput">Page Title</label>
                    <input type="text" class="form-control" id="pageTitleInput" value="<?php echo $pageTitle ?>">
                </div>
                <div class="form-group">
                    <label for="pageHeaderInput">Page Header</label>
                    <input type="text" class="form-control" id="pageHeaderInput" value="<?php echo $pageHeader ?>">
                </div>
                <hr>
                <div class="checkbox">
                    <label>
                        <input id="showPreviewCheckBox" <?php if ($showPreview == 'true') {
                            echo 'checked';
                        } ?> type="checkbox"> Show preview for images
                    </label>
                </div>
                <div class="form-group">
                    <label for="previewWidthInput">Preview Width (in pixels)</label>
                    <input type="text" class="form-control" id="previewWidthInput" value="<?php echo $previewWidth ?>">
                </div>
                <div class="form-group">
                    <label for="previewHeightInput">Preview Height (in pixels)</label>
                    <input type="text" class="form-control" id="previewHeightInput"
                           value="<?php echo $previewHeight ?>">
                </div>
                <button id="saveAppButton" type="button" class="btn btn-default">Save</button>
            </form>
        </div>
    </div>
    <div class="footer text-center">Powered by <a href="http://github.com/SnoUweR/php-file-browser"><strong>Unnamed
                Simple File Browser</strong></a></div>
</div>
</body>
</html>