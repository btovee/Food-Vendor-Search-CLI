<?php


namespace FoodVendorSearchCli;


class FoodItem
{
    /**
     * @var string $name The Food Item name
     */
    private $name;

    /**
     * @var array $allergies The allergens in the food
     */
    private $allergies;

    /**
     * @var int $advanceTime The Time needed in advance
     */
    private $advanceTime;

    /**
     * FoodItem constructor.
     * @param string $name
     * @param array $allergies
     * @param int $advanceTime
     */
    public function __construct(string $name, array $allergies, int $advanceTime)
    {
        $this->name = $name;
        $this->allergies = $allergies;
        $this->advanceTime = $advanceTime;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAllergies(): array
    {
        return $this->allergies;
    }

    /**
     * @return int
     */
    public function getAdvanceTime(): int
    {
        return $this->advanceTime;
    }


}