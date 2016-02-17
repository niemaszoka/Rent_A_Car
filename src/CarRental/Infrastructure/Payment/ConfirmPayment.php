<?php

namespace CarRental\Infrastructure\Payment;
use CarRental\Domain\Model\Payment;
class ConfirmPayment
{
    public function createPayment(UrlcCheck $completePayment)
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