<?php
namespace CarRental\Domain\Model;
class Payment
{
    private $finished;
    
    public function confirm()
    {
        $this->finished = true;
    }
    public function deny()
    {
        $this->finished = false;
    }
    public function isFinished()
    {
        return $this->finished;
    }
}