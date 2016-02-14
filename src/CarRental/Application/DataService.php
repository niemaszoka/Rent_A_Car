<?php

namespace CarRental\Application;

class DataService
{
    private $sourcePath;
    private $dataJSON;

    public function __construct($dataJSON)
    {
        $this->sourcePaths = array(
            'cars' => '../src/data/carsData.json',
            'users' => '../src/data/usersData.json'
        );
        $this->dataJSON = $dataJSON;
    }

    public function getData($type)
    {
        $array = $this->dataJSON->getData($this->sourcePaths[$type]);
        return $array[$type];
    }

    public function saveData($type, $data){
        $path = $this->sourcePaths[$type];
        $this->dataJSON->saveData($path, $data);
    }
}
