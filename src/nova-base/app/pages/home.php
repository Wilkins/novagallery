<?php

Page::title(Site::config('siteTitle'));
Page::metaTitle(Page::title() . ' | ' . Site::config('siteName'));
Page::metaDescription(Site::config('metaDescription'));

$order = 'oldest';

if (Site::config('sortAlbums')) {
    $order = Site::config('sortAlbums');
}

$fileListMaxCacheAge = Site::config('fileListMaxCacheAge');
$imageCache = Site::config('imageCache');

$gallery = new Gallery(IMAGES_DIR,"");

Page::addData('order', $order);
Page::addData('album', '');
Page::addData('gallery', $gallery);

Template::render('home');
