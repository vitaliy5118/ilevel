<?php

class Application_Form_Operation extends Zend_Form {
    
    public function init() {
        
        //выбрать тип операции
        $direction = new Zend_Form_Element_Select('direction', array('class' => 'form-control', "multiOptions" => array('in' => 'Заработать','out'=>'Потратить')));
        $direction->setLabel('Операция')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class' => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
        ));
        
        //ввести новую операцию
        $operation = new Zend_Form_Element_Text('operation', array('class' => 'form-control'));
        $operation->setLabel('Введите операцию')
                ->setRequired(true)
                ->setAttrib('placeholder', 'пример: Зарплата')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('regex',true, array("/^[А-Яа-яA-Za-z1-90 \.\-\,\"\№]{3,200}$/i", 'messages' => 'Ошибка регистра'))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Поле не может быть пустым')))
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class' => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
        ));

        // Создаём элемент hidden c именем = id
        $id = new Zend_Form_Element_Hidden('id');
        // Указываем, что данные в этом элементе фильтруются как число int
        $id->addFilter('Int')
                ->removeDecorator('label')
                ->removeDecorator('element');
        
        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit', array('class' => 'btn btn-default'));
        // Добавляем все созданные элементы к форме.
        $this->addElements(array($id, $direction, $operation, $submit));
    }

}
