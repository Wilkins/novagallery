<?php defined("NOVA") or die(); 

// Home
Router::add('/', static function() {
  require 'auth.php';
  require 'pages/album.php';
});


// core\Gallery
Router::add('/galleries/(.*)/cache/(.*)/(.*)', static function($var1, $var2, $var3) {
  require 'auth.php';
 // echo "core\Gallery Cache !<br>\n ";
  $album = rawurldecode($var1);
  $size = rawurldecode($var2);
  $image = rawurldecode($var3);
  require 'image.php';
});

// core\Gallery
Router::add('/galleries/(.*)/(.*)', static function($var1, $var2) {
  require 'auth.php';
  //echo "core\Gallery NOCACHE ! <br>\n";
  $album = rawurldecode($var1);
  $size = "SM";
  $image = rawurldecode($var2);
  require 'image.php';
});

Router::add('/album/(.*)', static function($var1) {
  require 'auth.php';
  $album = rawurldecode($var1);
  require 'pages/album.php';
});

Router::add('/favorite/(.*)', static function($var1) {
  require 'auth.php';
  $favorite = rawurldecode($var1);
  require 'pages/favorite.php';
});

Router::add('/cover/(.*)', static function($var1) {
  require 'auth.php';
  $cover = rawurldecode($var1);
  require 'pages/cover.php';
});

Router::add('/trash/(.*)', static function($var1) {
    require 'auth.php';
    $trash = rawurldecode($var1);
    require 'pages/trash.php';
});

Router::add('/download/(.*)', static function($var1) {
    require 'auth.php';
    $download = rawurldecode($var1);
    require 'pages/download.php';
});
Router::add('/rotateleft/(.*)', static function($var1) {
    require 'auth.php';
    $rotateleft = rawurldecode($var1);
    require 'pages/rotateleft.php';
});
Router::add('/rotateright/(.*)', static function($var1) {
    require 'auth.php';
    $rotateright = rawurldecode($var1);
    require 'pages/rotateright.php';
});
Router::add('/video/(.*)', static function($var1) {
    require 'auth.php';
    $video = rawurldecode($var1);
    require 'pages/video.php';
});
Router::add('/deletealbum/(.*)', static function($var1) {
    require 'auth.php';
    $album = rawurldecode($var1);
    require 'pages/deletealbum.php';
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
Router::add('/moveto-houra/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'HOURA';
    require 'pages/moveto.php';
});
Router::add('/moveto-ugap/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'UGAP';
    require 'pages/moveto.php';
});
Router::add('/moveto-snapchat/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'Snapchat';
    require 'pages/moveto.php';
});
Router::add('/moveto-bestof/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'BestOf';
    require 'pages/moveto.php';
});
Router::add('/moveto-divers/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'Divers';
    require 'pages/moveto.php';
});
Router::add('/moveto-maison/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'Maison';
    require 'pages/moveto.php';
});
Router::add('/moveto-openclassrooms/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'OpenClassrooms';
    require 'pages/moveto.php';
});
Router::add('/moveto-celeste/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'CELESTE';
    require 'pages/moveto.php';
});
Router::add('/moveto-ungi/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'UNGI';
    require 'pages/moveto.php';
});
Router::add('/rename-folder/(.*)', static function($var1) {
    require 'auth.php';
    $folder = rawurldecode($var1);
    $newFolder = $_GET['newName'];
    require 'pages/rename-folder.php';
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
