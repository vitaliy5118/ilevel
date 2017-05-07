<?php

class Application_Model_Operation {

    //put your code here
    public $id;
    public $direction;
    public $operation;

    public function __construct(Application_Form_Operation $form) {

        if ($form->getValue('id') != NULL) {
            $this->id = $form->getValue('id');
        }
        $this->direction = $form->getValue('direction');
        $this->operation = $form->getValue('operation');
    }

    public function makearray() {
        // ��������� ������ ������ ��� ����������
        $data = array(
            'direction'   => $this->direction,
            'operation'   => $this->operation
        );
        return $data;
    }

    public function save() {
        //��������� ������ � ����
        $save_data = new Application_Model_DbTable_Operation();
        $save_data->addOperation($this);
    }

    public function edit() {
        //��������� ������ � ����
        $save_data = new Application_Model_DbTable_Diary();
        $save_data->editDiary($this);
    }

    //������� �������������� ���� � ������
    public static function getSortDate($params) { 
        //����
        if($params->day<10){
            $day = "0".$params->day;
        } else {
           $day = $params->day; 
        }
        //�����
        if($params->month<10){
            $month = "0".$params->month;
        } else {
            $month = $params->month; 
        }
        //���������� ������ ���� yyyy-mm-dd
        return $date = $params->year."-".$month."-".$day;
    }

}
