<?php

namespace CarRental\Application;
use CarRental\Domain\Exception\RentException;
use CarRental\Domain\Model\Payment;

class PaymentService
{

    public function completePurchase($data, Payment $payment)
    {
        if ($payment->isFinished()) {
            return;
        }
        
        throw new RentException('Missing payment');
    }
}