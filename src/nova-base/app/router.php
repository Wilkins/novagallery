<?php defined("NOVA") or die(); 

// Home
Router::add('/', function() {
  require 'auth.php';
  require 'pages/home.php';
}, 'get');


// Gallery
Router::add('/galleries/(.*)/cache/(.*)/(.*)', function($var1, $var2, $var3) {
  require 'auth.php';
 // echo "Gallery Cache !<br>\n ";
  $album = rawurldecode($var1);
  $size = rawurldecode($var2);
  $image = rawurldecode($var3);
  require 'image.php';
}, 'get');

// Gallery
Router::add('/galleries/(.*)/(.*)', function($var1, $var2) {
  require 'auth.php';
  //echo "Gallery NOCACHE ! <br>\n";
  $album = rawurldecode($var1);
  $size = "SM";
  $image = rawurldecode($var2);
  require 'image.php';
}, 'get');

Router::add('/album/(.*)', function($var1) {
  require 'auth.php';
  $album = rawurldecode($var1);
  require 'pages/album.php';
}, 'get');

Router::add('/favorite/(.*)', function($var1) {
  require 'auth.php';
  $favorite = rawurldecode($var1);
  require 'pages/favorite.php';
}, 'get');


// Auth
Router::add('/login', function() {
  require 'pages/login.php';
}, ['get', 'post']);

Router::add('/logout', function() {
  require 'pages/logout.php';
}, 'get');


// Error pages
Router::pathNotFound(function(){
  Template::render('404');
});

// Run
Router::run(BASE_PATH);
