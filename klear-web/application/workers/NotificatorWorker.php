<?php


use Librecon\Mapper\Sql\Assistants;

class NotificatorWorker extends Iron_Gearman_Worker
{
    protected $_timeout = 10000; // 1000 = 1 second

    protected $_config;

    protected $_literals = array(
        'question' =>array(
            'es'=> '%source% te quiere proponer una reunión.',
            'eu'=> '%source% te quiere proponer una reunión. (eu)',
            'en'=> '%source% te quiere proponer una reunión. (en)'
        ),
        'answer' => array(
            'es'=> '%source% ha respondido a tu solicitud de reunión.',
            'eu'=> '%source% ha respondido a tu solicitud de reunión. (eu)',
            'en'=> '%source% ha respondido a tu solicitud de reunión. (en)'
        )                
    );
    

    protected function initRegisterFunctions()
    {


        $this->_registerFunction = array(
                'sendPush' => '_sendPush',
        );
    }

    protected function init()
    {
	date_default_timezone_set('Europe/Madrid');
        $this->_config = 
            \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('notifications');

    }



    protected function timeout()
    {
        $aMapper = new Assistants();
        $aMapper->getDbTable()->getAdapter()->closeConnection();
        
    }



    public function _sendPush($job)
    {

        $msg = igbinary_unserialize($job->workload());
        
        $aMapper = new Assistants();
        $assistantTarget = $aMapper->find($msg['userDestinationId']);
        $assistantSource = $aMapper->find($msg['userSourceId']);
        
        
        if (!is_object($assistantSource) || !is_object($assistantTarget)) {
            // TODO >> Log!
             file_put_contents("/tmp/in_the_worker", "2." . print_r($msg, true), FILE_APPEND);
            return;
        }
        
        if (!in_array($msg['msgType'],array("question","answer"))) {
            //TODO >> log!
             file_put_contents("/tmp/in_the_worker", "3." . print_r($msg, true), FILE_APPEND);
            return;
        }
        
        
        $sourceName = $assistantSource->getName(). ' ' . $assistantSource->getLastName();
        $destinationName = $assistantTarget->getName(). ' ' . $assistantTarget->getLastName();
        
         file_put_contents("/tmp/notificatorLOG", $sourceName . " envía a " . $destinationName ." [".$msg['msgType']."] con idMetting: " . $msg['meetingId'] . "\n", FILE_APPEND);
        
        $text = $this->_literals[$msg['msgType']][$assistantTarget->getLang()];
        $text = str_replace(array("%source%","%destination%"), array($sourceName, $destinationName), $text);
        
        $data = array(
            'meetingId' => $msg['meetingId']
        );
        
        $uuid = $assistantTarget->getUuid();
        
//         file_put_contents("/tmp/notificatorLOG", "Destination has UUID (". $uuid.")", FILE_APPEND);
        
        if (empty($uuid)) {
            
//             file_put_contents("/tmp/in_the_worker", "4." . print_r($msg, true), FILE_APPEND);
            //TODO >> log!
            return;
        }
        
        if ($assistantTarget->getDevice() == 'ios') {
//             file_put_contents("/tmp/notificatorLOG", "Destination has IOS\n", FILE_APPEND);
            $this->_sendTo_iOS($uuid, $text, $data);
        } elseif ($assistantTarget->getDevice() == 'android') {
//             file_put_contents("/tmp/notificatorLOG", "Destination has ANDROID\n", FILE_APPEND);
            $this->_sendTo_android($uuid, $text, $data);
        }

    }
    
    
    protected function _sendTo_iOS($uuid, $text, $data)
    {
        $push = new ApnsPHP_Push(
                $this->_config['ios']['env'], 
                APPLICATION_PATH .$this->_config['ios']['certPath']
        );
        // Set the Provider Certificate passphrase
        $push->setProviderCertificatePassphrase($this->_config['ios']['passphrase']);
        $push->connect();
        try {
            $message = new ApnsPHP_Message($uuid);
            
            $message->setText($text);
            $message->setSound();
            
            foreach($data as $key => $value) {
                $message->setCustomProperty($key, $value);
            }
            
            
            $message->setExpiry(60);
            $push->add($message);
            $push->send();
            $push->disconnect();
            
            $aErrorQueue = $push->getErrors();
        } catch(Exception $e) {
            $aErrorQueue = array($e->getMessage());
        }
             
        if (sizeof($aErrorQueue) > 0) {
            file_put_contents("/tmp/error_push_ios", print_r($aErrorQueue, true), FILE_APPEND);
        } else {
            file_put_contents("/tmp/ok_push_ios", print_r($uuid, true), FILE_APPEND);
//             file_put_contents("/tmp/notificatorLOG", "Sent ok!\n=========================================================================================================\n\n", FILE_APPEND);
        }
        
        
    }

    
    protected function _sendTo_android($uuid, $text, $data)
    {

        $message = array(
            'message' => $text,
            'vibrate'	=> 1,
            'sound'	=> 1,
        );
        
        foreach($data as $key => $value) {
                $message[$key] = $value;
        }
 
        $payload = array(
            'registration_ids' => array($uuid),
            'data'	=> $message
        );
        $headers = array(
            'Authorization: key=' . $this->_config['android']['apikey'],
            'Content-Type: application/json'
        );
        
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
        $result = curl_exec($ch );
        curl_close( $ch );
            
         file_put_contents("/tmp/notificatorLOG", "Sent ok ".print_r($result, true)."!\n=========================================================================================================\n\n", FILE_APPEND);
           
        
    }
    
    
}
