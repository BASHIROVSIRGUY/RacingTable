<?php
namespace AutoRacing\Pilot;

class Pilot {
    private $ID = 0;

    public $name = '';
    public $city = '';
    public $car = '';

    public $one_race_score = 0;
    public $two_race_score = 0;
    public $three_race_score = 0;
    public $four_race_score = 0;
    public $sum_races_score = 0;

    private $race_names = [
        1 => 'one_race_score',
        2 => 'two_race_score',
        3 => 'three_race_score',
        4 => 'four_race_score',
    ];
    private $actual_race_num = 0;

    public function __construct($info){
        extract($info);
        $this->ID = $id;
        $this->name = $name;
        $this->city = $city;
        $this->car = $car;
    }

    public function setRaceResult($score){
        if($this->actual_race_num < 4){
            ++$this->actual_race_num;
        }
        $race_var_name = $this->race_names[$this->actual_race_num];
        $this->$race_var_name = $score;
        if($this->actual_race_num == 4){
            foreach($this->race_names as $race_var_name){
                $this->sum_races_score += $this->$race_var_name;
            }
        }
    }

    public function getID(){
        return $this->ID;
    }

    public function getInfo(){
        return [
            'name' => $this->name,
            'city' => $this->city,
            'car' => $this->car,
            'one_race_score' => $this->one_race_score,
            'four_race_score' => $this->four_race_score,
            'two_race_score' => $this->two_race_score,
            'three_race_score' => $this->three_race_score,
            'sum_races_score' => $this->sum_races_score,
        ];
    }
}