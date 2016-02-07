<?php

namespace CarRental\Application;
class RentForm
{
    protected $CarID;
    protected $Brand;
    protected $Segment;
    protected $PricePerDay;

    public function getCarID()
    {
        return $this->CarID;
    }
    public function setCarID($CarID)
    {
        $this->CarID = $CarID;
    }
    public function getBrand()
    {
        return $this->Brand;
    }
    public function setBrand($Brand)
    {
        $this->Brand = $Brand;
    }
    public function getSegment()
    {
        return $this->Segment;
    }
    public function setSegment($Segment)
    {
        $this->Segment = $Segment;
    }
    public function getPricePerDay()
    {
        return $this->PricePerDay;
    }
    public function setPricePerDay($PricePerDay)
    {
        $this->PricePerDay = $PricePerDay;
    }
}