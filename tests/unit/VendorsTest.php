<?php

use FoodVendorSearchCli\CommandLineOptions;
use FoodVendorSearchCli\FoodItem;
use FoodVendorSearchCli\Vendor;
use FoodVendorSearchCli\Vendors;

/**
 * Class VendorsTest
 */
class VendorsTest extends \Codeception\Test\Unit
{
    /**
     * @var $mVendors Vendors
     */
    private $mVendors;

    /**
     * @var $commandLineOptions CommandLineOptions
     */
    private $commandLineOptions;

    /**
     * Populate the $commandLineOptions and $mVendors instance with data for testing
     *
     * @throws Exception
     */
    protected function _before(): void
    {
        $this->populateDefaultData();
        $this->commandLineOptions = [
            CommandLineOptions::FILE_NAME_LONG_OPTION => 'tests/unit/resources/example-input.txt',
            CommandLineOptions::DAY_LONG_OPTION => (new \DateTime('tomorrow'))->format('d/m/y'),
            CommandLineOptions::TIME_LONG_OPTION => '11:00',
            CommandLineOptions::LOCATION_LONG_OPTION => 'E32NY',
            CommandLineOptions::COVERS_LONG_OPTION => '2'
        ];
    }

    /**
     * Populate default data from Vendor instance
     */
    private function populateDefaultData(): void
    {
        $vendors = [
            ['Grain and Leaf', 'E32NY', 100, 'foodItem' => ['Grain salad', ['nuts'], '12']],
            ['Wholegrains', 'SW34DA', 20, 'foodItem' => ['The Classic', ['gluten'], '24']]
        ];
        $this->mVendors = new Vendors();
        foreach ($vendors as $vendor) {
            $oVendor = new Vendor($vendor[0], $vendor[1], $vendor[2]);
            $oVendor->addFoodItem(new FoodItem(...$vendor['foodItem']));
            $this->mVendors->addVendor($oVendor);
        }
    }

    /**
     * Test the findFoodItem Method
     */
    public function testFindFoodItems(): void
    {
        $actualFoodItemsFound = $this->mVendors->findFoodItems($this->commandLineOptions);
        $expectedFoodItemsFound = ["Grain salad;nuts"];
        $this->assertEquals($expectedFoodItemsFound, $actualFoodItemsFound);
    }
}