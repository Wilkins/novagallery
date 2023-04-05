<?php


use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase
{
    public function testReadDir(): void
    {
        $baseDir = __DIR__.'/../fixtures/Gallery1';
        $filesystem = new FileSystem($baseDir);
        $content = $filesystem->listDirectories();
        $expectedContent = [
            $baseDir.'/Album1',
            $baseDir.'/Album2',
        ];
        $this->assertEquals($expectedContent, $content);
    }
}