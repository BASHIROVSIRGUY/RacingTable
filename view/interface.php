<?php
use AutoRasing\TableAgregator\TableAgregator;


$data_cars_file_path = __DIR__ . '/../storage/data_cars.json';
$data_attempts_file_path = __DIR__ . '/../storage/data_attempts.json';
$data_cars_json = file_get_contents($data_cars_file_path);
$data_attempts_json = file_get_contents($data_attempts_file_path);
$table_obj = new TableAgregator($data_cars_json, $data_attempts_json);
$pilot_list = $table_obj->getSortedPilotList();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Auto racing results</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div>
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
                <?php foreach(TableAgregator::TABLE_COLUMNS as $col_name): ?>
                    <th > <?= $col_name ?> </th>
                <?php endforeach; ?>
            </thead>
            <?php 
                $i = 0;
                foreach($pilot_list as $pilot_obj): 
                    ++$i;
                    $pilot_info = ['rate' => $i] + $pilot_obj->getInfo(); 
            ?>
                <tr>
                    <?php
                        foreach(array_keys(TableAgregator::TABLE_COLUMNS) as $obj_col_name){
                            $row = $pilot_info[$obj_col_name];
                            if($obj_col_name == 'rate'){
                                $row = '<th> ' . $row . ' </th>';
                            } else{
                                $row = '<td> ' . $row . ' </td>';
                            }
                            echo $row;
                        }
                    ?>
                </tr>
            <?php 
                endforeach; 
            ?>
        </table>
        <hr>
        <div class="container">
            <div class="offset-md-10">
                <form method="GET">
                    <button type='submit' class="btn btn-outline-dark btn-lg ">
                        Обновить
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>