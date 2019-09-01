<?php

use Codeception\Test\Unit;
use FoodVendorSearchCli\CommandLineOptions;

/**
 * Class CommandLineOptionsTest
 */
class CommandLineOptionsTest extends Unit
{
    /**
     * @var CommandLineOptions $mCommandLineOptions
     */
    protected $mCommandLineOptions;

    /**
     * Create a Fresh instance before test
     */
    protected function _before()
    {
        $this->mCommandLineOptions = new CommandLineOptions();
    }

    /**
     * A test to check for valid command line Options
     */
    public function testValidateOptions(): void
    {
        $commandLineOptions = [
            CommandLineOptions::FILE_NAME_LONG_OPTION => 'tests/unit/resources/example-input.txt',
            CommandLineOptions::DAY_LONG_OPTION => '11/11/18',
            CommandLineOptions::TIME_LONG_OPTION => '11:00',
            CommandLineOptions::LOCATION_LONG_OPTION => 'NW43QB',
            CommandLineOptions::COVERS_LONG_OPTION => '20'
        ];

        $validOptions = $this->mCommandLineOptions->validateOptions($commandLineOptions);
        $this->assertTrue($validOptions);
    }


}