<?php

class Application_Form_Operation extends Zend_Form {
    
    public function init() {
        
        //������� ��� ��������
        $direction = new Zend_Form_Element_Select('direction', array('class' => 'form-control', "multiOptions" => array('in' => '����������','out'=>'���������')));
        $direction->setLabel('��������')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class' => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
        ));
        
        //������ ����� ��������
        $operation = new Zend_Form_Element_Text('operation', array('class' => 'form-control'));
        $operation->setLabel('������� ��������')
                ->setRequired(true)
                ->setAttrib('placeholder', '������: ��������')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('regex',true, array("/^[�-��-�A-Za-z1-90 \.\-\,\"\�]{3,200}$/i", 'messages' => '������ ��������'))
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
        $this->addElements(array($id, $direction, $operation, $submit));
    }

}
