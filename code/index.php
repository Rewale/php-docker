<?php
    class Racer {
        public $id;
        public $name;
        public $city;
        public $car;
        public $attempts = [];
        public $sum_of_points;
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

    $data_cars = file_get_contents("./res/data_cars.json");
    $data_attempts = file_get_contents("./res/data_attempts.json");

    $cars = json_decode($data_cars, true);
    $attempts = json_decode($data_attempts, true);
    $racers = [];

    foreach ($cars['data'] as $car) {
        $racer = new Racer();
        $racer->id = $car['id'];
        $racer->name = $car['name'];
        $racer->city = $car['city'];
        $racer->car = $car['car'];

        foreach ($attempts['data'] as $attempt) {
            if ($attempt['id'] == $car['id']) {
                $racer->sum_of_points += $attempt['result'];
                array_push($racer->attempts, $attempt['result']);
            }
        }
        array_push($racers,$racer);
    }

    usort($racers, 'racers_sort');

    $file = fopen('./res/results.csv', 'w');
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
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
    <input type="submit" value="Пересчитать">

    <table border="1" style="margin: auto">
            <tr>
                <th>Номер участника</th>
                <th>Имя</th>
                <th>Город</th>
                <th>Машина</th>
                <th>Попытки</th>
                <th>Сумма баллов</th>
            </tr>
            <?php
                foreach ($racers as $racer) {
                    ?>
                    <tr>
                        <td><?=$racer->id?></td>
                        <td><?=$racer->name?></td>
                        <td><?=$racer->city?></td>
                        <td><?=$racer->car?></td>
                        <td>
                        <?php
                        $i = 1;
                            foreach ($racer->attempts as $attempt) {
                                ?>
                                <p style="margin: 5px; padding: 0px;">Попытка <?=$i?>: <?=$attempt?></p>
                                <?php
                                $i++;
                            }
                        ?>
                        </td>
                        <td><?=$racer->sum_of_points?></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>
