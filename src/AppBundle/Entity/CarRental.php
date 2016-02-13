<?php

namespace AppBundle\Entity;


class CarRental
{
    protected $carId;
    protected $email;
    protected $firstName;
    protected $lastName;
    protected $rentDays;
    protected $pricePerDay;
    protected $initialAmount;
    protected $discount;
    protected $totalAmount;

    public function getCarId()
    {
        return $this->carId;
    }
    public function setCarID($carId)
    {
        $this->carId = $carId;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function setLastName($lastName){
        $this->lastName = $lastName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function setRentDays($days){
        $this->rentDays = $days;
    }

    public function getRentDays(){
        return $this->rentDays;
    }

    public function setPricePerDay($price){
        $this->pricePerDay = $price;
    }

    public function getPricePerDay(){
        return $this->pricePerDay;
    }

    public function setTotalAmount($amount){

        $this->totalAmount = $amount;        
    }

    public function getTotalAmount(){
        return $this->pricePerDay;
    }

    public function setInitialAmount($amount){
        $this->initialAmount = $amount;
    }

    public function getInitialAmount(){
        return $this->initialAmount;
    }

    public function setDiscount($discount){
        $this->discount = $discount;
    }

    public function getDiscount(){
        return $this->discount;
    }
}