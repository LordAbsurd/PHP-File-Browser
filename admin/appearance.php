<?php
require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(ABSPATH . "db.php");
SetPageHeader($_POST["header"]);
SetPageTitle($_POST["title"]);
SetPreviewState($_POST["show_preview"]);
SetPreviewWidth($_POST["preview_width"]);
SetPreviewHeight($_POST["preview_height"]);
