<?php

class DiaryController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }
    
    //начальна€ страница
    public function indexAction() {
        // action body
        $diary = new Application_Model_DbTable_Diary();
       
        //ajax query, getting diary data
        if ($this->getRequest()->isPost()) {

            $params = json_decode(str_replace ("\\","", $this->getRequest()->getPost('params')));
            
            //преобразуем данные в строку типа yyyy-mm-dd
            $date_min = Application_Model_Diary::getSortDate($params->date_min); //дата "от"
            $date_max = Application_Model_Diary::getSortDate($params->date_max); //дата "до"
            
            //возвращаем извлеченные данные 
            $diary_array =  $diary->getDiaryArray($date_min, $date_max);
                        
            echo json_encode($diary_array); //echo JSON data
            die;
        }
        
        //передаем начальные данные в view
        $this->view->diary = $diary->fetchAll($diary->select()->order('date DESC'));
        
        //данные баланса за период
        $this->view->info_in = $diary->infoDiary('in'); //баланс прихода денег
        $this->view->info_out = $diary->infoDiary('out'); //баланс расхода денег
    }

    //добавление новой записи
    public function addAction() {
        
        //проверка параметра direct методом GET
        if ($this->getRequest()->getParam('direct')) {
            $direct = $this->getRequest()->getParam('direct'); //получаем параметр direct методом GET
            
            //проверка параметра direrct
            if ($direct != 'in' && $direct != 'out') {
                die('¬нимание! Ќе допустимый параметр!'); //ложный или отсутствующий параметр
            }
            
            //создаем форму
            $form = new Application_Form_Diary($direct);
            
            //метка дл€ кнопки submit
            if($direct == 'in'){
                $form->submit->setLabel('«аработать');
            } else {
                $form->submit->setLabel('ѕотратить');
            }
            //передаем форму во view
            $this->view->form = $form;

            // ≈сли к нам идЄт Post запрос
            if ($this->getRequest()->isPost()) {
                //принимаем данные
                $formData = $this->getRequest()->getPost();

                //ѕроверка валидациии    
                if ($form->isValid($formData)) {
                    //”спешна€ валидаци€
                    //получаем текущее значение курса валют
                    $kurs = json_decode(file_get_contents("https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"));
                    //—охран€ем данные в базу
                    $diary = new Application_Model_Diary($form, $direct, $kurs[2]->sale);
                    $diary->save();
                    //ѕереходим на предыдущую страницу
                    $this->_helper->redirector('index');
                } else {
                    //Ќеуспешна€ валидаци€
                    //¬озвращаем данные в таблицу
                    $form->populate($formData);
                }
            }
        //отсутствует параметр direct
        } else {
           $this->_helper->redirector('index'); 
        }
    }

    //редактирование записи
    public function editAction() {
        
        //проверка параметра direct методом GET
        if ($this->getRequest()->getParam('direct')) {
            $direct = $this->getRequest()->getParam('direct');  //получаем параметр direct методом GET
            
            //проверка параметра direrct
            if ($direct != 'in' && $direct != 'out') {
                die('¬нимание! Ќе допустимый параметр!'); //ложный или отсутствующий параметр
            }
            
            //создаем форму
            $form = new Application_Form_Diary($direct);
            
            //метка дл€ кнопки submit
            if($direct == 'in'){
                $form->submit->setLabel('«аработать');
            } else {
                $form->submit->setLabel('ѕотратить');
            }
            //передаем форму во view
            $this->view->form = $form;

            // ≈сли к нам идЄт Post запрос
            if ($this->getRequest()->isPost()) {
                //принимаем данные
                $formData = $this->getRequest()->getPost();

                //ѕроверка валидациии    
                if ($form->isValid($formData)) {
                    //”спешна€ валидаци€
                    //получаем текущее значение курса валют
                    $kurs = json_decode(file_get_contents("https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"));
                    //—охран€ем данные в базу
                    $diary = new Application_Model_Diary($form, $direct, $kurs[2]->sale);
                    $diary->edit();
                    //ѕереходим на предыдущую страницу
                    $this->_helper->redirector('index');
                } else {
                    //Ќеуспешна€ валидаци€
                    //¬озвращаем данные в таблицу
                    $form->populate($formData);
                }
            } else {
                //загружаем данн≥е дл€ редактировани€
                $id = $this->getParam('id');
                //передаем данн≥е в форму
                $diary = new Application_Model_DbTable_Diary();
                $form->populate($diary->getDiary($id));
            }
        //отсутствует параметр direct
        } else {
           $this->_helper->redirector('index'); 
        }
    }
    
    //удаление записи (удаление посредством запроса ajax)
    public function deleteAction() {
        
        //отключаем view
        $this->_helper->viewRenderer->setNoRender(true);
        //если идет запрос типа POST
        if ($this->getRequest()->isPost('id')) {
            $id = $this->getRequest()->getPost('id'); //принимаем id
            //удал€ем запись
            $diary = new Application_Model_DbTable_Diary();
            $diary->deleteDiary($id);
            echo ('delete done'); //successfully
            die;
        } else {
            echo ('error Nodata'); //unsuccessfully
            die;
        }

    }

}
