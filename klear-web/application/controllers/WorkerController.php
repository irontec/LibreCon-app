<?php

class WorkerController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function notificatorAction()
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        \Iron_Gearman_Manager::setOptions($bootstrap->getOption("gearmandpersistent"));
        \Iron_Gearman_Manager::runWorker("Notificator");
    }
    
    
    public function emailerAction()
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        \Iron_Gearman_Manager::setOptions($bootstrap->getOption("gearmandpersistent"));
        \Iron_Gearman_Manager::runWorker("Emailer");
    }

}
