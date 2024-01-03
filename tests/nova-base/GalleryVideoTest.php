<?php

use PHPUnit\Framework\TestCase;

class GalleryVideoTest extends TestCase
{

    public function providerUrl(): array
    {
        return [
            [
                "2010/08.AOUT/21%20Mariage/mariage-Portable.m4v",
                "/code/src/galleries/2010/08.AOUT/21 Mariage/@eaDir/mariage-Portable.m4v/SYNOPHOTO_FILM_H264.mp4"
            ],
            [
                "2016/11.NOVEMBRE/03/IMG_7947.MOV",
                "/code/src/galleries/2016/11.NOVEMBRE/03/@eaDir/IMG_7947.MOV/SYNOPHOTO_FILM_H264.mp4"
            ],
            [
                "2016/12.DECEMBRE/20/fete%20de%20noel%202016.wmv",
                "/code/src/galleries/2016/12.DECEMBRE/20/@eaDir/fete de noel 2016.wmv/SYNOPHOTO_FILM_M.mov"
            ],
            [
                "2011/06.JUIN/18%20Mariage%20aude%20gr%C3%A9goire/achausson_videos/00001.MTS.avi",
                "/code/src/galleries/2011/06.JUIN/18 Mariage aude grégoire/achausson_videos/@eaDir/00001.MTS.avi/SYNOPHOTO_FILM_M.mov"
            ],
            [
                "2010/07.JUILLET/31%20EVJF%20EVG/03%20EVG/bioman1-092015-05082010.avi",
                "/code/src/galleries/2010/07.JUILLET/31 EVJF EVG/03 EVG/@eaDir/bioman1-092015-05082010.avi/SYNOPHOTO_FILM_M.mov"
            ],
            [
                "2008/11.NOVEMBRE/28.10-01-12%20Weekend%20Rome/rome.mov",
                "/code/src/galleries/2008/11.NOVEMBRE/28.10-01-12 Weekend Rome/@eaDir/rome.mov/SYNOPHOTO_FILM_M.mov"
            ],
            [
                "2010/08.AOUT/21%20Mariage/NOTRE%20MARIAGE%2021%20o8/FILMS%20MARIAGE/pierre/20100821151823.mpg",
                "/code/src/galleries/2010/08.AOUT/21 Mariage/NOTRE MARIAGE 21 o8/FILMS MARIAGE/pierre/@eaDir/20100821151823.mpg/SYNOPHOTO_FILM_M.mov"
            ],
        ];

    }

    /**
     * @dataProvider providerUrl
     */
    public function testGetVideo($video, $file): void
    {
        $file = str_replace(" ", "%20", $file);
        $result = GalleryVideo::getVideo($video);
        echo $video . "\n";
        echo $result . "\n";
        $this->assertStringContainsStringIgnoringCase('SYNOPHOTO_FILM', $result);
        $this->assertEquals($file, $result);
    }
}
