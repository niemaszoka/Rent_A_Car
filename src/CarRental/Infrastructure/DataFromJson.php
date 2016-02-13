<?php

namespace CarRental\Infrastructure;

use CarRental\Domain\DataArray;

class DataFromJson implements DataArray
{
    public function getData($sourcePath)
    {
        $array = json_decode(file_get_contents($sourcePath), true);

        return $array;
    }

    public function saveData($path, $data){
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
    }
}