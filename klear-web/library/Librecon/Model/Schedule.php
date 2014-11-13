<?php

/**
 * Application Model
 *
 * @package Librecon\Model
 * @subpackage Model
 * @author vvargas
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * [entity]
 *
 * @package Librecon\Model
 * @subpackage Model
 * @author vvargas
 */
 
namespace Librecon\Model;
class Schedule extends Raw\Schedule
{
    /**
     * This method is called just after parent's constructor
     */
    public function init()
    {
    }
    
    protected $_fieldFake;
    
    public function __construct()
    {
        parent::__construct();
        $this->_columnsList['startTime'] = 'startTime';
        $this->_columnsList['finishTime'] = 'finishTime';
    }
    
    
    public function getStartTime()
    {
        $curDate = $this->getStartDateTime(true);
        return $curDate->setTimezone(date_default_timezone_get())->toString("HH:mm:ss");
        
    }
    
    public function setStartTime($value)
    {
        $day = '2014-11-11';
        
        if ($this->getTargetDate() == 2) {
            $day = '2014-11-12';
        }
        
        $value = $day.' '.date("H:i:s",strtotime($value));
        
        $datetime = new \DateTime($value);
        
        $this->setStartDateTime($datetime);
    }
    
    public function getFinishTime()
    {
        $curDate = $this->getFinishDateTime(true);
        return $curDate->setTimezone(date_default_timezone_get())->toString("HH:mm:ss");
    }
    
    public function setFinishTime($value)
    {
        
        $day = '2014-11-11';
        
        if ($this->getTargetDate() == 2) {
            $day = '2014-11-12';
        }
        
        $value = $day.' '.date("H:i:s",strtotime($value));
        
        $datetime = new \DateTime($value);
        
        $this->setFinishDateTime($datetime);
        
    
    }
}
