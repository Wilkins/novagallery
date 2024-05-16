<?php

defined("NOVA") or die();

// Home
Router::add('/', static function() {
  require 'auth.php';
  require 'pages/album.php';
});

// Home
Router::add('/info', static function() {
    require 'auth.php';
    phpinfo();
});

Router::add('/galleries/(.*)/(.*)', static function($var1, $var2) {
  require 'auth.php';
  $album = rawurldecode($var1);
  $size = "SM";
  $image = rawurldecode($var2);
  require 'pages/image.php';
});

Router::add('/album/(.*)', static function($var1) {
  require 'auth.php';
  $album = rawurldecode($var1);
  require 'pages/album.php';
});

Router::add('/video/(.*)', static function($var1) {
    require 'auth.php';
    $video = rawurldecode($var1);
    require 'pages/video.php';
});

Router::add('/duplicates/(.*)', static function($var1) {
    require 'auth.php';
    $album = rawurldecode($var1);
    require 'pages/duplicates.php';
});

Router::add('/videos/(.*)', static function($var1) {
    require 'auth.php';
    $album = rawurldecode($var1);
    require 'pages/videos.php';
});

Router::add('/info/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    require 'pages/info.php';
});

Router::add('/test', static function() {
  require 'pages/test.php';
});

// Auth
Router::add('/login', static function() {
  require 'pages/login.php';
}, ['get', 'post']);

Router::add('/logout', static function() {
  require 'pages/logout.php';
});

// Error pages
Router::pathNotFound(static function(){
  Template::render('404');
});

// Run
Router::run(BASE_PATH);
