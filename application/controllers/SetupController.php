<?php

class SetupController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }
    
    //��������� �������� ��������
    public function indexAction() {
        // action body
        //��������� ��������� ������
        $operations = new Application_Model_DbTable_Operation();
        $this->view->operations = $operations->fetchAll($operations->select()->order('id DESC'));
    }

    //��������, �������� ����� ���� ������
    public function makeAction() {
        
        $make = new Application_Model_DbTable_Setup(); //����� ������ ����
        $make->makeBase(); //������� ���� ������
        $this->_helper->viewRenderer->setNoRender(true); //��������� view
        $this->_helper->redirector('index'); //goto index
    }
    //��������, �������� ��������� ������
    public function loadAction() {

        $update = new Application_Model_DbTable_Setup(); //����� ������ ����
        $update->loadBase(); //��������� ��������� ������
        $this->_helper->viewRenderer->setNoRender(true); //��������� view
        $this->_helper->redirector('index'); //goto index
    }
    
    //�������� �������� ����� �������� 
    public function addAction() {

        //������� �����
        $form = new Application_Form_Operation();
        $form->submit->setLabel('��������');
        $this->view->form = $form;

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            //��������� ������
            $formData = $this->getRequest()->getPost();
            //�������� ����������    
            if ($form->isValid($formData)) {
                //�������� ���������
                //��������� ������ � ����
                $diary = new Application_Model_Operation($form);
                $diary->save();
                //��������� �� ���������� ��������
                $this->_helper->redirector('index');
            } else {
                //���������� ���������
                //���������� ������ � �������
                $form->populate($formData);
            }
        }
        
    }
     
    //�������� �������� �������� (�������� ����������� ������� ajax)
    public function deleteAction() {
        
        //��������� view
        $this->_helper->viewRenderer->setNoRender(true);
        //���� ���� ������ ���� POST
        if ($this->getRequest()->isPost('id')) {
            $id = $this->getRequest()->getPost('id'); //��������� id
            //������� ������
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
    