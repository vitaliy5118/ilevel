<?php

class Application_Model_DbTable_Operation extends Zend_Db_Table_Abstract
{

    protected $_name = 'operations';
    
    //ф-ция добавления новой записи
    public function addOperation(Application_Model_Operation $operation) {
        // сохраняем данные в таблицу
        $this->insert($operation->makearray());
    }
    
    //ф-ция удаления записи
    public function deleteOperation($id) {
        //удаляем запись
        $this->delete('id=' . (int) $id);
    }
}

