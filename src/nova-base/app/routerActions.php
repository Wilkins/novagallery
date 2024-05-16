<?php

defined("NOVA") or die();

Router::add('/favorite/(.*)', static function($var1) {
  require 'auth.php';
  $favorite = rawurldecode($var1);
  require 'actions/favorite.php';
});

Router::add('/cover/(.*)', static function($var1) {
  require 'auth.php';
  $cover = rawurldecode($var1);
  require 'actions/cover.php';
});

Router::add('/trash/(.*)', static function($var1) {
    require 'auth.php';
    $trash = rawurldecode($var1);
    require 'actions/trash.php';
});

Router::add('/download/(.*)', static function($var1) {
    require 'auth.php';
    $download = rawurldecode($var1);
    require 'actions/download.php';
});

Router::add('/rotateleft/(.*)', static function($var1) {
    require 'auth.php';
    $rotateleft = rawurldecode($var1);
    require 'actions/rotateleft.php';
});

Router::add('/rotateright/(.*)', static function($var1) {
    require 'auth.php';
    $rotateright = rawurldecode($var1);
    require 'actions/rotateright.php';
});

Router::add('/deletealbum/(.*)', static function($var1) {
    require 'auth.php';
    $album = rawurldecode($var1);
    require 'actions/deletealbum.php';
});

Router::add('/moveto-houra/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'HOURA';
    require 'actions/moveto.php';
});

Router::add('/moveto-ugap/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'UGAP';
    require 'actions/moveto.php';
});

Router::add('/moveto-snapchat/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'Snapchat';
    require 'actions/moveto.php';
});

Router::add('/moveto-bestof/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'BestOf';
    require 'actions/moveto.php';
});

Router::add('/moveto-divers/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'Divers';
    require 'actions/moveto.php';
});

Router::add('/moveto-maison/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'Maison';
    require 'actions/moveto.php';
});

Router::add('/moveto-openclassrooms/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'OpenClassrooms';
    require 'actions/moveto.php';
});

Router::add('/moveto-celeste/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'CELESTE';
    require 'actions/moveto.php';
});

Router::add('/moveto-ungi/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $destination = 'UNGI';
    require 'actions/moveto.php';
});

Router::add('/rename-folder/(.*)', static function($var1) {
    require 'auth.php';
    $folder = rawurldecode($var1);
    $newFolder = $_GET['newName'];
    require 'actions/rename-folder.php';
});

Router::add('/save-comment/(.*)', static function($var1) {
    require 'auth.php';
    $image = rawurldecode($var1);
    $comment = $_GET['comment'];
    require 'actions/save-comment.php';
});

Router::add('/mode/(.*)', static function($var1) {
    require 'auth.php';
    $mode = rawurldecode($var1);
    require 'actions/mode.php';
});
