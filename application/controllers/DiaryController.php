<?php

class DiaryController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }
    
    //��������� ��������
    public function indexAction() {
        // action body
        $diary = new Application_Model_DbTable_Diary();
       
        //ajax query, getting diary data
        if ($this->getRequest()->isPost()) {

            $params = json_decode(str_replace ("\\","", $this->getRequest()->getPost('params')));
            
            //����������� ������ � ������ ���� yyyy-mm-dd
            $date_min = Application_Model_Diary::getSortDate($params->date_min); //���� "��"
            $date_max = Application_Model_Diary::getSortDate($params->date_max); //���� "��"
            
            //���������� ����������� ������ 
            $diary_array =  $diary->getDiaryArray($date_min, $date_max);
                        
            echo json_encode($diary_array); //echo JSON data
            die;
        }
        
        //�������� ��������� ������ � view
        $this->view->diary = $diary->fetchAll($diary->select()->order('date DESC'));
        
        //������ ������� �� ������
        $this->view->info_in = $diary->infoDiary('in'); //������ ������� �����
        $this->view->info_out = $diary->infoDiary('out'); //������ ������� �����
    }

    //���������� ����� ������
    public function addAction() {
        
        //�������� ��������� direct ������� GET
        if ($this->getRequest()->getParam('direct')) {
            $direct = $this->getRequest()->getParam('direct'); //�������� �������� direct ������� GET
            
            //�������� ��������� direrct
            if ($direct != 'in' && $direct != 'out') {
                die('��������! �� ���������� ��������!'); //������ ��� ������������� ��������
            }
            
            //������� �����
            $form = new Application_Form_Diary($direct);
            
            //����� ��� ������ submit
            if($direct == 'in'){
                $form->submit->setLabel('����������');
            } else {
                $form->submit->setLabel('���������');
            }
            //�������� ����� �� view
            $this->view->form = $form;

            // ���� � ��� ��� Post ������
            if ($this->getRequest()->isPost()) {
                //��������� ������
                $formData = $this->getRequest()->getPost();

                //�������� ����������    
                if ($form->isValid($formData)) {
                    //�������� ���������
                    //�������� ������� �������� ����� �����
                    $kurs = json_decode(file_get_contents("https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"));
                    //��������� ������ � ����
                    $diary = new Application_Model_Diary($form, $direct, $kurs[2]->sale);
                    $diary->save();
                    //��������� �� ���������� ��������
                    $this->_helper->redirector('index');
                } else {
                    //���������� ���������
                    //���������� ������ � �������
                    $form->populate($formData);
                }
            }
        //����������� �������� direct
        } else {
           $this->_helper->redirector('index'); 
        }
    }

    //�������������� ������
    public function editAction() {
        
        //�������� ��������� direct ������� GET
        if ($this->getRequest()->getParam('direct')) {
            $direct = $this->getRequest()->getParam('direct');  //�������� �������� direct ������� GET
            
            //�������� ��������� direrct
            if ($direct != 'in' && $direct != 'out') {
                die('��������! �� ���������� ��������!'); //������ ��� ������������� ��������
            }
            
            //������� �����
            $form = new Application_Form_Diary($direct);
            
            //����� ��� ������ submit
            if($direct == 'in'){
                $form->submit->setLabel('����������');
            } else {
                $form->submit->setLabel('���������');
            }
            //�������� ����� �� view
            $this->view->form = $form;

            // ���� � ��� ��� Post ������
            if ($this->getRequest()->isPost()) {
                //��������� ������
                $formData = $this->getRequest()->getPost();

                //�������� ����������    
                if ($form->isValid($formData)) {
                    //�������� ���������
                    //�������� ������� �������� ����� �����
                    $kurs = json_decode(file_get_contents("https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"));
                    //��������� ������ � ����
                    $diary = new Application_Model_Diary($form, $direct, $kurs[2]->sale);
                    $diary->edit();
                    //��������� �� ���������� ��������
                    $this->_helper->redirector('index');
                } else {
                    //���������� ���������
                    //���������� ������ � �������
                    $form->populate($formData);
                }
            } else {
                //��������� ����� ��� ��������������
                $id = $this->getParam('id');
                //�������� ����� � �����
                $diary = new Application_Model_DbTable_Diary();
                $form->populate($diary->getDiary($id));
            }
        //����������� �������� direct
        } else {
           $this->_helper->redirector('index'); 
        }
    }
    
    //�������� ������ (�������� ����������� ������� ajax)
    public function deleteAction() {
        
        //��������� view
        $this->_helper->viewRenderer->setNoRender(true);
        //���� ���� ������ ���� POST
        if ($this->getRequest()->isPost('id')) {
            $id = $this->getRequest()->getPost('id'); //��������� id
            //������� ������
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
