<?php

class Application_Form_Diary extends Zend_Form {
    
    protected $direct;

    public function __construct($direct)
    {
        $this->direct = $direct; //�������� "�����-������"
        parent::__construct();
    }

    public function init() {
        
        //��������� � ���� ������ ������ ��������� �������� � ����������� �� ��������� direct
        $operations = new Application_Model_DbTable_Diary();
        $operation_array = $operations->getOperations($this->direct);
        
        //��������� ��������
        $operation = new Zend_Form_Element_Select('operation', array('class' => 'form-control', "multiOptions" => $operation_array));
        $operation->setLabel('��������')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class' => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
        ));
        
        //�����
        $cash_ua = new Zend_Form_Element_Text('cash_ua', array('class' => 'form-control'));
        $cash_ua->setLabel('�����')
                ->setRequired(true)
                ->setAttrib('placeholder', '������: 15,75')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('regex', true, array("/^[1-90 \.]{1,25}$/i", 'messages' => '������� ������ �����'))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => '���� �� ����� ���� ������')))
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class' => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
        ));

        // ������ ������� hidden c ������ = id
        $id = new Zend_Form_Element_Hidden('id');
        // ���������, ��� ������ � ���� �������� ����������� ��� ����� int
        $id->addFilter('Int')
                ->removeDecorator('label')
                ->removeDecorator('element');
        
        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit', array('class' => 'btn btn-default'));
        // ��������� ��� ��������� �������� � �����.
        $this->addElements(array($id, $operation, $cash_ua, $submit));
    }

}
