<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initPlugins() {
        Zend_Controller_Front::getInstance()
                ->registerPlugin(new Application_Plugin_KursCheck());
    }
}