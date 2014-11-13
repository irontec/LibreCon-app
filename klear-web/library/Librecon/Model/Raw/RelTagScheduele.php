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
class RelTagScheduele extends ModelAbstract
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
    protected $_idScheduele;


    /**
     * Parent relation RelTagScheduele_ibfk_1
     *
     * @var \Librecon\Model\Raw\Tags
     */
    protected $_Tags;

    /**
     * Parent relation RelTagScheduele_ibfk_2
     *
     * @var \Librecon\Model\Raw\Schedule
     */
    protected $_Schedule;



    protected $_columnsList = array(
        'id'=>'id',
        'idTag'=>'idTag',
        'idScheduele'=>'idScheduele',
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
            'RelTagSchedueleIbfk1'=> array(
                    'property' => 'Tags',
                    'table_name' => 'Tags',
                ),
            'RelTagSchedueleIbfk2'=> array(
                    'property' => 'Schedule',
                    'table_name' => 'Schedule',
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
     * @return \Librecon\Model\Raw\RelTagScheduele
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
     * @return \Librecon\Model\Raw\RelTagScheduele
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
     * @return \Librecon\Model\Raw\RelTagScheduele
     */
    public function setIdScheduele($data)
    {

        if ($this->_idScheduele != $data) {
            $this->_logChange('idScheduele');
        }

        if (!is_null($data)) {
            $this->_idScheduele = (int) $data;
        } else {
            $this->_idScheduele = $data;
        }
        return $this;
    }

    /**
     * Gets column idScheduele
     *
     * @return int
     */
    public function getIdScheduele()
    {
            return $this->_idScheduele;
    }


    /**
     * Sets parent relation IdTag
     *
     * @param \Librecon\Model\Raw\Tags $data
     * @return \Librecon\Model\Raw\RelTagScheduele
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

        $this->_setLoaded('RelTagSchedueleIbfk1');
        return $this;
    }

    /**
     * Gets parent IdTag
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Tags
     */
    public function getTags($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSchedueleIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_Tags = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_Tags;
    }

    /**
     * Sets parent relation IdScheduele
     *
     * @param \Librecon\Model\Raw\Schedule $data
     * @return \Librecon\Model\Raw\RelTagScheduele
     */
    public function setSchedule(\Librecon\Model\Raw\Schedule $data)
    {
        $this->_Schedule = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setIdScheduele($primaryKey);
        }

        $this->_setLoaded('RelTagSchedueleIbfk2');
        return $this;
    }

    /**
     * Gets parent IdScheduele
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Schedule
     */
    public function getSchedule($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSchedueleIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_Schedule = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_Schedule;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\RelTagScheduele
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\RelTagScheduele')) {

                $this->setMapper(new \Librecon\Mapper\Sql\RelTagScheduele);

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
     * @return null | \Librecon\Model\Validator\RelTagScheduele
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\RelTagScheduele')) {

                $this->setValidator(new \Librecon\Validator\RelTagScheduele);
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
     * @see \Mapper\Sql\RelTagScheduele::delete
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
