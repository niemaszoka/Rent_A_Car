<?php

namespace CarRental\Infrastructure\Payment;
use CarRental\Domain\Model\Payment;

class PaymentFactory
{
    public function createPayment(CompletedPayment $completePayment)
    {
        $payment = new Payment();
        if ($completePayment->isSuccessful()) {
            $payment->confirm();
            return $payment;
        }

        $payment->deny();
        return $payment;
    }
}