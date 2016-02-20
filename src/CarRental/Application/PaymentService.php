<?php

namespace CarRental\Application;
use CarRental\Domain\Exception\RentException;
use CarRental\Domain\Model\Payment;

class PaymentService
{
    private $carsService;

    public function __construct($carsService)
    {
        $this->carsService = $carsService;
    }

    public function completePurchase($carId, Payment $payment)
    {
        if ($payment->isFinished()) { 
            $carsService->rentCar($carId);
            return;
        }
        
        throw new RentException('Missing payment');
    }
}