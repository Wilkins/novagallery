<?php

Page::title(Synology::getTitle($album, "Videos"));
Page::metaTitle(Page::title() . ' | ' . Site::config('siteName'));

// 404 if album doesn't exists
if (!Synology::fileExists($album)) {
    Template::render('404');
    exit;
}

$gallery = new GalleryVideo(IMAGES_DIR, $album);
$parentPage = $gallery->parentAlbum($album);
if ($parentPage) {
    $parentPage = 'album/' . $parentPage;
}

$favorites = Metadata::getKey($album, Metadata::FAVORITES_KEY);

Page::addData('gallery', $gallery);
Page::addData('album', $album);
Page::addData('parentPage', $parentPage);
Page::addData('favorites', $favorites);


Template::render('videos');
