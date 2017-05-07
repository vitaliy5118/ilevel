<?php

class Application_Model_DbTable_Setup extends Zend_Db_Table_Abstract {

    //������� ���� ������    
    public function makeBase() {

        $sql = ("DROP DATABASE idiary");
        $this->_db->query($sql);

        //������� ������� ������ "operations"
        $sql = ("CREATE DATABASE idiary");
        $this->_db->query($sql);

        $sql = ("use idiary");
        $this->_db->query($sql);

        //������� ������� ������ "operations"
        $sql = ("CREATE TABLE IF NOT EXISTS operations (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  operation varchar(100) NOT NULL,
                  direction varchar(100) NOT NULL,
                  INDEX (operation),
                  INDEX (direction)
                )
                  ENGINE = INNODB
                  COLLATE cp1251_general_ci;
                ");

        $this->_db->query($sql);

        //������� ������� ������ "operations"
        $sql = ("CREATE TABLE IF NOT EXISTS diary (
                  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  date timestamp,
                  direction varchar(30) NOT NULL,
                  operation varchar(30) NOT NULL,
                  cash_ua double NOT NULL, 
                  cash_usd double NOT NULL, 
                  INDEX (direction),
                  INDEX (operation),
                  FOREIGN KEY (direction) REFERENCES operations (direction) ON DELETE CASCADE ON UPDATE CASCADE,
                  FOREIGN KEY (operation) REFERENCES operations (operation) ON DELETE CASCADE ON UPDATE CASCADE
                )
                  ENGINE = INNODB
                  COLLATE cp1251_general_ci;
                ");
        $this->_db->query($sql);
    }

//���������� ���������� ��������� �� �����
    public function loadBase() {

//��������� ������
        $sql = ("INSERT INTO operations (operation, direction)
                 VALUES 
                 ('������ �� ������', 'out'),           
                 ('������� ���������', 'out'),           
                 ('���������� ����������', 'out'),           
                 ('������������ ������', 'out'),           
                 ('������', 'out'),           
                 ('������� ��������', 'in'),           
                 ('��������', 'in');           
                ");

        $this->_db->query($sql);

//��������� ������
        $sql = ("INSERT INTO diary (date, direction, operation, cash_ua, cash_usd)
                 VALUES 
                 ('2017-03-01 11:16:42','out','������ �� ������','15', '0.549'),           
                 ('2017-03-02 12:16:42','out','���������� ����������','15','0.549'),           
                 ('2017-03-03 13:16:42','out','������� ���������','270', '10.09'),          
                 ('2017-03-03 14:16:42','out','������� ���������','1200', '45.09'),          
                 ('2017-03-04 15:16:42','out','������������ ������','1400', '55.49'),          
                 ('2017-03-05 16:16:42','in','��������','8500', '309.09'),          
                 ('2017-03-06 17:16:42','in','������� ��������','1000', '33.09');          
                ");

        $this->_db->query($sql);

    }
}
