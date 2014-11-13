<?php

use Librecon\Mapper\Sql\Schedule as ScheduleMapper;

class CalendarController extends Zend_Controller_Action
{
    
    protected $_days = array('1','2');
    protected $_langs = array('es','eu','en');

    public function init()
    {
        date_default_timezone_set('Europe/Madrid');
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $tz = new \DateTimeZone(date_default_timezone_get());
        
        $day = $this->getParam("day","1");
        if (!in_array($day, $this->_days)) {
            throw new Zend_Controller_Action_Exception('This page does not exist', 404);
        }
        
        $this->view->minDate = new \DateTime('2014-11-1'.$day.' 08:30:00', $tz);
        $this->view->maxDate = new \DateTime('2014-11-1'.$day.' '.(17+$day).':30:00', $tz);
        
        $this->view->interval = new DateInterval('PT30M');
        
        $lang = $this->getParam("lang","es"); 
        if (!in_array($lang, $this->_langs)) {
            throw new Zend_Controller_Action_Exception('This page does not exist', 404);
        }
        $slugFilter = new \Iron_Filter_Slug();
        $sMapper = new ScheduleMapper();
        $items = $sMapper->fetchList("targetDate = ". $day,'startDateTime');
        
        $this->view->data = array();
        
        foreach($items as $item) {
            if (!isset($this->view->data[$item->getLocation()])) {
                $this->view->data[$item->getLocation()] = array();
            }
            
            $schedule = array(
                'start' => new \DateTime($item->getStartDateTime(true)->setTimezone(date_default_timezone_get())->toString(Zend_Date::ISO_8601), $tz),
                'end' => new \DateTime($item->getFinishDateTime(true)->setTimezone(date_default_timezone_get())->toString(Zend_Date::ISO_8601), $tz),
                'es_title' => $item->getName('es'),
                'title' => $item->getName($lang),
                'slug' => $slugFilter->filter($item->getName('es')),
                'speakers' => array(),
                'tags' => array()
            );

            $speakersRels = $item->getRelScheduleSpeaker();
            foreach($speakersRels as $rel) {
                $speaker = $rel->getSpeaker();
	if (!is_object($speaker)) {
		continue;
	}
                if ($speaker->getPictureFileSize() == 0) {
                        $url = 'http://www.librecon.io/wp-content/uploads/2014/10/Avatar_tipo.jpg';
                } else {
                        $url = 'https://mobile.librecon.io/avatar/'.$speaker->getId();
                }

                $schedule['speakers'][] = array(
                    'name'=> $speaker->getName(),
                    'company' => $speaker->getCompany(),
                    'url' => $url
                );
             }

            $tagsRels = $item->getRelTagScheduele();
            foreach($tagsRels as $rel) {
                $tag = $rel->getTags();
                $schedule['tags'][] = array('name'=>$tag->getName($lang), 'class'=>$this->_resolveClassForColor($tag->getColor()));
            }

            $this->view->data[$item->getLocation()][] = $schedule;
            
        }
        
        
        $this->view->locations = array_keys($this->view->data);
        
        
    }
    
    
    protected function _resolveClassForColor($color)
    {
        $color = strtoupper(preg_replace("/[^0-9A-F]/i","",$color));


        $rel = array(
            "CB9B12"=>"tagmclaro",
            "C91D83"=>"tagfucsia",
            "006EB6"=>"tagazul",
            "CB0D17"=>"tagrojo",
            "B3CAA0"=>"tagverdecaqui",
            "670047"=>"tagmorado",
            "00F097"=>"tagverdeagua",
            "962F33"=>"tagteja",
            "69CA1B"=>"tagverdeintenso",
            "FEF200"=>"tagamarillo",
            "000000"=>"tagnegro"
        );
        if (isset($rel[$color])) {
            return $rel[$color];
        }
        
        return '';
        
        
    }    
}
