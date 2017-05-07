<?php

class Application_Form_Diary extends Zend_Form {
    
    protected $direct;

    public function __construct($direct)
    {
        $this->direct = $direct; //параметр "доход-расход"
        parent::__construct();
    }

    public function init() {
        
        //выгружаем с базы данных список доступных операций в зависимости от параметра direct
        $operations = new Application_Model_DbTable_Diary();
        $operation_array = $operations->getOperations($this->direct);
        
        //доступные операции
        $operation = new Zend_Form_Element_Select('operation', array('class' => 'form-control', "multiOptions" => $operation_array));
        $operation->setLabel('Операция')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class' => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
        ));
        
        //сумма
        $cash_ua = new Zend_Form_Element_Text('cash_ua', array('class' => 'form-control'));
        $cash_ua->setLabel('Сумма')
                ->setRequired(true)
                ->setAttrib('placeholder', 'пример: 15,75')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('regex', true, array("/^[1-90 \.]{1,25}$/i", 'messages' => 'Введите только цифры'))
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
        $this->addElements(array($id, $operation, $cash_ua, $submit));
    }

}
