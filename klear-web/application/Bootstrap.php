<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public function _initAutoload()
    {
        require_once APPLICATION_PATH .'/../library/vendor/autoload.php';
    }
    
    
    
    public function _initMagicRoute()
    {
        
        $front = Zend_Controller_Front::getInstance();
        
        $router = $front->getRouter();

        $route = new Zend_Controller_Router_Route(
                'pushTest',
                array(
                        'controller' => 'index',
                        'action'     => 'notification'
                )
        );
        $router->addRoute('pushTest', $route);
        

        $route = new Zend_Controller_Router_Route(
                'mailTest',
                array(
                        'controller' => 'index',
                        'action'     => 'mailer'
                )
        );
        $router->addRoute('mailTest', $route);
        
    }

}

