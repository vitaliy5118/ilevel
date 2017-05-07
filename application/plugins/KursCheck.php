<?php
class Application_Plugin_KursCheck extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        //�������� ������� �������� �����
        $kurs = json_decode(file_get_contents("https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"));
        if($kurs !== NULL){
            Zend_Registry::set('kurs', $kurs); //�������� ������ ����� � ��������
        } else {
            Zend_Registry::set('kurs', 'error'); //�������� ������ ����� � ��������
        }
    }
}
