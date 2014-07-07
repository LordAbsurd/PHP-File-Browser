<?php

define('DB_NAME', 'stuff');
define('DB_USER', 'root');
define('DB_PORT', 3306);
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');

define('FILES_IMAGE', './images/file.png');
define('IMAGES_IMAGE', './images/image.png');
define('ZIPS_IMAGE', './images/zip.png');
define('CSS_IMAGE', './images/css.png');
define('DOCS_IMAGE', './images/office.png');
define('VIDEOS_IMAGE', './images/video.png');
define('AUDIO_IMAGE', './images/sound.png');
define('WEB_IMAGE', './images/xml.png');
define('PHP_IMAGE', './images/php.png');
define('SCRIPTS_IMAGE', './images/script.png');
define('DIRS_IMAGE', './images/folder.png');

if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);