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
class RelTagSpeaker extends ModelAbstract
{


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
    protected $_idTag;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_idSpeaker;


    /**
     * Parent relation RelTagSpeaker_ibfk_1
     *
     * @var \Librecon\Model\Raw\Tags
     */
    protected $_Tags;

    /**
     * Parent relation RelTagSpeaker_ibfk_2
     *
     * @var \Librecon\Model\Raw\Speaker
     */
    protected $_Speaker;



    protected $_columnsList = array(
        'id'=>'id',
        'idTag'=>'idTag',
        'idSpeaker'=>'idSpeaker',
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
            'RelTagSpeakerIbfk1'=> array(
                    'property' => 'Tags',
                    'table_name' => 'Tags',
                ),
            'RelTagSpeakerIbfk2'=> array(
                    'property' => 'Speaker',
                    'table_name' => 'Speaker',
                ),
        ));

        $this->setDependentList(array(
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
     * @return \Librecon\Model\Raw\RelTagSpeaker
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
     * @return \Librecon\Model\Raw\RelTagSpeaker
     */
    public function setIdTag($data)
    {

        if ($this->_idTag != $data) {
            $this->_logChange('idTag');
        }

        if (!is_null($data)) {
            $this->_idTag = (int) $data;
        } else {
            $this->_idTag = $data;
        }
        return $this;
    }

    /**
     * Gets column idTag
     *
     * @return int
     */
    public function getIdTag()
    {
            return $this->_idTag;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\RelTagSpeaker
     */
    public function setIdSpeaker($data)
    {

        if ($this->_idSpeaker != $data) {
            $this->_logChange('idSpeaker');
        }

        if (!is_null($data)) {
            $this->_idSpeaker = (int) $data;
        } else {
            $this->_idSpeaker = $data;
        }
        return $this;
    }

    /**
     * Gets column idSpeaker
     *
     * @return int
     */
    public function getIdSpeaker()
    {
            return $this->_idSpeaker;
    }


    /**
     * Sets parent relation IdTag
     *
     * @param \Librecon\Model\Raw\Tags $data
     * @return \Librecon\Model\Raw\RelTagSpeaker
     */
    public function setTags(\Librecon\Model\Raw\Tags $data)
    {
        $this->_Tags = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setIdTag($primaryKey);
        }

        $this->_setLoaded('RelTagSpeakerIbfk1');
        return $this;
    }

    /**
     * Gets parent IdTag
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Tags
     */
    public function getTags($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSpeakerIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_Tags = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_Tags;
    }

    /**
     * Sets parent relation IdSpeaker
     *
     * @param \Librecon\Model\Raw\Speaker $data
     * @return \Librecon\Model\Raw\RelTagSpeaker
     */
    public function setSpeaker(\Librecon\Model\Raw\Speaker $data)
    {
        $this->_Speaker = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setIdSpeaker($primaryKey);
        }

        $this->_setLoaded('RelTagSpeakerIbfk2');
        return $this;
    }

    /**
     * Gets parent IdSpeaker
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Speaker
     */
    public function getSpeaker($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSpeakerIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_Speaker = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_Speaker;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\RelTagSpeaker
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\RelTagSpeaker')) {

                $this->setMapper(new \Librecon\Mapper\Sql\RelTagSpeaker);

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
     * @return null | \Librecon\Model\Validator\RelTagSpeaker
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\RelTagSpeaker')) {

                $this->setValidator(new \Librecon\Validator\RelTagSpeaker);
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
     * @see \Mapper\Sql\RelTagSpeaker::delete
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
