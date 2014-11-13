<?php

/**
 * Application Model
 *
 * @package Librecon\Model\Raw
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

namespace Librecon\Model\Raw;
class Meeting extends ModelAbstract
{

    protected $_statusAcceptedValues = array(
        'pending',
        'canceled',
        'accepted',
    );
    protected $_emailShareAcceptedValues = array(
        '0',
        '1',
    );
    protected $_cellphoneShareAcceptedValues = array(
        '0',
        '1',
    );
    protected $_atRightNowAcceptedValues = array(
        '0',
        '1',
    );
    protected $_atHalfHourAcceptedValues = array(
        '0',
        '1',
    );
    protected $_atOneHourAcceptedValues = array(
        '0',
        '1',
    );

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_id;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_sender;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_receiver;

    /**
     * Database var type timestamp
     *
     * @var string
     */
    protected $_createdAt;

    /**
     * [enum:pending|canceled|accepted]
     * Database var type varchar
     *
     * @var string
     */
    protected $_status;

    /**
     * [enum:0|1]
     * Database var type tinyint
     *
     * @var int
     */
    protected $_emailShare;

    /**
     * [enum:0|1]
     * Database var type tinyint
     *
     * @var int
     */
    protected $_cellphoneShare;

    /**
     * [enum:0|1]
     * Database var type tinyint
     *
     * @var int
     */
    protected $_atRightNow;

    /**
     * [enum:0|1]
     * Database var type tinyint
     *
     * @var int
     */
    protected $_atHalfHour;

    /**
     * [enum:0|1]
     * Database var type tinyint
     *
     * @var int
     */
    protected $_atOneHour;

    /**
     * Database var type datetime
     *
     * @var string
     */
    protected $_responseDate;


    /**
     * Parent relation Meeting_ibfk_1
     *
     * @var \Librecon\Model\Raw\Assistants
     */
    protected $_AssistantsBySender;

    /**
     * Parent relation Meeting_ibfk_2
     *
     * @var \Librecon\Model\Raw\Assistants
     */
    protected $_AssistantsByReceiver;



    protected $_columnsList = array(
        'id'=>'id',
        'sender'=>'sender',
        'receiver'=>'receiver',
        'createdAt'=>'createdAt',
        'status'=>'status',
        'emailShare'=>'emailShare',
        'cellphoneShare'=>'cellphoneShare',
        'atRightNow'=>'atRightNow',
        'atHalfHour'=>'atHalfHour',
        'atOneHour'=>'atOneHour',
        'responseDate'=>'responseDate',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'status'=> array('enum:pending|canceled|accepted'),
            'emailShare'=> array('enum:0|1'),
            'cellphoneShare'=> array('enum:0|1'),
            'atRightNow'=> array('enum:0|1'),
            'atHalfHour'=> array('enum:0|1'),
            'atOneHour'=> array('enum:0|1'),
        ));

        $this->setMultiLangColumnsList(array(
        ));

        $this->setAvailableLangs(array('es', 'en', 'eu'));

        $this->setParentList(array(
            'MeetingIbfk1'=> array(
                    'property' => 'AssistantsBySender',
                    'table_name' => 'Assistants',
                ),
            'MeetingIbfk2'=> array(
                    'property' => 'AssistantsByReceiver',
                    'table_name' => 'Assistants',
                ),
        ));

        $this->setDependentList(array(
        ));




        $this->_defaultValues = array(
            'createdAt' => 'CURRENT_TIMESTAMP',
            'status' => 'pending',
            'emailShare' => '0',
            'cellphoneShare' => '0',
            'atRightNow' => '0',
            'atHalfHour' => '0',
            'atOneHour' => '0',
        );

        $this->_initFileObjects();
        parent::__construct();
    }

    /**
     * This method is called just after parent's constructor
     */
    public function init()
    {
    }
    /**************************************************************************
    ************************** File System Object (FSO)************************
    ***************************************************************************/

    protected function _initFileObjects()
    {

        return $this;
    }

    public function getFileObjects()
    {

        return array();
    }



    /**************************************************************************
    *********************************** /FSO ***********************************
    ***************************************************************************/

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setId($data)
    {

        if ($this->_id != $data) {
            $this->_logChange('id');
        }

        if (!is_null($data)) {
            $this->_id = (int) $data;
        } else {
            $this->_id = $data;
        }
        return $this;
    }

    /**
     * Gets column id
     *
     * @return int
     */
    public function getId()
    {
            return $this->_id;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setSender($data)
    {

        if ($this->_sender != $data) {
            $this->_logChange('sender');
        }

        if (!is_null($data)) {
            $this->_sender = (int) $data;
        } else {
            $this->_sender = $data;
        }
        return $this;
    }

    /**
     * Gets column sender
     *
     * @return int
     */
    public function getSender()
    {
            return $this->_sender;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setReceiver($data)
    {

        if ($this->_receiver != $data) {
            $this->_logChange('receiver');
        }

        if (!is_null($data)) {
            $this->_receiver = (int) $data;
        } else {
            $this->_receiver = $data;
        }
        return $this;
    }

    /**
     * Gets column receiver
     *
     * @return int
     */
    public function getReceiver()
    {
            return $this->_receiver;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setCreatedAt($data)
    {

        if ($data == '0000-00-00 00:00:00') {
            $data = null;
        }

        if ($data === 'CURRENT_TIMESTAMP' || is_null($data)) {
            $data = \Zend_Date::now()->setTimezone('UTC');
        }

        if ($data instanceof \Zend_Date) {

            $data = new \DateTime($data->toString('yyyy-MM-dd HH:mm:ss'), new \DateTimeZone($data->getTimezone()));

        } elseif (!is_null($data) && !$data instanceof \DateTime) {

            $data = new \DateTime($data, new \DateTimeZone('UTC'));
        }

        if ($data instanceof \DateTime && $data->getTimezone()->getName() != 'UTC') {

            $data->setTimezone(new \DateTimeZone('UTC'));
        }


        if ($this->_createdAt != $data) {
            $this->_logChange('createdAt');
        }

        $this->_createdAt = $data;
        return $this;
    }

    /**
     * Gets column createdAt
     *
     * @param boolean $returnZendDate
     * @return Zend_Date|null|string Zend_Date representation of this datetime if enabled, or ISO 8601 string if not
     */
    public function getCreatedAt($returnZendDate = false)
    {
    
        if (is_null($this->_createdAt)) {

            return null;
        }

        if ($returnZendDate) {
            $zendDate = new \Zend_Date($this->_createdAt->getTimestamp(), \Zend_Date::TIMESTAMP);
            $zendDate->setTimezone('UTC');
            return $zendDate;
        }


        return $this->_createdAt->format('Y-m-d H:i:s');

    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setStatus($data)
    {

        if ($this->_status != $data) {
            $this->_logChange('status');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_statusAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for status'));
            }
            $this->_status = (string) $data;
        } else {
            $this->_status = $data;
        }
        return $this;
    }

    /**
     * Gets column status
     *
     * @return string
     */
    public function getStatus()
    {
            return $this->_status;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setEmailShare($data)
    {

        if ($this->_emailShare != $data) {
            $this->_logChange('emailShare');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_emailShareAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for emailShare'));
            }
            $this->_emailShare = (int) $data;
        } else {
            $this->_emailShare = $data;
        }
        return $this;
    }

    /**
     * Gets column emailShare
     *
     * @return int
     */
    public function getEmailShare()
    {
            return $this->_emailShare;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setCellphoneShare($data)
    {

        if ($this->_cellphoneShare != $data) {
            $this->_logChange('cellphoneShare');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_cellphoneShareAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for cellphoneShare'));
            }
            $this->_cellphoneShare = (int) $data;
        } else {
            $this->_cellphoneShare = $data;
        }
        return $this;
    }

    /**
     * Gets column cellphoneShare
     *
     * @return int
     */
    public function getCellphoneShare()
    {
            return $this->_cellphoneShare;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setAtRightNow($data)
    {

        if ($this->_atRightNow != $data) {
            $this->_logChange('atRightNow');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_atRightNowAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for atRightNow'));
            }
            $this->_atRightNow = (int) $data;
        } else {
            $this->_atRightNow = $data;
        }
        return $this;
    }

    /**
     * Gets column atRightNow
     *
     * @return int
     */
    public function getAtRightNow()
    {
            return $this->_atRightNow;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setAtHalfHour($data)
    {

        if ($this->_atHalfHour != $data) {
            $this->_logChange('atHalfHour');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_atHalfHourAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for atHalfHour'));
            }
            $this->_atHalfHour = (int) $data;
        } else {
            $this->_atHalfHour = $data;
        }
        return $this;
    }

    /**
     * Gets column atHalfHour
     *
     * @return int
     */
    public function getAtHalfHour()
    {
            return $this->_atHalfHour;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setAtOneHour($data)
    {

        if ($this->_atOneHour != $data) {
            $this->_logChange('atOneHour');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_atOneHourAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for atOneHour'));
            }
            $this->_atOneHour = (int) $data;
        } else {
            $this->_atOneHour = $data;
        }
        return $this;
    }

    /**
     * Gets column atOneHour
     *
     * @return int
     */
    public function getAtOneHour()
    {
            return $this->_atOneHour;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setResponseDate($data)
    {

        if ($data == '0000-00-00 00:00:00') {
            $data = null;
        }

        if ($data === 'CURRENT_TIMESTAMP') {
            $data = \Zend_Date::now()->setTimezone('UTC');
        }

        if ($data instanceof \Zend_Date) {

            $data = new \DateTime($data->toString('yyyy-MM-dd HH:mm:ss'), new \DateTimeZone($data->getTimezone()));

        } elseif (!is_null($data) && !$data instanceof \DateTime) {

            $data = new \DateTime($data, new \DateTimeZone('UTC'));
        }

        if ($data instanceof \DateTime && $data->getTimezone()->getName() != 'UTC') {

            $data->setTimezone(new \DateTimeZone('UTC'));
        }


        if ($this->_responseDate != $data) {
            $this->_logChange('responseDate');
        }

        $this->_responseDate = $data;
        return $this;
    }

    /**
     * Gets column responseDate
     *
     * @param boolean $returnZendDate
     * @return Zend_Date|null|string Zend_Date representation of this datetime if enabled, or ISO 8601 string if not
     */
    public function getResponseDate($returnZendDate = false)
    {
    
        if (is_null($this->_responseDate)) {

            return null;
        }

        if ($returnZendDate) {
            $zendDate = new \Zend_Date($this->_responseDate->getTimestamp(), \Zend_Date::TIMESTAMP);
            $zendDate->setTimezone('UTC');
            return $zendDate;
        }


        return $this->_responseDate->format('Y-m-d H:i:s');

    }


    /**
     * Sets parent relation Sender
     *
     * @param \Librecon\Model\Raw\Assistants $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setAssistantsBySender(\Librecon\Model\Raw\Assistants $data)
    {
        $this->_AssistantsBySender = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setSender($primaryKey);
        }

        $this->_setLoaded('MeetingIbfk1');
        return $this;
    }

    /**
     * Gets parent Sender
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Assistants
     */
    public function getAssistantsBySender($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'MeetingIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_AssistantsBySender = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_AssistantsBySender;
    }

    /**
     * Sets parent relation Receiver
     *
     * @param \Librecon\Model\Raw\Assistants $data
     * @return \Librecon\Model\Raw\Meeting
     */
    public function setAssistantsByReceiver(\Librecon\Model\Raw\Assistants $data)
    {
        $this->_AssistantsByReceiver = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setReceiver($primaryKey);
        }

        $this->_setLoaded('MeetingIbfk2');
        return $this;
    }

    /**
     * Gets parent Receiver
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Assistants
     */
    public function getAssistantsByReceiver($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'MeetingIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_AssistantsByReceiver = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_AssistantsByReceiver;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\Meeting
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Meeting')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Meeting);

            } else {

                 new \Exception("Not a valid mapper class found");
            }

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(false);
        }

        return $this->_mapper;
    }

    /**
     * Returns the validator class for this model
     *
     * @return null | \Librecon\Model\Validator\Meeting
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Meeting')) {

                $this->setValidator(new \Librecon\Validator\Meeting);
            }
        }

        return $this->_validator;
    }

    public function setFromArray($data)
    {
        return $this->getMapper()->loadModel($data, $this);
    }

    /**
     * Deletes current row by deleting the row that matches the primary key
     *
     * @see \Mapper\Sql\Meeting::delete
     * @return int|boolean Number of rows deleted or boolean if doing soft delete
     */
    public function deleteRowByPrimaryKey()
    {
        if ($this->getId() === null) {
            $this->_logger->log('The value for Id cannot be null in deleteRowByPrimaryKey for ' . get_class($this), \Zend_Log::ERR);
            throw new \Exception('Primary Key does not contain a value');
        }

        return $this->getMapper()->getDbTable()->delete(
            'id = ' .
             $this->getMapper()->getDbTable()->getAdapter()->quote($this->getId())
        );
    }
}
