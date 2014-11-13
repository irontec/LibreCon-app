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
class Expositor extends ModelAbstract
{
    /*
     * @var \Iron_Model_Fso
     */
    protected $_logoFso;


    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_id;

    /**
     * [FSO]
     * Database var type int
     *
     * @var int
     */
    protected $_logoFileSize;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_logoMimeType;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_logoBaseName;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_companyName;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_orderField;

    /**
     * [ml]
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_description;

    /**
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_descriptionEu;

    /**
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_descriptionEn;

    /**
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_descriptionEs;

    /**
     * Database var type timestamp
     *
     * @var string
     */
    protected $_lastModified;




    protected $_columnsList = array(
        'id'=>'id',
        'logoFileSize'=>'logoFileSize',
        'logoMimeType'=>'logoMimeType',
        'logoBaseName'=>'logoBaseName',
        'companyName'=>'companyName',
        'orderField'=>'orderField',
        'description'=>'description',
        'description_eu'=>'descriptionEu',
        'description_en'=>'descriptionEn',
        'description_es'=>'descriptionEs',
        'lastModified'=>'lastModified',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'logoFileSize'=> array('FSO'),
            'description'=> array('ml'),
        ));

        $this->setMultiLangColumnsList(array(
            'description'=>'Description',
        ));

        $this->setAvailableLangs(array('es', 'en', 'eu'));

        $this->setParentList(array(
        ));

        $this->setDependentList(array(
        ));




        $this->_defaultValues = array(
            'orderField' => '0',
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
        $this->_logoFso = new \Iron_Model_Fso($this, $this->getLogoSpecs());

        return $this;
    }

    public function getFileObjects()
    {

        return array('logo');
    }

    public function getLogoSpecs()
    {
        return array(
            'basePath' => 'logo',
            'sizeName' => 'logoFileSize',
            'mimeName' => 'logoMimeType',
            'baseNameName' => 'logoBaseName',
        );
    }

    public function putLogo($filePath = '',$baseName = '')
    {
        $this->_logoFso->put($filePath);

        if (!empty($baseName)) {

            $this->_logoFso->setBaseName($baseName);
        }
    }

    public function fetchLogo($autoload = true)
    {
        if ($autoload === true && $this->getlogoFileSize() > 0) {

            $this->_logoFso->fetch();
        }

        return $this->_logoFso;
    }

    public function removeLogo()
    {
        $this->_logoFso->remove();

        $this->_logoFso = null;

        return true;
    }



    /**************************************************************************
    *********************************** /FSO ***********************************
    ***************************************************************************/

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Expositor
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
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setLogoFileSize($data)
    {

        if ($this->_logoFileSize != $data) {
            $this->_logChange('logoFileSize');
        }

        if (!is_null($data)) {
            $this->_logoFileSize = (int) $data;
        } else {
            $this->_logoFileSize = $data;
        }
        return $this;
    }

    /**
     * Gets column logoFileSize
     *
     * @return int
     */
    public function getLogoFileSize()
    {
            return $this->_logoFileSize;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setLogoMimeType($data)
    {

        if ($this->_logoMimeType != $data) {
            $this->_logChange('logoMimeType');
        }

        if (!is_null($data)) {
            $this->_logoMimeType = (string) $data;
        } else {
            $this->_logoMimeType = $data;
        }
        return $this;
    }

    /**
     * Gets column logoMimeType
     *
     * @return string
     */
    public function getLogoMimeType()
    {
            return $this->_logoMimeType;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setLogoBaseName($data)
    {

        if ($this->_logoBaseName != $data) {
            $this->_logChange('logoBaseName');
        }

        if (!is_null($data)) {
            $this->_logoBaseName = (string) $data;
        } else {
            $this->_logoBaseName = $data;
        }
        return $this;
    }

    /**
     * Gets column logoBaseName
     *
     * @return string
     */
    public function getLogoBaseName()
    {
            return $this->_logoBaseName;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setCompanyName($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_companyName != $data) {
            $this->_logChange('companyName');
        }

        if (!is_null($data)) {
            $this->_companyName = (string) $data;
        } else {
            $this->_companyName = $data;
        }
        return $this;
    }

    /**
     * Gets column companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
            return $this->_companyName;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setOrderField($data)
    {

        if ($this->_orderField != $data) {
            $this->_logChange('orderField');
        }

        if (!is_null($data)) {
            $this->_orderField = (int) $data;
        } else {
            $this->_orderField = $data;
        }
        return $this;
    }

    /**
     * Gets column orderField
     *
     * @return int
     */
    public function getOrderField()
    {
            return $this->_orderField;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setDescription($data, $language = '')
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }

        $language = $this->_getCurrentLanguage($language);

        $methodName = "setDescription". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            $this->_description = $data;
            return $this;
        }
        $this->$methodName($data);
        return $this;
    }

    /**
     * Gets column description
     *
     * @return text
     */
    public function getDescription($language = '')
    {
    
        $language = $this->_getCurrentLanguage($language);

        $methodName = "getDescription". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            return $this->_description;
        }

        return $this->$methodName();

    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setDescriptionEu($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_descriptionEu != $data) {
            $this->_logChange('descriptionEu');
        }

        if (!is_null($data)) {
            $this->_descriptionEu = (string) $data;
        } else {
            $this->_descriptionEu = $data;
        }
        return $this;
    }

    /**
     * Gets column description_eu
     *
     * @return text
     */
    public function getDescriptionEu()
    {
            return $this->_descriptionEu;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setDescriptionEn($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_descriptionEn != $data) {
            $this->_logChange('descriptionEn');
        }

        if (!is_null($data)) {
            $this->_descriptionEn = (string) $data;
        } else {
            $this->_descriptionEn = $data;
        }
        return $this;
    }

    /**
     * Gets column description_en
     *
     * @return text
     */
    public function getDescriptionEn()
    {
            return $this->_descriptionEn;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Expositor
     */
    public function setDescriptionEs($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_descriptionEs != $data) {
            $this->_logChange('descriptionEs');
        }

        if (!is_null($data)) {
            $this->_descriptionEs = (string) $data;
        } else {
            $this->_descriptionEs = $data;
        }
        return $this;
    }

    /**
     * Gets column description_es
     *
     * @return text
     */
    public function getDescriptionEs()
    {
            return $this->_descriptionEs;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Expositor
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
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\Expositor
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Expositor')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Expositor);

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
     * @return null | \Librecon\Model\Validator\Expositor
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Expositor')) {

                $this->setValidator(new \Librecon\Validator\Expositor);
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
     * @see \Mapper\Sql\Expositor::delete
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
