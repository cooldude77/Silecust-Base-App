<?php

namespace App\Tests\Service\Product\File;

use Silecust\WebShop\Service\MasterData\Product\Image\Provider\ProductDirectoryImagePathProvider;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function PHPUnit\Framework\assertEquals;

class ProductFileDirectoryPathNamerTest extends KernelTestCase
{

    use ProductFixture ;
    public function testGetFileFullPath()
    {
        $this->createProductFixtures();
        self::bootKernel();
        $namer = new ProductDirectoryImagePathProvider(static::$kernel->getProjectDir(),static::$kernel->getContainer()->getParameter('file_storage_path'));

        $expected = static::$kernel->getProjectDir() . "/data/test/uploads/product/{$this->product1->getId()}/images/file_name.png";
        assertEquals($expected,$namer->getFullPhysicalPathForFileByName($this->product1->object(),'file_name.png'), );
    }
}