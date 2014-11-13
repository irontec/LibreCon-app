<?php

use Librecon\Mapper\Sql\Schedule as ScheduleMapper;

class TalksController extends Zend_Controller_Action
{
    
    protected $_langs = array('es','eu','en');

    public function init()
    {
        date_default_timezone_set('Europe/Madrid');
    }

    public function indexAction()
    {
        $tz = new \DateTimeZone(date_default_timezone_get());
        $lang = $this->getParam("lang","es"); 
        if (!in_array($lang, $this->_langs)) {
            throw new Zend_Controller_Action_Exception('This page does not exist', 404);
        }
        
        $slugFilter = new \Iron_Filter_Slug();
        $sMapper = new ScheduleMapper();
	$cond = "location not like '%Euskalduna%'"; 
	if ($this->getParam("location")) {
		$idLoc = (int)$this->getParam("location");
		switch($idLoc) {
			case 1:
				$cond = "location = 'Sala Red Hat'";
			break;
			case 2:
				$cond = "location = 'Sala Open'";
			break;
			case 3:
				$cond = "location = 'Tech Space'";
			break;
		}
	}

	
        $items = $sMapper->fetchList($cond,"startdatetime");
        
        $this->view->data = array();
        
        foreach($items as $item) {
            
            $schedule = array(
                'start' => new \DateTime($item->getStartDateTime(true)->setTimezone(date_default_timezone_get())->toString(Zend_Date::ISO_8601), $tz),
                'end' => new \DateTime($item->getFinishDateTime(true)->setTimezone(date_default_timezone_get())->toString(Zend_Date::ISO_8601), $tz),
                'title' => $item->getName($lang),
                'description' => $item->getDescription($lang),
                'slug' => $slugFilter->filter($item->getName('es')),
                'speakers' => array(),
                'location' => $item->getLocation(),
                'tags' => array(),
                'imgs' => array()
            );
            
            $speakersRels = $item->getRelScheduleSpeaker();
            foreach($speakersRels as $rel) {
                $speaker = $rel->getSpeaker();
                if (!$speaker) {
                    continue;
                }
                if ($speaker && $speaker->getPictureFileSize() > 0) {
                    $schedule['imgs'][] = 'https://mobile.librecon.io/avatar/big/idx/'.$speaker->getId();
                }

                $schedule['speakers'][] = array(
                    'name'=> $speaker->getName(),
                    'company' => $speaker->getCompany()
                );
             }

            $tagsRels = $item->getRelTagScheduele();
            foreach($tagsRels as $rel) {
                $tag = $rel->getTags();
                $schedule['tags'][] = array(
                    'name'=>$tag->getName($lang),
                    'class'=>$this->_resolveClassForColor($tag->getColor())
                );
            }

            if (sizeof($schedule['imgs']) == 0) {
                $schedule['imgs'][] = "http://www.librecon.io/wp-content/uploads/2014/10/Avatar_tipo.jpg";
            }
            
            $this->view->data[] = $schedule;
            
        }
        
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
