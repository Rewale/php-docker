<?php

class Racer {
    public $id;
    public $name;
    public $city;
    public $car;
    public $carId;
    public $attempts = [];
    public $sum_of_points;

    public function setAttempsFromArray($attempts){
        foreach ($attempts['data'] as $attempt) {
            if ($attempt['id'] == $this->id) {
                $this->sum_of_points += $attempt['result'];
                array_push($this->attempts, $attempt['result']);
            }
        }

    }

    public function setCarInfo($data){
        foreach ($data AS $key => $value) $this->{$key} = $value;
    }
}


function getRacersAttmept(array $attempts, Racer $racer ){
    foreach ($attempts['data'] as $attempt) {
        if ($attempt['id'] == $car['id']) {
            $racer->sum_of_points += $attempt['result'];
            array_push($racer->attempts, $attempt['result']);
        }
    }
}

function racers_sort($a, $b) {
    if ($a->sum_of_points != $b->sum_of_points) {
        return $a->sum_of_points < $b->sum_of_points ? 1 : -1;
    }
    for ($i = 0; $i < count($a->attempts); $i++) {
        if ($a->attempts[$i] != $b->attempts[$i]) {
            return $a->attempts[$i] > $b->attempts[$i] ? 1 : -1;
        }
    }
    return strnatcasecmp($a->name, $b->name);
}

function getRacers(string $pathToJson){
    $data_cars = file_get_contents($pathToJson."/data_cars.json");
    $data_attempts = file_get_contents($pathToJson."/data_attempts.json");

    $cars = json_decode($data_cars, true);
    $attempts = json_decode($data_attempts, true);
    $racers = [];

    foreach ($cars['data'] as $car) {
        $racer = new Racer();
        $racer->setCarInfo($car);
        $racer->setAttempsFromArray($attempts);

        array_push($racers,$racer);
    }

    usort($racers, 'racers_sort');

    return $racers;
}

function writeResultToCsv(string $path, string $fileName, array $racers){

    $file = fopen($path.'/'.$fileName.'.csv', 'w');
    fputcsv($file, ['Номер участника', 'Имя', 'Город', 'Машина', 'Попытки', 'Сумма баллов'], ';');
    foreach ($racers as $racer) {
        $attempt_string = '';
        $i = 1;
        foreach ($racer->attempts as $attempt) {
            $attempt_string .= "Попытка $i: ".$attempt.',';
            $i++;
        }
        fputcsv($file, [$racer->id, $racer->name, $racer->city, $racer->car, $attempt_string, $racer->sum_of_points], ';');
    }
    fclose($file);
}
