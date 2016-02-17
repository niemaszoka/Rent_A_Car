<?php

namespace CarRental\Application;
use CarRental\Domain\Exception\RentException;
use CarRental\Domain\Model\Payment;
class CarRenting
{
    private $carId;
    private $carsService;

    public function completePurchase($carsService, $carId, Payment $payment)
    {
    $this->carId = $carId;
        if ($payment->isFinished()) { 
            $carsService->rentCar($carId);
            return;
        }
        throw new RentException('Missing payment');
    }
}