<?php


namespace FoodVendorSearchCli;


/**
 * Class CommandLineOptions
 * @package FoodVendorSearchCli
 */
class CommandLineOptions
{

    /**
     * @var string FILE_NAME_LONG_OPTION long option for filename (required)
     */
    public const FILE_NAME_LONG_OPTION = 'filename';

    /**
     * @var string DAY_LONG_OPTION long option for day (required)
     */
    public const DAY_LONG_OPTION = 'day';

    /**
     * @var string TIME_LONG_OPTION long option for time (required)
     */
    public const TIME_LONG_OPTION = 'time';

    /**
     * @var string LOCATION_LONG_OPTION long option for location (required)
     */
    public const LOCATION_LONG_OPTION = 'location';

    /**
     * @var string COVERS_LONG_OPTION long option for covers (required)
     */
    public const COVERS_LONG_OPTION = 'covers';

    /**
     * @var Logger $logger logger instance
     */
    private $logger;

    /**
     * @var string $filename The filename set by user input.
     */
    private $filename;

    /**
     * @var string $day The day set by user input.
     */
    private $day;

    /**
     * @var string $time The time set by user input.
     */
    private $time;

    /**
     * @var string $location The location set by user input.
     */
    private $location;

    /**
     * @var string $covers The covers set by user input.
     */
    private $covers;

    /**
     * CommandLineOptions constructor.
     */
    public function __construct()
    {
        $this->logger = Logger::getInstance();
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getCovers(): string
    {
        return $this->covers;
    }

    /**
     * Read the commandline arguments entered by user
     * @return array
     */
    public function readCommandLineOptions(): array
    {
        return getopt(null, [
            self::FILE_NAME_LONG_OPTION . ":",
            self::DAY_LONG_OPTION . ":",
            self::TIME_LONG_OPTION . ":",
            self::LOCATION_LONG_OPTION . ":",
            self::COVERS_LONG_OPTION . ":"
        ]);

    }

    /**
     *  Validate commandline options
     *
     * @param array $commandLineOptions
     * @return bool
     */
    public function validateOptions(array $commandLineOptions): bool
    {
        if (
            $this->validFilename($commandLineOptions) &&
            $this->validDay($commandLineOptions) &&
            $this->validTime($commandLineOptions) &&
            $this->validLocation($commandLineOptions) &&
            $this->validCovers($commandLineOptions)
        ) {
            $this->filename = $commandLineOptions[self::FILE_NAME_LONG_OPTION];
            $this->day = $commandLineOptions[self::DAY_LONG_OPTION];
            $this->time = $commandLineOptions[self::TIME_LONG_OPTION];
            $this->location = $commandLineOptions[self::LOCATION_LONG_OPTION];
            $this->covers = $commandLineOptions[self::COVERS_LONG_OPTION];

            return true;
        }
        return false;
    }


    /**
     * Check Option exists $commandLineOptions array
     *
     * @param string $option
     * @param array $commandLineOptions
     * @return bool
     */
    private function checkForOption(string $option, array $commandLineOptions): bool
    {
        return array_key_exists($option, $commandLineOptions);
    }


    /**
     *  Check the input file name is valid and exists
     *
     * @param array $commandLineOptions
     * @return bool
     */
    private function validFilename(array $commandLineOptions): bool
    {
        if (
            $this->checkForOption(self::FILE_NAME_LONG_OPTION, $commandLineOptions) &&
            is_string($commandLineOptions[self::FILE_NAME_LONG_OPTION]) &&
            file_exists($commandLineOptions[self::FILE_NAME_LONG_OPTION])
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if day input is a valid day format (dd/mm/yy)
     *
     * @param string $date
     * @return bool
     */
    private function checkDateFormat(string $date): bool {
        $matches = array();
        if (!preg_match('/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{2})$/', $date, $matches)) {
            return false;
        }
        if (!checkdate($matches[2], $matches[1], $matches[3])) {
            return false;
        }
        return true;
    }

    /**
     * Check the option exist and if day input is a valid day format (dd/mm/yy)
     *
     * @param array $commandLineOptions
     * @return bool
     */
    private function validDay(array $commandLineOptions): bool
    {
        if (
            $this->checkForOption(self::DAY_LONG_OPTION, $commandLineOptions) &&
            $this->checkDateFormat($commandLineOptions[self::DAY_LONG_OPTION])
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if time input is a valid 24h format (hh:mm)
     *
     * @param string $time
     * @return bool
     */
    private function checkTimeFormat(string $time):bool {
        return preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time);
    }

    /**
     * Check the option exist and if time input is a valid 24h format (hh:mm)
     *
     * @param array $commandLineOptions
     * @return bool
     */
    private function validTime(array $commandLineOptions): bool
    {
        if (
            $this->checkForOption(self::TIME_LONG_OPTION, $commandLineOptions) &&
            $this->checkTimeFormat($commandLineOptions[self::TIME_LONG_OPTION])
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if for a London postcode
     *
     * @param string $postcode
     * @return bool
     */
    private function validPostcode(string $postcode): bool
    {
        return preg_match('/^(E[0-9]{1,2})|(EC[0-9]{1,2})|(N[0-9]{1,2})|(NW[0-9]{1,2})|(SE[0-9]{1,2})|(SW[0-9]{1,2})|(W[0-9]{1,2})|(WC[0-9]{1,2})$/', mb_strtoupper($postcode));
    }

    /**
     * Check if location input is a valid postcode without spaces (e.g. NW43QB)
     *
     * @param array $commandLineOptions
     * @return bool
     */
    private function validLocation(array $commandLineOptions): bool
    {
        if (
            $this->checkForOption(self::LOCATION_LONG_OPTION, $commandLineOptions) &&
            $this->validPostcode($commandLineOptions[self::LOCATION_LONG_OPTION])
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if Covers input is a number
     *
     * @param array $commandLineOptions
     * @return bool
     */
    private function validCovers(array $commandLineOptions): bool
    {
        if (
            $this->checkForOption(self::COVERS_LONG_OPTION, $commandLineOptions) &&
            is_numeric($commandLineOptions[self::COVERS_LONG_OPTION])
        ) {
            return true;
        }
        return false;
    }
}