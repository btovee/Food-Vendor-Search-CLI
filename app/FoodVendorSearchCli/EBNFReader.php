<?php


namespace FoodVendorSearchCli;


/**
 * Class EBNFReader
 * @package FoodVendorSearchCli
 */
class EBNFReader
{
    /**
     * @var Logger $mLogger logger instance
     */
    private $mLogger;

    /**
     * @var Vendors
     */
    private $mVendors;

    /**
     * CommandLineOptions constructor.
     */
    public function __construct()
    {
        $this->mLogger = Logger::getInstance();
        $this->mVendors = Vendors::getInstance();
    }


    /**
     * Parse input file into Vendors instance
     *
     * @param string $filePath
     */
    public function parseFile(string $filePath): void
    {
        if (is_file($filePath)) {
            $fileContents = file_get_contents($filePath);
            $vendors = $this->splitFileContentsIntoVendors($fileContents);
            $this->createVendorsModel($vendors);
        } else {
            $this->mLogger::out("Filename specified is not a regular file.");
        }
    }

    /**
     * Split the file contents into vendors
     *
     * @param string $fileContents
     * @return array
     */
    private function splitFileContentsIntoVendors(string $fileContents): array
    {
        return preg_split('/\r\n\r\n|\r\r|\n\n/', $fileContents);
    }

    /**
     * Split the vendors and FoodItems up
     *
     * @param string $vendor
     * @return array
     */
    private function splitIntoVendorsAndFoodItems(string $vendor): array
    {
        return preg_split('/\r\n|\r|\n/', $vendor);
    }

    /**
     * Split out vendor
     *
     * @param string $vendor
     * @return array
     */
    private function splitVendor(string $vendor): array
    {
        $vendorDetails = explode(';', $vendor);
        $vendorDetails[2] = intval($vendorDetails[2]);
        return $vendorDetails;
    }

    /**
     * Split out Food Items
     *
     * @param string $foodItem
     * @return array
     */
    private function splitFoodItem(string $foodItem): array
    {
        $foodItemDetails = explode(';', $foodItem);
        $foodItemDetails[1] = $this->splitAllergens($foodItemDetails[1]);
        $foodItemDetails[2] = $this->getNumberFromString($foodItemDetails[2]);
        return $foodItemDetails;
    }

    /**
     * Split out allergens
     *
     * @param string $allergens
     * @return array
     */
    private function splitAllergens(string $allergens): array
    {
        return explode(',', $allergens);
    }

    /**
     * Retrieve a number from string
     *
     * @param string $advanceTime
     * @return string
     */
    private function getNumberFromString(string $advanceTime): string
    {
        preg_match('/\d+/', $advanceTime, $output_array);
        return intval($output_array[0]);
    }

    /**
     * Creates Vendors Instance from array parsed from file
     *
     * @param array $vendors
     */
    private function createVendorsModel(array $vendors): void
    {
        foreach ($vendors as $vendor) {
            $vendorDetails = $this->splitIntoVendorsAndFoodItems($vendor);
            $oVendor = new Vendor(...$this->splitVendor($vendorDetails[0]));
            foreach (array_slice($vendorDetails, 1) as $foodItem) {
                $foodItemFormatted = $this->splitFoodItem($foodItem);
                $oFoodItem = new FoodItem(...$foodItemFormatted);
                $oVendor->addFoodItem($oFoodItem);
            }
            $this->mVendors->addVendor($oVendor);
        }
    }
}