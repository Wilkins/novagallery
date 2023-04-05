<?php


use PHPUnit\Framework\TestCase;

class SynologyTest extends TestCase
{
    public function testUrl(): void
    {
        $album = 'Gallery/Album1/Alb=+~um12/Album é-%$/Album 123';
        $image = 'IMG_12345.jpg';
        $size = 'SM';
        $resultUrl = Synology::url($album, $image, $size);
        $expectedUrl = IMAGES_URL."/$album/@eaDir/$image/SYNOPHOTO_THUMB_SM.jpg";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testPath(): void
    {
        $album = 'Gallery/Album1/Album12';
        $image = 'IMG_12345.jpg';
        $size = 'SM';
        $resultUrl = Synology::path($album, $image, $size);
        $expectedUrl = IMAGES_DIR."/$album/@eaDir/$image/SYNOPHOTO_THUMB_SM.jpg";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testGetThumbFromUrl(): void
    {
        $album = '2013/01.JANVIER/Soirée%20Koh%20Lanta';
        $image = 'IMG_1143.JPG';
        $fullFilename = "$album/$image";
        $resultUrl = Synology::getThumbFromUrl($fullFilename);
        $expectedUrl = IMAGES_DIR."/$album/@eaDir/$image/SYNOPHOTO_THUMB_SM.jpg";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testGetAlbumFromUrl(): void
    {
        $album = '2013/01.JANVIER/Soirée%20Koh%20Lanta';
        $image = 'IMG_1143.JPG';
        $fullFilename = "$album/$image";
        $resultUrl = Synology::getAlbumFromUrl($fullFilename);
        $expectedUrl = IMAGES_DIR."/$album";
        $this->assertEquals($expectedUrl, $resultUrl);
    }

    public function testGetAlbumCoverFromUrl(): void
    {
        $album = '2013/01.JANVIER/Soirée%20Koh%20Lanta';
        $resultUrl = Synology::getAlbumCoverFromAlbum($album);
        $expectedUrl = IMAGES_DIR."/$album/.COVER.JPG";
        $this->assertEquals($expectedUrl, $resultUrl);
    }
}