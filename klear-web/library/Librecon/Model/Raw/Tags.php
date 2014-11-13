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
class Tags extends ModelAbstract
{


    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_id;

    /**
     * [ml]
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
    protected $_nameEu;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_nameEn;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_nameEs;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_color;

    /**
     * Database var type timestamp
     *
     * @var string
     */
    protected $_lastModified;



    /**
     * Dependent relation RelTagScheduele_ibfk_1
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\RelTagScheduele[]
     */
    protected $_RelTagScheduele;

    /**
     * Dependent relation RelTagSpeaker_ibfk_1
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\RelTagSpeaker[]
     */
    protected $_RelTagSpeaker;


    protected $_columnsList = array(
        'id'=>'id',
        'name'=>'name',
        'name_eu'=>'nameEu',
        'name_en'=>'nameEn',
        'name_es'=>'nameEs',
        'color'=>'color',
        'lastModified'=>'lastModified',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'name'=> array('ml'),
        ));

        $this->setMultiLangColumnsList(array(
            'name'=>'Name',
        ));

        $this->setAvailableLangs(array('es', 'en', 'eu'));

        $this->setParentList(array(
        ));

        $this->setDependentList(array(
            'RelTagSchedueleIbfk1' => array(
                    'property' => 'RelTagScheduele',
                    'table_name' => 'RelTagScheduele',
                ),
            'RelTagSpeakerIbfk1' => array(
                    'property' => 'RelTagSpeaker',
                    'table_name' => 'RelTagSpeaker',
                ),
        ));


        $this->setOnDeleteSetNullRelationships(array(
            'RelTagScheduele_ibfk_1',
            'RelTagSpeaker_ibfk_1'
        ));


        $this->_defaultValues = array(
            'lastModified' => 'CURRENT_TIMESTAMP',
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
     * @return \Librecon\Model\Raw\Tags
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
     * @return \Librecon\Model\Raw\Tags
     */
    public function setName($data, $language = '')
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }

        $language = $this->_getCurrentLanguage($language);

        $methodName = "setName". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            $this->_name = $data;
            return $this;
        }
        $this->$methodName($data);
        return $this;
    }

    /**
     * Gets column name
     *
     * @return string
     */
    public function getName($language = '')
    {
    
        $language = $this->_getCurrentLanguage($language);

        $methodName = "getName". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            return $this->_name;
        }

        return $this->$methodName();

    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Tags
     */
    public function setNameEu($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_nameEu != $data) {
            $this->_logChange('nameEu');
        }

        if (!is_null($data)) {
            $this->_nameEu = (string) $data;
        } else {
            $this->_nameEu = $data;
        }
        return $this;
    }

    /**
     * Gets column name_eu
     *
     * @return string
     */
    public function getNameEu()
    {
            return $this->_nameEu;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Tags
     */
    public function setNameEn($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_nameEn != $data) {
            $this->_logChange('nameEn');
        }

        if (!is_null($data)) {
            $this->_nameEn = (string) $data;
        } else {
            $this->_nameEn = $data;
        }
        return $this;
    }

    /**
     * Gets column name_en
     *
     * @return string
     */
    public function getNameEn()
    {
            return $this->_nameEn;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Tags
     */
    public function setNameEs($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_nameEs != $data) {
            $this->_logChange('nameEs');
        }

        if (!is_null($data)) {
            $this->_nameEs = (string) $data;
        } else {
            $this->_nameEs = $data;
        }
        return $this;
    }

    /**
     * Gets column name_es
     *
     * @return string
     */
    public function getNameEs()
    {
            return $this->_nameEs;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Tags
     */
    public function setColor($data)
    {

        if ($this->_color != $data) {
            $this->_logChange('color');
        }

        if (!is_null($data)) {
            $this->_color = (string) $data;
        } else {
            $this->_color = $data;
        }
        return $this;
    }

    /**
     * Gets column color
     *
     * @return string
     */
    public function getColor()
    {
            return $this->_color;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Tags
     */
    public function setLastModified($data)
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


        if ($this->_lastModified != $data) {
            $this->_logChange('lastModified');
        }

        $this->_lastModified = $data;
        return $this;
    }

    /**
     * Gets column lastModified
     *
     * @param boolean $returnZendDate
     * @return Zend_Date|null|string Zend_Date representation of this datetime if enabled, or ISO 8601 string if not
     */
    public function getLastModified($returnZendDate = false)
    {
    
        if (is_null($this->_lastModified)) {

            return null;
        }

        if ($returnZendDate) {
            $zendDate = new \Zend_Date($this->_lastModified->getTimestamp(), \Zend_Date::TIMESTAMP);
            $zendDate->setTimezone('UTC');
            return $zendDate;
        }


        return $this->_lastModified->format('Y-m-d H:i:s');

    }


    /**
     * Sets dependent relations RelTagScheduele_ibfk_1
     *
     * @param array $data An array of \Librecon\Model\Raw\RelTagScheduele
     * @return \Librecon\Model\Raw\Tags
     */
    public function setRelTagScheduele(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_RelTagScheduele === null) {

                $this->getRelTagScheduele();
            }

            $oldRelations = $this->_RelTagScheduele;

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

        $this->_RelTagScheduele = array();

        foreach ($data as $object) {
            $this->addRelTagScheduele($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations RelTagScheduele_ibfk_1
     *
     * @param \Librecon\Model\Raw\RelTagScheduele $data
     * @return \Librecon\Model\Raw\Tags
     */
    public function addRelTagScheduele(\Librecon\Model\Raw\RelTagScheduele $data)
    {
        $this->_RelTagScheduele[] = $data;
        $this->_setLoaded('RelTagSchedueleIbfk1');
        return $this;
    }

    /**
     * Gets dependent RelTagScheduele_ibfk_1
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\RelTagScheduele
     */
    public function getRelTagScheduele($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSchedueleIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_RelTagScheduele = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_RelTagScheduele;
    }

    /**
     * Sets dependent relations RelTagSpeaker_ibfk_1
     *
     * @param array $data An array of \Librecon\Model\Raw\RelTagSpeaker
     * @return \Librecon\Model\Raw\Tags
     */
    public function setRelTagSpeaker(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_RelTagSpeaker === null) {

                $this->getRelTagSpeaker();
            }

            $oldRelations = $this->_RelTagSpeaker;

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

        $this->_RelTagSpeaker = array();

        foreach ($data as $object) {
            $this->addRelTagSpeaker($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations RelTagSpeaker_ibfk_1
     *
     * @param \Librecon\Model\Raw\RelTagSpeaker $data
     * @return \Librecon\Model\Raw\Tags
     */
    public function addRelTagSpeaker(\Librecon\Model\Raw\RelTagSpeaker $data)
    {
        $this->_RelTagSpeaker[] = $data;
        $this->_setLoaded('RelTagSpeakerIbfk1');
        return $this;
    }

    /**
     * Gets dependent RelTagSpeaker_ibfk_1
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\RelTagSpeaker
     */
    public function getRelTagSpeaker($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSpeakerIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_RelTagSpeaker = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_RelTagSpeaker;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\Tags
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Tags')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Tags);

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
     * @return null | \Librecon\Model\Validator\Tags
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Tags')) {

                $this->setValidator(new \Librecon\Validator\Tags);
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
     * @see \Mapper\Sql\Tags::delete
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
