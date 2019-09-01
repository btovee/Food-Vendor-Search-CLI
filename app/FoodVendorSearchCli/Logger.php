<?php


namespace FoodVendorSearchCli;


/**
 * Logger class
 * Singleton using lazy instantiation
 */
class Logger
{
    /**
     * @var Logger $instance Logger singleton
     */
    private static $instance = NULL;

    /**
     * @var array $logs
     */
    private $logs;

    /**
     * Logger constructor.
     */
    private function __construct()
    {
        $logs = [];
    }

    /**
     * Gets instance of the Logger
     * @return Logger instance
     *
     */
    public function getInstance(): Logger
    {
        if (self::$instance === NULL) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    /**
     * Adds a message to the log
     * @param String $message Message to be logged
     *
     */
    public function log($message): void
    {
        $this->logs[] = $message;
    }

    /**
     * Returns array of logs
     * @return array Array of log messages
     *
     */
    public function get_logs(): array
    {
        return $this->logs;
    }

    /**
     * Output string to user
     *
     * @param string $logItem
     */
    public static function out(string $logItem)
    {
        echo $logItem . "\r\n";
    }

    /**
     * Outputs help text to user
     */
    public static function outputHelpText(): void
    {
        $helpTexts = [
            'filename - input file with the vendors data  eg: --filename=\'tests/unit/resources/example-input.txt\'',
            'day      - delivery day (dd/mm/yy) eg: --day=\'11/11/18\'',
            'time     - delivery time in 24h format (hh:mm) eg: --time=\'11:00\'',
            'location - delivery location (postcode without spaces, e.g. NW43QB) eg: --location=\'NW43QB\'',
            'covers   - number of people to feed (2) eg: --covers=\'20\'`'
        ];
        foreach ($helpTexts as $helpText) {
            self::out($helpText);
        }
    }
}

;