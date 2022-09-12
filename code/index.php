<?php
require_once('service.php');
$pathToJson = './res';
$racers = getRacers($pathToJson);
writeResultToCsv($pathToJson, 'results', $racers);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="table.css">
        <link rel="stylesheet" href="button.css">
        <title>Racers</title>
    </head>
    <body>
    <div class="container">
        <input class="button-3" type="submit" value="Пересчитать">

        <table class="styled-table" style="margin-top: 10px">
            <thead>
                <tr>
                    <th>Номер участника</th>
                    <th>Имя</th>
                    <th>Город</th>
                    <th>Машина</th>
                    <th>Попытки</th>
                    <th>Сумма баллов</th>
                </tr>
            </thead>
            <?php
                foreach ($racers as $racer) {
                    ?>
                    <tr class="active-row">
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
    </div>
    </body>
</html>
