<?php


namespace FoodVendorSearchCli;


/**
 * Class FoodVendorSearch
 * @package FoodVendorSearchCli
 */
class FoodVendorSearch
{

    /**
     * @var CommandLineOptions $commandLineOptions
     */
    private $mCommandLineOptions;

    /**
     * @var EBNFReader $eBNFReader
     */
    private $mEBNFReader;

    /**
     * @var Logger $logger logger Singleton
     */
    private $mLogger;

    /**
     * @var Vendors
     */
    private $mVendors;

    /**
     * FoodVendorSearch constructor.
     */
    public function __construct() {
        $this->mCommandLineOptions = new CommandLineOptions();
        $this->mEBNFReader = new EBNFReader();
        $this->mLogger = Logger::getInstance();
        $this->mVendors = Vendors::getInstance();
    }

    /**
     * @param array $foodItemsFound
     */
    private function outputFoodItemsFound(array $foodItemsFound): void {
        foreach ($foodItemsFound as $foodItem){
            Logger::out($foodItem);
        }
    }

    /**
     * Main Method, runs application
     */
    public function run(): void {
        $commandLineOptions = $this->mCommandLineOptions->readCommandLineOptions();
        $optionsAreValid = $this->mCommandLineOptions->validateOptions($commandLineOptions);
        if($optionsAreValid) {
            $filePath = $this->mCommandLineOptions->getFilename();
            $this->mEBNFReader->parseFile($filePath);
            $foodItemsFound = $this->mVendors->findFoodItems($commandLineOptions);
            $this->outputFoodItemsFound($foodItemsFound);
        } else {
            Logger::out('Options input were invalid.');
            Logger::outputHelpText();
        }
    }

}