<?php

namespace CarRental\Application;

class CarsService
{
    private $dataService;

    public function __construct($dataService)
    {
        $this->dataService = $dataService;
    }

    public function getAvailableCars()
    {
        $allCars = $this->getAllCars();
        $availableCars = [];

        foreach ($allCars as &$car)
        {
            if (!$car['booked'] && !$car['rented'])
            {
                array_push($availableCars, $car);
            }
        }
        return $availableCars;
    }

    public function getAllCars(){
        return $this->dataService->getData('cars');
    }


    public function getCar($carId){
        $allCars = $this->getAllCars();

        foreach ($allCars as &$car)
        {
            if ($car['id'] === $carId)
            {
                return $car;
            }
        }

        return 'Error: car not found';
    }

    public function saveCars($cars){
        $data = array('cars' => $cars);
        $this->dataService->saveData('cars', $data);
    }

    public function bookCar($carId)
    {
         $this->changeProperty($carId, 'booked', true);
    }

    public function cancelBooking($carId)
    {
         $this->changeProperty($carId, 'booked', false);
    }

    public function markCarAsRented($carId)
    {
         $this->changeProperty($carId, 'rented', true);
    }

    public function returnCar($carId)
    {
        $this->changeProperty($carId, 'rented', false);
        
    }    

    public function rentCar($carId){
        $this->markCarAsRented($carId);
        $this->cancelBooking($carId);

    }

    public function changeProperty($carId, $property, $value){
        $allCars = $this->getAllCars();
        foreach ($allCars as $key => $car)
        {
            if ($car['id'] === $carId)
            {
                $allCars[$key][$property] = $value;
            }
        }
        $this->saveCars($allCars);
    }

}
