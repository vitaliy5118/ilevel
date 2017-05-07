<?php

class Application_Model_DbTable_Operation extends Zend_Db_Table_Abstract
{

    protected $_name = 'operations';
    
    //�-��� ���������� ����� ������
    public function addOperation(Application_Model_Operation $operation) {
        // ��������� ������ � �������
        $this->insert($operation->makearray());
    }
    
    //�-��� �������� ������
    public function deleteOperation($id) {
        //������� ������
        $this->delete('id=' . (int) $id);
    }
}

