<?php

namespace CarRental\Application;
use CarRental\Domain\Exception\RentException;
use CarRental\Domain\Model\Payment;

class PaymentService
{
    private $formService;

    public function __construct($formService)
    {
        $this->formService = $formService;
    }

    public function completePurchase($data, Payment $payment)
    {
        if ($payment->isFinished()) { 
            $formService->onPaymentSuccess($data);
            return;
        }
        
        throw new RentException('Missing payment');
    }
}