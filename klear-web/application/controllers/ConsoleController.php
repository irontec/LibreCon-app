<?php
use Librecon\Mapper\Sql\Assistants;

class ConsoleController extends Zend_Controller_Action
{

    protected $_fromName = 'LibreCon';
    protected $_fromEmail = 'no-reply@librecon.io';
    
    
    protected $_subjects = array(
        "WelcomeCode"=>"Tu codigo de activación LibreCon APP",
        "Welcome"=>"Información de interés para LibreCon 2014"
    );
    
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function mailingAction()
    {
        $mailType = $this->getParam("target");
        
        if (!in_array($mailType, array(
            "WelcomeCode",
            "Welcome"
        ))) {
            
            echo "No a valid target especified!\t[OUT]\n";
            exit(2);
            
        }
        
        $file = APPLICATION_PATH . '/configs/mails/' . $mailType . '.html';
        $subject = $this->_subjects[$mailType];
        
        $this->_doThemailing($file, $subject);
        
    }
    
    protected function _doThemailing($file, $subject) 
    {
    
        $aMapper = new Assistants();
        $assistants = $aMapper->fetchAll();
        
        $html = file_get_contents($file);
        
        foreach($assistants as $assistant) {
            
            $replace = array(
                '%name%' => $assistant->getName() . ' ' . $assistant->getLastName(),
                '%email%' => $assistant->getEmail(),
                '%code%' => $assistant->getCode()
            );
        
            $notifyMail = new \Zend_Mail();
            
            $message = str_replace($replace, array_keys($replace), $html);
            
            $notifyMail->setBodyHtml($message);
            $notifyMail->setBodyText(strip_tags($message));
            $notifyMail->setFrom($this->_fromEmail, $this->_fromName);
            $notifyMail->setSubject($this->_subject);
        
            $notifyMail->addTo($assistant->getEmail(), $assistant->getName() . ' ' . $assistant->getLastName());
            //$result = $notifyMail->send();
            $result = "FAKE";
            if ($assistant->getEmail() == "jabi@irontec.com") {
                $result = $notifyMail->send();
            }
            $log = "[" .basename($file). "] " . date("d/m/Y H:i:s") . " > " . $assistant->getEmail() . " > [". $result."]\n";
            echo $log;
            file_put_contents("/tmp/mailingLog", $log, FILE_APPEND);
        }
    }
    

}
