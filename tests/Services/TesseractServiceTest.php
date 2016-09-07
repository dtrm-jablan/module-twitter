<?php namespace Determnie\Module\Elastic\Tests\Services;
use Determine\Library\Utility\Disk;
use Determine\Library\Utility\Enums\GlobFlags;
use Determine\Library\Utility\Tests\TestCase;

/**
 * Tests the tesseract service methods
 */
class TesseractServiceTest extends TestCase
{

    /**
     * @covers \Determine\Module\Elastic\Services\TesseractService::__construct
     * @covers \Determine\Module\Elastic\Services\TesseractService::scan
     * @covers \Determine\Module\Elastic\Services\TesseractService::convertFile
     * @covers \Determine\Module\Elastic\Services\TesseractService::checkFile
     * @covers \Determine\Module\Elastic\Services\TesseractService::checkRuntime
     * @covers \Determine\Module\Elastic\Providers\TesseractServiceProvider::register
     */
    public function testScan()
    {
        $_filePath = __DIR__ . '/ocr-files';
        $_testPrefix = '/ocr-test.*';

        foreach (Disk::glob($_filePath . $_testPrefix, GlobFlags::GLOB_NODIR | GlobFlags::GLOB_NODOTS) as $_file) {
            $_testFile = Disk::path([$_filePath, $_file]);

            $this->assertNotEmpty(app('tesseract')->scan($_testFile));
        }
    }
}
