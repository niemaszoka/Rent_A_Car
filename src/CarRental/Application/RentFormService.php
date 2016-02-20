<?php

namespace CarRental\Application;

class RentFormService
{
    protected $usersService;
    protected $carsService;

    public function __construct($usersService, $carsService){
        $this->usersService = $usersService;
        $this->carsService = $carsService;
    }

    public function onPaymentSuccess($data){
        
        $this->carsService->rentCar($data->getCarId());
        $this->carsService->cancelBooking($data->getCarId());
        $this->usersService->rentCar($data);
    }

    public function handleSubmission($data){
        $days = $data->getRentDays();
        $pricePerDay = $data->getPricePerDay();

        $amount = $this->calculateInitialAmount($days, $pricePerDay);

        $discount = $this->calculateDiscount(
            $data->getEmail(),
            $days,
            $amount
        );

        $totalAmount = $amount-$discount;
        
        $data->setInitialAmount($amount);
        $data->setDiscount($discount);
        $data->setTotalAmount($totalAmount);

        return $data;
    }

    public function calculateInitialAmount($days, $price){
        return $days * $price;
    }

    public function calculateDiscount($userEmail, $rentDays, $amount){

        $discounts = array(
            'rentDaysDiscount' => $this->rentDaysDiscount($rentDays, $amount),
            'monthlyDiscount' => $this-> monthlyDiscount($userEmail, $amount)
        );

        return max($discounts);

    }

    public function rentDaysDiscount($days, $amount){
        $discount = 0;

        if( $days > 7 ){
            $discount = 0.1 * $amount;
        }

        return $discount;
    }

    public function monthlyDiscount($userEmail, $amount){
        $userData = $this->usersService->getUser($userEmail);
        $currentMonth = date("Y-m");
        $discount = 0;

        if( $userData['rents'] !== null && array_key_exists($currentMonth, $userData['rents'])){
            if($userData['rents'][$currentMonth] > 2){
                $discount = $amount * 0.2;
            }
        }

        return $discount;
    }
}