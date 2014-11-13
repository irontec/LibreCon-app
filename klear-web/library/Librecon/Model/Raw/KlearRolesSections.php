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
class KlearRolesSections extends ModelAbstract
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
    protected $_klearRoleId;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_klearSectionId;


    /**
     * Parent relation KlearRolesSections_ibfk_1
     *
     * @var \Librecon\Model\Raw\KlearRoles
     */
    protected $_KlearRole;

    /**
     * Parent relation KlearRolesSections_ibfk_2
     *
     * @var \Librecon\Model\Raw\KlearSections
     */
    protected $_KlearSection;



    protected $_columnsList = array(
        'id'=>'id',
        'klearRoleId'=>'klearRoleId',
        'klearSectionId'=>'klearSectionId',
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
            'KlearRolesSectionsIbfk1'=> array(
                    'property' => 'KlearRole',
                    'table_name' => 'KlearRoles',
                ),
            'KlearRolesSectionsIbfk2'=> array(
                    'property' => 'KlearSection',
                    'table_name' => 'KlearSections',
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
     * @return \Librecon\Model\Raw\KlearRolesSections
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
     * @return \Librecon\Model\Raw\KlearRolesSections
     */
    public function setKlearRoleId($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_klearRoleId != $data) {
            $this->_logChange('klearRoleId');
        }

        if (!is_null($data)) {
            $this->_klearRoleId = (int) $data;
        } else {
            $this->_klearRoleId = $data;
        }
        return $this;
    }

    /**
     * Gets column klearRoleId
     *
     * @return int
     */
    public function getKlearRoleId()
    {
            return $this->_klearRoleId;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\KlearRolesSections
     */
    public function setKlearSectionId($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_klearSectionId != $data) {
            $this->_logChange('klearSectionId');
        }

        if (!is_null($data)) {
            $this->_klearSectionId = (int) $data;
        } else {
            $this->_klearSectionId = $data;
        }
        return $this;
    }

    /**
     * Gets column klearSectionId
     *
     * @return int
     */
    public function getKlearSectionId()
    {
            return $this->_klearSectionId;
    }


    /**
     * Sets parent relation KlearRole
     *
     * @param \Librecon\Model\Raw\KlearRoles $data
     * @return \Librecon\Model\Raw\KlearRolesSections
     */
    public function setKlearRole(\Librecon\Model\Raw\KlearRoles $data)
    {
        $this->_KlearRole = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setKlearRoleId($primaryKey);
        }

        $this->_setLoaded('KlearRolesSectionsIbfk1');
        return $this;
    }

    /**
     * Gets parent KlearRole
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\KlearRoles
     */
    public function getKlearRole($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'KlearRolesSectionsIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_KlearRole = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_KlearRole;
    }

    /**
     * Sets parent relation KlearSection
     *
     * @param \Librecon\Model\Raw\KlearSections $data
     * @return \Librecon\Model\Raw\KlearRolesSections
     */
    public function setKlearSection(\Librecon\Model\Raw\KlearSections $data)
    {
        $this->_KlearSection = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setKlearSectionId($primaryKey);
        }

        $this->_setLoaded('KlearRolesSectionsIbfk2');
        return $this;
    }

    /**
     * Gets parent KlearSection
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\KlearSections
     */
    public function getKlearSection($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'KlearRolesSectionsIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_KlearSection = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_KlearSection;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\KlearRolesSections
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\KlearRolesSections')) {

                $this->setMapper(new \Librecon\Mapper\Sql\KlearRolesSections);

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
     * @return null | \Librecon\Model\Validator\KlearRolesSections
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\KlearRolesSections')) {

                $this->setValidator(new \Librecon\Validator\KlearRolesSections);
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
     * @see \Mapper\Sql\KlearRolesSections::delete
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
