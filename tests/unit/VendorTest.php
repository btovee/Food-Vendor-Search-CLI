<?php

use FoodVendorSearchCli\FoodItem;
use FoodVendorSearchCli\Vendor;

/**
 * Class VendorTest
 */
class VendorTest extends \Codeception\Test\Unit
{

    /**
     * A factory to create a Vendor instance
     *
     * @param string $postcode
     * @return Vendor
     */
    private function vendorFactory(string $postcode): Vendor
    {
        return new Vendor("test", $postcode, 10);
    }

    /**
     * Test withinPostcode method with valid and invalid data
     */
    public function testWithinPostcode(): void
    {
        $vendor = $this->vendorFactory("NW43QB");
        $actual = $vendor->withinPostcode("NW43QB");
        $this->assertTrue($actual);
        $vendor = $this->vendorFactory("NW43QB");
        $actual = $vendor->withinPostcode("SE16DP");
        $this->assertFalse($actual);
    }


    /**
     * Test canCoverTheNumberOfPeople method
     */
    public function testCanCoverTheNumberOfPeople(): void
    {
        $vendor = $this->vendorFactory("");
        $actual = $vendor->canCoverTheNumberOfPeople(5);
        $this->assertTrue($actual);
        $actual = $vendor->canCoverTheNumberOfPeople(11);
        $this->assertFalse($actual);
    }

    /**
     * Test withinNoticePeriod method
     * @throws Exception
     */
    public function testWithinNoticePeriod(): void
    {
        $vendor = $this->vendorFactory("");
        $vendor->addFoodItem(new FoodItem('Full English breakfast', ['gluten'], 12));
        $datetime = new DateTime('tomorrow + 1day');
        $date = $datetime->format('d/m/y');
        $time = $datetime->format('H:i');
        $foodItemGenerator = $vendor->withinNoticePeriod($date, $time);
        $actualFoodItems = [];
        foreach ($foodItemGenerator as $foodItem) {
            $actualFoodItems[] = $foodItem;
        }
        $expectedFoodItems = [
            [
                'foodItemName' => 'Full English breakfast',
                'allergies' =>
                    ['gluten',
                    ],
            ],
        ];
        $this->assertEquals($expectedFoodItems, $actualFoodItems);
    }
}