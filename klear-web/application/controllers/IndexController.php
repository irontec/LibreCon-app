<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    
    
public function notificationAction()
    {
        
        exit();
        $userSourceId = $this->getParam("source",1);
        $usertargetId = $this->getParam("target",2);
        $meetingId = $this->getParam("meeting",2);
        $type = $this->getParam("type","question");
        
        
        $gearmandOptions = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('gearmandpersistent');
        \Iron_Gearman_Manager::setOptions($gearmandOptions);
        
        $job = array(
            'userSourceId' => $userSourceId,
            'userDestinationId' =>$usertargetId,
            'meetingId' => $meetingId,
            'msgType' => $type
        );
        
        $gearmandClient = \Iron_Gearman_Manager::getClient();
        $r = $gearmandClient->doBackground("sendPush", igbinary_serialize($job));
        var_dump($job);
        var_dump($r);exit;
    }
    
    
    public function mailerAction()
    {
    
        $userId = $this->getParam("id",1);
        
        $gearmandOptions = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('gearmandpersistent');
        \Iron_Gearman_Manager::setOptions($gearmandOptions);
    
        $job = array('userId' => $userId);
       
        $gearmandClient = \Iron_Gearman_Manager::getClient();
        $r = $gearmandClient->doBackground("sendEmail", igbinary_serialize($job));
        var_dump($job);
        var_dump($r);exit;
    }
    
    

}

