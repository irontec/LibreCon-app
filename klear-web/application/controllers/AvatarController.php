<?php


class AvatarController extends Zend_Controller_Action
{
    
    public function init()
    {

    }

    public function indexAction(){
        die("");
    }

    public function __call($m, $args)
    {
	if ($m == 'bigAction') {
		$section = 'big';
		$id = (int)$this->getRequest()->getParam("idx");
	} else {
		$section = 'mini';
		$id = (int)$m;
	}
	if ($id == 0) {
		die("");
	}


	$this->_forward('image','media','default',array('entity'=>'speaker','id'=>$id, 'section'=>$section));

        
    }
    
}
