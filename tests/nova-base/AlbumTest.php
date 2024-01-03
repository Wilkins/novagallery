<?php


use PHPUnit\Framework\TestCase;

class AlbumTest extends TestCase
{
    public function testUrl(): void
    {
        $album = 'core\Gallery/Album1/Alb=+~um12/Album é-%$/Album 123';
        $image = 'IMG_12345.jpg';
        $size = 'SM';
        $resultUrl = Album::url($album, $image, $size);
        $expectedUrl = IMAGES_URL."/$album/@eaDir/$image/SYNOPHOTO_THUMB_SM.jpg";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testPath(): void
    {
        $album = 'core\Gallery/Album1/Album12';
        $image = 'IMG_12345.jpg';
        $size = 'SM';
        $resultUrl = Album::path($album, $image, $size);
        $expectedUrl = IMAGES_DIR."/$album/@eaDir/$image/SYNOPHOTO_THUMB_SM.jpg";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testGetThumbFromUrl(): void
    {
        $album = '2013/01.JANVIER/Soirée%20Koh%20Lanta';
        $image = 'IMG_1143.JPG';
        $fullFilename = "$album/$image";
        $resultUrl = Album::getThumbFromUrl($fullFilename);
        $expectedUrl = IMAGES_DIR."/$album/@eaDir/$image/SYNOPHOTO_THUMB_SM.jpg";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testGetAlbumFromUrl(): void
    {
        $album = '2013/01.JANVIER/Soirée%20Koh%20Lanta';
        $image = 'IMG_1143.JPG';
        $fullFilename = "$album/$image";
        $resultUrl = Album::getAlbumFromUrl($fullFilename);
        $expectedUrl = IMAGES_DIR."/$album";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testGetAlbumCoverFromUrl(): void
    {
        $album = '2013/01.JANVIER/Soirée%20Koh%20Lanta';
        $resultUrl = Album::getAlbumCoverFromUrl($album);
        $expectedUrl = IMAGES_DIR."/$album/.COVER.JPG";
        $this->assertEquals($expectedUrl, $resultUrl);
    }
}
