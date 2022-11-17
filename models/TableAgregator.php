<?php
namespace AutoRasing\TableAgregator;
use AutoRacing\Pilot\Pilot;

class TableAgregator {
    const SORT_COLUMNS = [
        'sum_score' => 'sum_races_score',
        'four_race' => 'four_race_score',
        'three_race' => 'three_race_score',
        'two_race' => 'two_race_score',
        'one_race' => 'one_race_score',
        'name' => 'name',
    ];
    const RESULT_FILE_PATH = '/../storage/result.csv';
    const TABLE_COLUMNS = [
        'rate' => 'Номер места',
        'name' => 'Имя',
        'city' => 'Город',
        'car' => 'Автомобиль',
        'one_race_score' => 'Первый заезд',
        'four_race_score' => 'Второй заезд',
        'two_race_score' => 'Третий заезд',
        'three_race_score' => 'Четвертый заезд',
        'sum_races_score' => 'Сумма очков',
    ];
    private $pilot_list = [];

    public function __construct($cars_json, $attempts_json){
        $this->fillPilotList($cars_json);
        $this->fillRaceResult($attempts_json);
    }

    public function getSortedPilotList(){
        $table_data = $this->combineTableData();
        $id_key_num = key(array_filter(array_keys($table_data), function($k) {
            return $k == 'id';
        }));
        $table_data_unkeys = array_values($table_data);
        array_multisort(...$table_data_unkeys);
        $sorted_pilot_ids = array_reverse($table_data_unkeys[$id_key_num]);
        $sorted_pilot_list = [];
        foreach($sorted_pilot_ids as $pilot_id){
            $sorted_pilot_list[$pilot_id] = $this->pilot_list[$pilot_id];
        }
        $this->saveRasultDataToCSV($sorted_pilot_list);
        return $sorted_pilot_list;
    } 

    private function fillPilotList($cars_json){
        $data = json_decode($cars_json, true);
        foreach($data as $info){
            $this->pilot_list[$info['id']] = new Pilot($info);
        }
    }

    private function fillRaceResult($attempts_json){
        $data = json_decode($attempts_json, true);
        foreach($data as $attempt){
            $this->pilot_list[$attempt['id']]->setRaceResult($attempt['result']);
        }
    }

    private function combineTableData(){
        $table_data = [];
        foreach(self::SORT_COLUMNS as $table_col_name => $obj_col_name){
            $table_data[$table_col_name] = array_column($this->pilot_list, $obj_col_name);
        }
        $table_data['id'] = array_keys($this->pilot_list);
        return $table_data;
    }

    private function saveRasultDataToCSV($sorted_pilot_list){
        $file = fopen(__DIR__ . self::RESULT_FILE_PATH, 'w+');
        fputcsv($file, array_values(self::TABLE_COLUMNS));
        $i = 0;
        foreach($sorted_pilot_list as $pilot_obj){
            $row = [];
            ++$i;
            $pilot_info = ['rate' => $i] + $pilot_obj->getInfo();
            foreach(array_keys(self::TABLE_COLUMNS) as $obj_name){
                $row[] = $pilot_info[$obj_name];
            }
            fputcsv($file, $row);
        }
    }
}