<?php

class SetupController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }
    
    //стартовая страница настроек
    public function indexAction() {
        // action body
        //выгружаем начальные данные
        $operations = new Application_Model_DbTable_Operation();
        $this->view->operations = $operations->fetchAll($operations->select()->order('id DESC'));
    }

    //миграция, создание новой базы данных
    public function makeAction() {
        
        $make = new Application_Model_DbTable_Setup(); //новый объект базы
        $make->makeBase(); //создаем базу данных
        $this->_helper->viewRenderer->setNoRender(true); //отключить view
        $this->_helper->redirector('index'); //goto index
    }
    //миграция, загрузка начальных данных
    public function loadAction() {

        $update = new Application_Model_DbTable_Setup(); //новый объект базы
        $update->loadBase(); //загружаем начальные данные
        $this->_helper->viewRenderer->setNoRender(true); //отключить view
        $this->_helper->redirector('index'); //goto index
    }
    
    //действие создания новой операции 
    public function addAction() {

        //создаем форму
        $form = new Application_Form_Operation();
        $form->submit->setLabel('Добавить');
        $this->view->form = $form;

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            //принимаем данные
            $formData = $this->getRequest()->getPost();
            //Проверка валидациии    
            if ($form->isValid($formData)) {
                //Успешная валидация
                //Сохраняем данные в базу
                $diary = new Application_Model_Operation($form);
                $diary->save();
                //Переходим на предыдущую страницу
                $this->_helper->redirector('index');
            } else {
                //Неуспешная валидация
                //Возвращаем данные в таблицу
                $form->populate($formData);
            }
        }
        
    }
     
    //действие удаление операции (удаление посредством запроса ajax)
    public function deleteAction() {
        
        //отключаем view
        $this->_helper->viewRenderer->setNoRender(true);
        //если идет запрос типа POST
        if ($this->getRequest()->isPost('id')) {
            $id = $this->getRequest()->getPost('id'); //принимаем id
            //удаляем запись
            $operation = new Application_Model_DbTable_Operation();
            $operation->deleteOperation($id);
            echo ('delete done');
            die;
        } else {
            echo ('Error Nodata');
            die;
        }

    }
}
    