<?php

$title = ucwords($album);
$title = str_replace('/', ' &raquo; ', $title);

Page::title($title);
Page::metaTitle(Page::title() . ' | ' . Site::config('siteName'));

$order = 'newest';

if (Site::config('sortImages')) {
    $order = Site::config('sortImages');
}

$fileListMaxCacheAge = Site::config('fileListMaxCacheAge');
$imageCache = Site::config('imageCache');

if (isset($_GET['order'])) {
    switch ($_GET['order']) {
        case 'oldest':
            $order = 'oldest';
            break;
        case 'newest':
            $order = 'newest';
            break;
        default:
            $order = 'default';
            break;
    }
}

// Todo:
// Some protections for album name

// 404 if album doesn't exists
if (!file_exists(IMAGES_DIR . '/' . $album)) {
    Template::render('404');
    exit;
}

$gallery = new Gallery(IMAGES_DIR, $album);
$parentPage = $gallery->parentAlbum($album);
if ($parentPage) {
    $parentPage = 'album/' . $parentPage;
}

$favorites = Synology::getMetadata($album, Synology::FAVORITES_KEY);

Page::addData('gallery', $gallery);
Page::addData('order', $order);
Page::addData('album', $album);
Page::addData('parentPage', $parentPage);
Page::addData('favorites', $favorites);


Template::render('album');
