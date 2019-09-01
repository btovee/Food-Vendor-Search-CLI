<?php


namespace FoodVendorSearchCli;


use Hoa\File\Read;

/**
 * Class Vendors
 * @package FoodVendorSearchCli
 */
class Vendors
{
    /**
     * @var Vendors $instance Vendors singleton
     */
    private static $instance = NULL;

    /**
     * Gets instance of the Logger
     * @return Vendors instance
     *
     */
    public function getInstance(): Vendors
    {
        if (self::$instance === NULL) {
            self::$instance = new Vendors();
        }
        return self::$instance;
    }

    /**
     * @var array $vendors An array of the available vendors
     */
    private $vendors = [];

    /**
     * @return array
     */
    public function getVendors(): array
    {
        return $this->vendors;
    }

    /**
     * @param array $vendors
     */
    public function setVendors(array $vendors): void
    {
        $this->vendors = $vendors;
    }

    /**
     * Add a vendor to list
     *
     * @param Vendor $vendor
     */
    public function addVendor(Vendor $vendor): void
    {
        $this->vendors[] = $vendor;
    }

    /**
     * Format food items to return to the commandline
     *
     * @param array $foodItems
     * @return string
     */
    private function formatFoundFoodItems(array $foodItems): string
    {
        return sprintf("%s;%s", $foodItems["foodItemName"], implode(',', $foodItems["allergies"]));
    }

    /**
     * Find matching food items from the Vendors
     *
     * @param array $commandLineOptions
     * @return array
     */
    public function findFoodItems(array $commandLineOptions): array
    {
        $postcode = $commandLineOptions[CommandLineOptions::LOCATION_LONG_OPTION];
        $covers = $commandLineOptions[CommandLineOptions::COVERS_LONG_OPTION];
        $day = $commandLineOptions[CommandLineOptions::DAY_LONG_OPTION];
        $time = $commandLineOptions[CommandLineOptions::TIME_LONG_OPTION];

        $foodItemsFound = [];

        foreach ($this->vendors as $oVendor) {
            if (
                $oVendor->canCoverTheNumberOfPeople($covers) &&
                $oVendor->withinPostcode($postcode)
            ) {
                $foodItemsFoundGenerator = $oVendor->withinNoticePeriod($day, $time);
                foreach ($foodItemsFoundGenerator as $foodItems) {
                    $foodItemsFound[] = $this->formatFoundFoodItems($foodItems);
                }
            }
        }
        return $foodItemsFound;
    }

}