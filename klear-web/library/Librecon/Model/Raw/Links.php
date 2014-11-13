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
class Links extends ModelAbstract
{


    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_id;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_name;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_type;



    /**
     * Dependent relation Schedule_ibfk_1
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Schedule[]
     */
    protected $_ScheduleByLink1Type;

    /**
     * Dependent relation Schedule_ibfk_2
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Schedule[]
     */
    protected $_ScheduleByLink2Type;

    /**
     * Dependent relation Schedule_ibfk_3
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Schedule[]
     */
    protected $_ScheduleByLink3Type;

    /**
     * Dependent relation Schedule_ibfk_4
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Schedule[]
     */
    protected $_ScheduleByLink4Type;

    /**
     * Dependent relation Speaker_ibfk_1
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Speaker[]
     */
    protected $_SpeakerByLink1Type;

    /**
     * Dependent relation Speaker_ibfk_2
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Speaker[]
     */
    protected $_SpeakerByLink2Type;

    /**
     * Dependent relation Speaker_ibfk_3
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Speaker[]
     */
    protected $_SpeakerByLink3Type;

    /**
     * Dependent relation Speaker_ibfk_4
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Speaker[]
     */
    protected $_SpeakerByLink4Type;


    protected $_columnsList = array(
        'id'=>'id',
        'name'=>'name',
        'type'=>'type',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
        ));

        $this->setMultiLangColumnsList(array(
        ));

        $this->setAvailableLangs(array('es', 'en', 'eu'));

        $this->setParentList(array(
        ));

        $this->setDependentList(array(
            'ScheduleIbfk1' => array(
                    'property' => 'ScheduleByLink1Type',
                    'table_name' => 'Schedule',
                ),
            'ScheduleIbfk2' => array(
                    'property' => 'ScheduleByLink2Type',
                    'table_name' => 'Schedule',
                ),
            'ScheduleIbfk3' => array(
                    'property' => 'ScheduleByLink3Type',
                    'table_name' => 'Schedule',
                ),
            'ScheduleIbfk4' => array(
                    'property' => 'ScheduleByLink4Type',
                    'table_name' => 'Schedule',
                ),
            'SpeakerIbfk1' => array(
                    'property' => 'SpeakerByLink1Type',
                    'table_name' => 'Speaker',
                ),
            'SpeakerIbfk2' => array(
                    'property' => 'SpeakerByLink2Type',
                    'table_name' => 'Speaker',
                ),
            'SpeakerIbfk3' => array(
                    'property' => 'SpeakerByLink3Type',
                    'table_name' => 'Speaker',
                ),
            'SpeakerIbfk4' => array(
                    'property' => 'SpeakerByLink4Type',
                    'table_name' => 'Speaker',
                ),
        ));




        $this->_defaultValues = array(
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
     * @return \Librecon\Model\Raw\Links
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
     * @param string $data
     * @return \Librecon\Model\Raw\Links
     */
    public function setName($data)
    {

        if ($this->_name != $data) {
            $this->_logChange('name');
        }

        if (!is_null($data)) {
            $this->_name = (string) $data;
        } else {
            $this->_name = $data;
        }
        return $this;
    }

    /**
     * Gets column name
     *
     * @return string
     */
    public function getName()
    {
            return $this->_name;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Links
     */
    public function setType($data)
    {

        if ($this->_type != $data) {
            $this->_logChange('type');
        }

        if (!is_null($data)) {
            $this->_type = (string) $data;
        } else {
            $this->_type = $data;
        }
        return $this;
    }

    /**
     * Gets column type
     *
     * @return string
     */
    public function getType()
    {
            return $this->_type;
    }


    /**
     * Sets dependent relations Schedule_ibfk_1
     *
     * @param array $data An array of \Librecon\Model\Raw\Schedule
     * @return \Librecon\Model\Raw\Links
     */
    public function setScheduleByLink1Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_ScheduleByLink1Type === null) {

                $this->getScheduleByLink1Type();
            }

            $oldRelations = $this->_ScheduleByLink1Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_ScheduleByLink1Type = array();

        foreach ($data as $object) {
            $this->addScheduleByLink1Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Schedule_ibfk_1
     *
     * @param \Librecon\Model\Raw\Schedule $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addScheduleByLink1Type(\Librecon\Model\Raw\Schedule $data)
    {
        $this->_ScheduleByLink1Type[] = $data;
        $this->_setLoaded('ScheduleIbfk1');
        return $this;
    }

    /**
     * Gets dependent Schedule_ibfk_1
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Schedule
     */
    public function getScheduleByLink1Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_ScheduleByLink1Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_ScheduleByLink1Type;
    }

    /**
     * Sets dependent relations Schedule_ibfk_2
     *
     * @param array $data An array of \Librecon\Model\Raw\Schedule
     * @return \Librecon\Model\Raw\Links
     */
    public function setScheduleByLink2Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_ScheduleByLink2Type === null) {

                $this->getScheduleByLink2Type();
            }

            $oldRelations = $this->_ScheduleByLink2Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_ScheduleByLink2Type = array();

        foreach ($data as $object) {
            $this->addScheduleByLink2Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Schedule_ibfk_2
     *
     * @param \Librecon\Model\Raw\Schedule $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addScheduleByLink2Type(\Librecon\Model\Raw\Schedule $data)
    {
        $this->_ScheduleByLink2Type[] = $data;
        $this->_setLoaded('ScheduleIbfk2');
        return $this;
    }

    /**
     * Gets dependent Schedule_ibfk_2
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Schedule
     */
    public function getScheduleByLink2Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_ScheduleByLink2Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_ScheduleByLink2Type;
    }

    /**
     * Sets dependent relations Schedule_ibfk_3
     *
     * @param array $data An array of \Librecon\Model\Raw\Schedule
     * @return \Librecon\Model\Raw\Links
     */
    public function setScheduleByLink3Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_ScheduleByLink3Type === null) {

                $this->getScheduleByLink3Type();
            }

            $oldRelations = $this->_ScheduleByLink3Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_ScheduleByLink3Type = array();

        foreach ($data as $object) {
            $this->addScheduleByLink3Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Schedule_ibfk_3
     *
     * @param \Librecon\Model\Raw\Schedule $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addScheduleByLink3Type(\Librecon\Model\Raw\Schedule $data)
    {
        $this->_ScheduleByLink3Type[] = $data;
        $this->_setLoaded('ScheduleIbfk3');
        return $this;
    }

    /**
     * Gets dependent Schedule_ibfk_3
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Schedule
     */
    public function getScheduleByLink3Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk3';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_ScheduleByLink3Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_ScheduleByLink3Type;
    }

    /**
     * Sets dependent relations Schedule_ibfk_4
     *
     * @param array $data An array of \Librecon\Model\Raw\Schedule
     * @return \Librecon\Model\Raw\Links
     */
    public function setScheduleByLink4Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_ScheduleByLink4Type === null) {

                $this->getScheduleByLink4Type();
            }

            $oldRelations = $this->_ScheduleByLink4Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_ScheduleByLink4Type = array();

        foreach ($data as $object) {
            $this->addScheduleByLink4Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Schedule_ibfk_4
     *
     * @param \Librecon\Model\Raw\Schedule $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addScheduleByLink4Type(\Librecon\Model\Raw\Schedule $data)
    {
        $this->_ScheduleByLink4Type[] = $data;
        $this->_setLoaded('ScheduleIbfk4');
        return $this;
    }

    /**
     * Gets dependent Schedule_ibfk_4
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Schedule
     */
    public function getScheduleByLink4Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk4';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_ScheduleByLink4Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_ScheduleByLink4Type;
    }

    /**
     * Sets dependent relations Speaker_ibfk_1
     *
     * @param array $data An array of \Librecon\Model\Raw\Speaker
     * @return \Librecon\Model\Raw\Links
     */
    public function setSpeakerByLink1Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_SpeakerByLink1Type === null) {

                $this->getSpeakerByLink1Type();
            }

            $oldRelations = $this->_SpeakerByLink1Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_SpeakerByLink1Type = array();

        foreach ($data as $object) {
            $this->addSpeakerByLink1Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Speaker_ibfk_1
     *
     * @param \Librecon\Model\Raw\Speaker $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addSpeakerByLink1Type(\Librecon\Model\Raw\Speaker $data)
    {
        $this->_SpeakerByLink1Type[] = $data;
        $this->_setLoaded('SpeakerIbfk1');
        return $this;
    }

    /**
     * Gets dependent Speaker_ibfk_1
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Speaker
     */
    public function getSpeakerByLink1Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_SpeakerByLink1Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_SpeakerByLink1Type;
    }

    /**
     * Sets dependent relations Speaker_ibfk_2
     *
     * @param array $data An array of \Librecon\Model\Raw\Speaker
     * @return \Librecon\Model\Raw\Links
     */
    public function setSpeakerByLink2Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_SpeakerByLink2Type === null) {

                $this->getSpeakerByLink2Type();
            }

            $oldRelations = $this->_SpeakerByLink2Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_SpeakerByLink2Type = array();

        foreach ($data as $object) {
            $this->addSpeakerByLink2Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Speaker_ibfk_2
     *
     * @param \Librecon\Model\Raw\Speaker $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addSpeakerByLink2Type(\Librecon\Model\Raw\Speaker $data)
    {
        $this->_SpeakerByLink2Type[] = $data;
        $this->_setLoaded('SpeakerIbfk2');
        return $this;
    }

    /**
     * Gets dependent Speaker_ibfk_2
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Speaker
     */
    public function getSpeakerByLink2Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_SpeakerByLink2Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_SpeakerByLink2Type;
    }

    /**
     * Sets dependent relations Speaker_ibfk_3
     *
     * @param array $data An array of \Librecon\Model\Raw\Speaker
     * @return \Librecon\Model\Raw\Links
     */
    public function setSpeakerByLink3Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_SpeakerByLink3Type === null) {

                $this->getSpeakerByLink3Type();
            }

            $oldRelations = $this->_SpeakerByLink3Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_SpeakerByLink3Type = array();

        foreach ($data as $object) {
            $this->addSpeakerByLink3Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Speaker_ibfk_3
     *
     * @param \Librecon\Model\Raw\Speaker $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addSpeakerByLink3Type(\Librecon\Model\Raw\Speaker $data)
    {
        $this->_SpeakerByLink3Type[] = $data;
        $this->_setLoaded('SpeakerIbfk3');
        return $this;
    }

    /**
     * Gets dependent Speaker_ibfk_3
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Speaker
     */
    public function getSpeakerByLink3Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk3';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_SpeakerByLink3Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_SpeakerByLink3Type;
    }

    /**
     * Sets dependent relations Speaker_ibfk_4
     *
     * @param array $data An array of \Librecon\Model\Raw\Speaker
     * @return \Librecon\Model\Raw\Links
     */
    public function setSpeakerByLink4Type(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_SpeakerByLink4Type === null) {

                $this->getSpeakerByLink4Type();
            }

            $oldRelations = $this->_SpeakerByLink4Type;

            if (is_array($oldRelations)) {

                $dataPKs = array();

                foreach ($data as $newItem) {

                    if (is_numeric($pk = $newItem->getPrimaryKey())) {

                        $dataPKs[] = $pk;
                    }
                }

                foreach ($oldRelations as $oldItem) {

                    if (!in_array($oldItem->getPrimaryKey(), $dataPKs)) {

                        $this->_orphans[] = $oldItem;
                    }
                }
            }
        }

        $this->_SpeakerByLink4Type = array();

        foreach ($data as $object) {
            $this->addSpeakerByLink4Type($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Speaker_ibfk_4
     *
     * @param \Librecon\Model\Raw\Speaker $data
     * @return \Librecon\Model\Raw\Links
     */
    public function addSpeakerByLink4Type(\Librecon\Model\Raw\Speaker $data)
    {
        $this->_SpeakerByLink4Type[] = $data;
        $this->_setLoaded('SpeakerIbfk4');
        return $this;
    }

    /**
     * Gets dependent Speaker_ibfk_4
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Speaker
     */
    public function getSpeakerByLink4Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk4';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_SpeakerByLink4Type = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_SpeakerByLink4Type;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\Links
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Links')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Links);

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
     * @return null | \Librecon\Model\Validator\Links
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Links')) {

                $this->setValidator(new \Librecon\Validator\Links);
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
     * @see \Mapper\Sql\Links::delete
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
