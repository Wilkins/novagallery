<?php

$album = $album ?? '';

Page::title(Synology::getTitle($album));
Page::metaTitle(Page::title() . ' | ' . Site::config('siteName'));

if (Site::config('sortImages')) {
    $order = Site::config('sortImages');
}

// 404 if album doesn't exists
if (!Synology::fileExists($album)) {
    Template::render('404');
    exit;
}

$gallery = new Gallery(IMAGES_DIR, $album);
$parentPage = $gallery->parentAlbum($album);
if ($parentPage) {
    $parentPage = 'album/' . $parentPage;
}

$favorites = Metadata::getKey($album, Metadata::FAVORITES_KEY);

Page::addData('gallery', $gallery);
Page::addData('order', $order);
Page::addData('album', isset($album) ? $album : '');
Page::addData('parentPage', $parentPage);
Page::addData('favorites', $favorites);


Template::render('album');
