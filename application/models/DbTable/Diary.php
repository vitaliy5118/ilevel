<?php

class Application_Model_DbTable_Diary extends Zend_Db_Table_Abstract
{

    protected $_name = 'diary';
    
    //��������� ��������� �������� � ����������
    public function getOperations($direction = 'in') {
        $sql = (" SELECT operation
                  FROM `operations`
                  WHERE direction = '$direction'
                ");
        $data =  $this->getAdapter()->query($sql)->fetchAll();
        //��������� ������ ������
        foreach($data as $rows){
           foreach ($rows as $name => $value){
              $data_array[$value] = $value; 
           } 
        }
        return $data_array;
    }
    //��������� ������ �� ���������� ������
    public function getDiary($id) {
       //��������� id
        $id = (int) $id;
        //�������� ������
        $row = $this->fetchRow('id=' . $id);
        if (!$row) {
           echo ("������!<br> ������ � ��������������� id=$id �� ���������!"); die; 
        }
        //���������� ������
        return $row->toArray();
    }
    
    //���������� ������ �������� �� �������� ������
    public function getDiaryArray($date_min, $date_max) {
        $sql = (" SELECT *
                  FROM `diary`
                  WHERE `date` BETWEEN '$date_min 00:00:00' AND '$date_max 23:59:59'
                  ORDER BY `date` DESC
                ");
        $data =  $this->getAdapter()->query($sql)->fetchAll();
        //��������� ������ ������
        foreach($data as $rows){
           foreach ($rows as $name => $value){
              $data_array[$name] = iconv('cp1251', 'utf-8', $value); 
           } 
           $full_array[] = $data_array; 
        }
        return $full_array;
    }
    
    public function addDiary(Application_Model_Diary $diary) {
        // ��������� ������ � �������
        $this->insert($diary->makearray());
    }
    
    public function editDiary(Application_Model_Diary $diary) {
        // ��������� ������ � �������
        $this->update($diary->makearray(), 'id=' . (int) $diary->id);
    }
    
    public function deleteDiary($id) {
        //������� ������
        $this->delete('id=' . (int) $id);
    }
    
    //��������� ���� ���������� � ������������ �����
    public function infoDiary($direction) {
        $sql = (" SELECT SUM(cash_ua) as ua, SUM(cash_usd) as usd
                  FROM `diary`
                  WHERE direction = '$direction'
                ");
        $data =  $this->getAdapter()->query($sql)->fetchAll();
        //��������� ������ ������
        foreach($data as $rows){
           foreach ($rows as $name => $value){
              $data_array[$name] = $value; 
           } 
        }
        return $data_array;
    }
    
}

