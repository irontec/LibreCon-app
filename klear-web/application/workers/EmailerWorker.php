<?php

use Librecon\Mapper\Sql\Assistants;

class EmailerWorker extends Iron_Gearman_Worker
{
    protected $_timeout = 10000; // 1000 = 1 second

    protected $_config;

    protected $_fromName = 'LibreCon';
    protected $_fromEmail = 'no-reply@librecon.io';
    protected $_subject = '[LIBRECON] Ongietorri | Bienvenido | Welcome';
    
    
    protected function initRegisterFunctions()
    {
        $this->_registerFunction = array(
            'sendEmail' => '_sendEmail'
        );
    }

    protected function init()
    {
       $transport = new Zend_Mail_Transport_Smtp('localhost');
       Zend_Mail::setDefaultTransport($transport);
        
       date_default_timezone_set('Europe/Madrid');
    }



    protected function timeout()
    {
        $aMapper = new Assistants();
        $aMapper->getDbTable()->getAdapter()->closeConnection();
        
    }



    public function _sendEmail($job)
    {

        $msg = igbinary_unserialize($job->workload());
        
        
        $aMapper = new Assistants();
        $assistant = $aMapper->find($msg['userId']);
        if (!is_object($assistant)) {
            // TODO :: LOG!
            return;
        }
        
        $replace = array(
            '%name%' => $assistant->getName() . ' ' . $assistant->getLastName(),
            '%email%' => $assistant->getEmail(),
            '%code%' => $assistant->getCode()
        );
        
        $notifyMail = new \Zend_Mail('utf-8');
        
        $message = $this->_getMessage($assistant->getLang());

        $message = str_replace(array_keys($replace), $replace, $message);
        
        $notifyMail->setBodyHtml($message);
        $notifyMail->setBodyText(strip_tags($message));
        $notifyMail->setFrom($this->_fromEmail, $this->_fromName);
        $notifyMail->setSubject($this->_subject);
        
        $notifyMail->addTo($assistant->getEmail(), $assistant->getName() . ' ' . $assistant->getLastName());
        //$notifyMail->addTo('jabi+demoLibre@irontec.com', $assistant->getName() . ' ' . $assistant->getLastName());
        $result = $notifyMail->send();
        
        file_put_contents("/tmp/mails", $assistant->getEmail() . ' > ' . $assistant->getName() . ' ' . $assistant->getLastName() . ' > res=' . print_r($result) ."\n", FILE_APPEND);
        $rememberSents = $assistant->getMailRemember();
        $rememberSents = $rememberSents+1;
        $assistant->setMailRemember($rememberSents)->save();
        
    }
    
    
    protected function _getMessage($lang = 'es') {
        $file = APPLICATION_PATH . '/configs/mails/code_' . $lang . '.html';
        return file_get_contents($file);
        
    }
    
    
}
