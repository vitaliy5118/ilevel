<?php

class Application_Model_Diary {

    //put your code here
    public $id;
    public $direction;
    public $operation;
    public $cash_ua;
    public $cash_usd;

    public function __construct(Application_Form_Diary $form, $direction, $kurs) {

        if ($form->getValue('id') != NULL) {
            $this->id = $form->getValue('id');
        }
        $this->direction = $direction;
        $this->operation = $form->getValue('operation');
        $this->cash_ua   = $form->getValue('cash_ua');
        //пересчет курса
        $this->cash_usd = $this->cash_ua/$kurs;
    }

    public function makearray() {
        // формируем массив данных дл€ сохранени€
        $data = array(
            'direction'   => $this->direction,
            'operation'   => $this->operation,
            'cash_ua'     => $this->cash_ua,
            'cash_usd'    => $this->cash_usd
        );
        return $data;
    }

    public function save() {
        //ƒобавл€ем данные в базу
        $save_data = new Application_Model_DbTable_Diary();
        $save_data->addDiary($this);
    }

    public function edit() {
        //ƒобавл€ем данные в базу
        $save_data = new Application_Model_DbTable_Diary();
        $save_data->editDiary($this);
    }

    //функци€ преобразовани€ даты в строку
    public static function getSortDate($params) { 
        //день
        if($params->day<10){
            $day = "0".$params->day;
        } else {
           $day = $params->day; 
        }
        //мес€ц
        if($params->month<10){
            $month = "0".$params->month;
        } else {
            $month = $params->month; 
        }
        //возвращаем строку типа yyyy-mm-dd
        return $date = $params->year."-".$month."-".$day;
    }

}
