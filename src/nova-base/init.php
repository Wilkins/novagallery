<?php
/**
 * Initalize novaGallery App
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @version 1.1.1
 * @link https://novagallery.org
 **/

// define basics
define('NOVA', true);

$path = __DIR__;
$path = substr($path, 0, -10);
define('ROOT_DIR', $path);

require __DIR__.'/../../vendor/autoload.php';

Site::initialize();

// define constants
$imagesDirName = Site::config('imagesDirName');
if(is_null($imagesDirName)){
  $imagesDirName = 'galleries';
}

define('BASE_PATH', Site::basePath());

define('IMAGES_DIR', ROOT_DIR.'/'.$imagesDirName);
define('IMAGES_URL', BASE_PATH.'/'.$imagesDirName);
define('IMAGES_URL_CODE', BASE_PATH.'/code/src/'.$imagesDirName);
define('IMAGES_QUALITY', Site::config('imageQuality'));

define('THEME_DIR', 'nova-themes/'.Site::theme());
define('THEME_PATH', BASE_PATH.'/nova-themes/'.Site::theme());

Lang::initialize(Site::config('language'));

// load routes
require 'app/router.php';
