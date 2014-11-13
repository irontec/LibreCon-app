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
class KlearSections extends ModelAbstract
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
    protected $_description;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_identifier;



    /**
     * Dependent relation KlearRolesSections_ibfk_2
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\KlearRolesSections[]
     */
    protected $_KlearRolesSections;


    protected $_columnsList = array(
        'id'=>'id',
        'name'=>'name',
        'description'=>'description',
        'identifier'=>'identifier',
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
            'KlearRolesSectionsIbfk2' => array(
                    'property' => 'KlearRolesSections',
                    'table_name' => 'KlearRolesSections',
                ),
        ));

        $this->setOnDeleteCascadeRelationships(array(
            'KlearRolesSections_ibfk_2'
        ));



        $this->_defaultValues = array(
            'description' => '',
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
     * @return \Librecon\Model\Raw\KlearSections
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
     * @return \Librecon\Model\Raw\KlearSections
     */
    public function setName($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
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
     * @return \Librecon\Model\Raw\KlearSections
     */
    public function setDescription($data)
    {

        if ($this->_description != $data) {
            $this->_logChange('description');
        }

        if (!is_null($data)) {
            $this->_description = (string) $data;
        } else {
            $this->_description = $data;
        }
        return $this;
    }

    /**
     * Gets column description
     *
     * @return string
     */
    public function getDescription()
    {
            return $this->_description;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\KlearSections
     */
    public function setIdentifier($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_identifier != $data) {
            $this->_logChange('identifier');
        }

        if (!is_null($data)) {
            $this->_identifier = (string) $data;
        } else {
            $this->_identifier = $data;
        }
        return $this;
    }

    /**
     * Gets column identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
            return $this->_identifier;
    }


    /**
     * Sets dependent relations KlearRolesSections_ibfk_2
     *
     * @param array $data An array of \Librecon\Model\Raw\KlearRolesSections
     * @return \Librecon\Model\Raw\KlearSections
     */
    public function setKlearRolesSections(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_KlearRolesSections === null) {

                $this->getKlearRolesSections();
            }

            $oldRelations = $this->_KlearRolesSections;

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

        $this->_KlearRolesSections = array();

        foreach ($data as $object) {
            $this->addKlearRolesSections($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations KlearRolesSections_ibfk_2
     *
     * @param \Librecon\Model\Raw\KlearRolesSections $data
     * @return \Librecon\Model\Raw\KlearSections
     */
    public function addKlearRolesSections(\Librecon\Model\Raw\KlearRolesSections $data)
    {
        $this->_KlearRolesSections[] = $data;
        $this->_setLoaded('KlearRolesSectionsIbfk2');
        return $this;
    }

    /**
     * Gets dependent KlearRolesSections_ibfk_2
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\KlearRolesSections
     */
    public function getKlearRolesSections($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'KlearRolesSectionsIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_KlearRolesSections = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_KlearRolesSections;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\KlearSections
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\KlearSections')) {

                $this->setMapper(new \Librecon\Mapper\Sql\KlearSections);

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
     * @return null | \Librecon\Model\Validator\KlearSections
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\KlearSections')) {

                $this->setValidator(new \Librecon\Validator\KlearSections);
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
     * @see \Mapper\Sql\KlearSections::delete
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
