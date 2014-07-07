//Okay, I know there are many messy jquery/vanilla-js things, but I will fix it in future.

$(document).ready(function () {
    var uploadButton = document.getElementById('uploadButton');
    var fileToUpload = document.getElementById('fileToUpload');
    var saveOptionsButton = document.getElementById('saveOptionsButton');
    var saveAppButton = document.getElementById('saveAppButton');
    var directoryInput = document.getElementById('directoryInput');
    var removeButton = $('.removeButton');
    var editButton = $('.editButton');

    $('#progressDiv').hide();
    $('#forbiddenExtsInput').tagsInput({
        'defaultText': 'php,html',
        'height': '34px',
        'width': '100%'
    });
    $('#forbiddenFoldersInput').tagsInput({
        'defaultText': 'admin,src',
        'height': '34px',
        'width': '100%'
    });

    uploadButton.addEventListener('click', function () {
        uploadFile();
    });

    fileToUpload.addEventListener('change', function () {
        fileSelected();
    });

    saveOptionsButton.addEventListener('click', function () {
        if (validateDirectoryInput())
            saveOptions();
    });

    saveAppButton.addEventListener('click', function () {
        saveAppearance();
    });

    directoryInput.addEventListener('input', function () {
        validateDirectoryInput();
    });

    removeButton.click(function () {
        removeFile($(this).attr('data-button'));
    });

    editButton.click(function () {
        editFile($(this).attr('data-button'));
    });

    function removeFile(filename) {
        var fd = new FormData();
        fd.append("filename", filename);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'remove.php');
        xhr.addEventListener("load", refreshPage, false);
        xhr.send(fd);
    }

    function editFile(filename) {
        bootbox.prompt("New description for '" + filename + "'", function (description) {
            if (description !== null) {
                var fd = new FormData();
                fd.append("filename", filename);
                fd.append("description", description);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'edit.php');
                xhr.addEventListener("load", refreshPage, false);
                xhr.send(fd);
            }
        });
    }

    //TODO: Only update table, not the whole page
    function refreshPage() {
        location.reload();
    }

    function fileSelected() {
        var file = document.getElementById('fileToUpload').files[0];
        if (file) {
            var fileSize = 0;
            if (file.size > 1024 * 1024)
                fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
            else
                fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

            document.getElementById('fileInformation').innerHTML = 'Information';
            document.getElementById('fileName').innerHTML = 'Name: ' + file.name;
            document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;
            document.getElementById('fileType').innerHTML = 'Type: ' + file.type;
        }
    }

    function validateDirectoryInput() {
        if (directoryInput.value == null || /^\s/.test(directoryInput.value) || directoryInput.value.length == 0) {
            $("#directoryDiv").addClass("has-error");
            $("#directoryHelp").html("Directory name can't be empty. For root directory use '.' (dot).");
            directoryInput.focus();
            return false;
        }
        else {
            $("#directoryDiv").removeClass("has-error");
            $("#directoryHelp").html("Directory with files.");
            return true;
        }
    }

    function uploadFile() {
        var fd = new FormData();
        fd.append("file", document.getElementById('fileToUpload').files[0]);
        fd.append("description", document.getElementById('descriptionInput').value);
        fd.append("location", document.getElementById('locationInput').value);
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", uploadProgress, false);
        xhr.addEventListener("load", uploadComplete, false);
        xhr.addEventListener("error", uploadFailed, false);
        xhr.addEventListener("abort", uploadCanceled, false);
        xhr.open('POST', 'upload.php');
        xhr.send(fd);
    }

    function uploadProgress(evt) {
        $(uploadButton).addClass('disabled');
        uploadButton.innerHTML = 'Uploading...';
        if (evt.lengthComputable) {
            var percentComplete = Math.round(evt.loaded * 100 / evt.total);
            $('#progressDiv').show();
            $('#progressNumber').css('width', percentComplete.toString() + '%').attr('aria-valuenow', percentComplete.toString());
        }
    }

    function uploadComplete(evt) {
        $('#progressDiv').hide();
        $(uploadButton).removeClass('disabled');
        uploadButton.innerHTML = 'Upload';
        refreshPage();
        //alert(evt.target.responseText);
    }

    function uploadFailed(evt) {
        $('#progressDiv').hide();
        alert("There was an error attempting to upload the file.");
    }

    function uploadCanceled(evt) {
        $('#progressDiv').hide();
        alert("The upload has been canceled by the user or the browser dropped the connection.");
    }

    function optionsSaved(evt) {

    }

    function saveAppearance() {
        var fd = new FormData();
        fd.append("title", document.getElementById('pageTitleInput').value);
        fd.append("header", document.getElementById('pageHeaderInput').value);
        fd.append("show_preview", $('#showPreviewCheckBox').prop('checked'));
        fd.append("preview_height", document.getElementById('previewWidthInput').value);
        fd.append("preview_width", document.getElementById('previewHeightInput').value);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'appearance.php');
        xhr.send(fd);
    }

    function saveOptions() {
        var fd = new FormData();
        fd.append("directory", document.getElementById('directoryInput').value);
        fd.append("extensions", document.getElementById('forbiddenExtsInput').value);
        fd.append("folders", document.getElementById('forbiddenFoldersInput').value);
        fd.append("prefix", document.getElementById('forbiddenPrefixInput').value);
        var xhr = new XMLHttpRequest();
        xhr.addEventListener("load", optionsSaved, false);
        xhr.open('POST', 'options.php');
        xhr.send(fd);
    }
});