<?php

namespace CarRental\Application;

class CarsService
{
    private $cars;
    private $dataService;

    public function __construct($dataService)
    {
        $this->cars = $dataService->getData('cars');
    }

    public function getAvailableCars()
    {
        $availableCars = [];

        foreach ($this->cars as &$car)
        {
            if (!$car['booked'] && !$car['rented'])
            {
                array_push($availableCars, $car);
            }
        }
        return $availableCars;
    }

    public function bookCar($carIndex)
    {
        
    }

    public function rentCar($carIndex)
    {

    }
}
