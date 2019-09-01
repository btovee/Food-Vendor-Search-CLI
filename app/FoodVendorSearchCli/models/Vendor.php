<?php


namespace FoodVendorSearchCli;


/**
 * Class Vendor
 * @package FoodVendorSearchCli
 */
class Vendor
{

    /**
     * @var string $name The name of the Vendor
     */
    private $name;

    /**
     * @var string $postcode The postcode of the Vendor
     */
    private $postcode;

    /**
     * @var string $maxCovers The max covers of the Vendor
     */
    private $maxCovers;

    /**
     * @var array $foodItems The food Items of the Vendor
     */
    private $foodItems = [];

    /**
     * Vendor constructor.
     * @param $name
     * @param $postcode
     * @param $maxCovers
     */
    public function __construct(string $name, string $postcode, int $maxCovers)
    {
        $this->name = $name;
        $this->postcode = $postcode;
        $this->maxCovers = $maxCovers;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @return int
     */
    public function getMaxCovers(): int
    {
        return $this->maxCovers;
    }

    /**
     * @return array
     */
    public function getFoodItems(): array
    {
        return $this->foodItems;
    }

    /**
     * @param FoodItem $foodItem
     */
    public function addFoodItem(FoodItem $foodItem): void
    {
        $this->foodItems[] = $foodItem;
    }


    /**
     * Check can cover the number of people
     *
     * @param int $numberOfPeople
     * @return bool
     */
    public function canCoverTheNumberOfPeople(int $numberOfPeople): bool
    {
        return $numberOfPeople <= $this->maxCovers ? true : false;
    }

    /**
     * Check is within the notice period
     *
     * @param string $day The day in the format dd/mm/yy
     * @param string $time The time in the format hh:mm
     * @return \Generator
     * @throws \Exception
     */
    public function withinNoticePeriod(string $day, string $time)
    {
        $date = date_create_from_format('d/m/y:H:i', $day . ":" . $time);
        $datetimeNow = new \DateTime();
        $interval = date_diff($datetimeNow, $date);
        $hours = $interval->h;
        $hours = $hours + ($interval->days * 24);

        foreach ($this->foodItems as $oFoodItem) {
            $foodItemAdvanceTime = $oFoodItem->getAdvanceTime();
            if ($hours >= $foodItemAdvanceTime) {
                yield [
                    'foodItemName' => $oFoodItem->getName(),
                    'allergies' => $oFoodItem->getAllergies()
                ];
            }
        }
    }


    /**
     * Check the first letters of the area code match
     *
     * @param string $postcode
     * @return string
     */
    private function getFirstLettersOfAreaCode(string $postcode): string
    {
        preg_match('/([a-zA-Z]+)/', $postcode, $matches, PREG_OFFSET_CAPTURE, 0);
        return $matches[0][0];
    }

    /**
     * Check is within postcode
     *
     * @param string $postcode
     * @return bool
     */
    public function withinPostcode(string $postcode): bool
    {
        $vendorAreaCode = $this->getFirstLettersOfAreaCode($this->postcode);
        $searchAreaCode = $this->getFirstLettersOfAreaCode($postcode);
        if (mb_strtoupper($vendorAreaCode) === mb_strtoupper($searchAreaCode)) {
            return true;
        } else {
            return false;
        }
    }


}