<?php

use FoodVendorSearchCli\EBNFReader;
use FoodVendorSearchCli\FoodItem;
use FoodVendorSearchCli\Vendor;
use FoodVendorSearchCli\Vendors;

/**
 * Class EBNFReaderTest
 */
class EBNFReaderTest extends \Codeception\Test\Unit
{

    /**
     * @var $mEBNFReader EBNFReader
     */
    private $mEBNFReader;
    /**
     * @var $mVendors Vendors
     */
    private $mVendors;

    /**
     * @var FILE_PATH string The File path to test input file
     */
    private const FILE_PATH = __DIR__ . '/resources/example-input.txt';


    /**
     * Create a Fresh instance before test of EBNFReader and Vendors
     */
    protected function _before()
    {
        $this->mEBNFReader = new EBNFReader();
        $this->mVendors = Vendors::getInstance();
    }

    /**
     * A factory to create a Vendors Test object
     *
     * @return Vendors
     */
    private function vendorsTestDataFactory(): Vendors
    {
        $vendors = [
            ['Grain and Leaf', 'E32NY', 100, 'foodItems' => [
                ['Grain salad', ['nuts'], 12]
            ]
            ],
            ['Wholegrains', 'SW34DA', 20, 'foodItems' => [
                ['The Classic', ['gluten'], 24]
            ]
            ],
            ['Ghana Kitchen', 'NW42QA', 40, 'foodItems' => [
                ['Premium meat selection', [''], 36],
                ['Breakfast', ['gluten', 'eggs'], 12]
            ]
            ],
            ['Well Kneaded', 'EC32BA', 150, 'foodItems' => [
                ['Full English breakfast', ['gluten'], 24]
            ]
            ],
        ];

        $testDataVendors = new Vendors();
        foreach ($vendors as $vendor) {
            $oVendor = new Vendor($vendor[0], $vendor[1], $vendor[2]);
            foreach ($vendor['foodItems'] as $foodItem) {
                $oVendor->addFoodItem(new FoodItem(...$foodItem));
            }
            $testDataVendors->addVendor($oVendor);
        }

        return $testDataVendors;
    }

    /**
     * Test to check the file input file is parsed correctly
     */
    public function testParseFile(): void
    {
        $this->mEBNFReader->parseFile(self::FILE_PATH);
        $testDataVendors = $this->vendorsTestDataFactory();
        $vendorsActual = $this->mVendors->getVendors();
        $vendorsExpected = $testDataVendors->getVendors();
        $this->assertEquals($vendorsExpected, $vendorsActual);
    }
}