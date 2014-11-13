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
class Txoko extends ModelAbstract
{
    /*
     * @var \Iron_Model_Fso
     */
    protected $_pictureFso;


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
    protected $_pictureFileSize;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_pictureMimeType;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_pictureBaseName;

    /**
     * [ml]
     * Database var type varchar
     *
     * @var string
     */
    protected $_title;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_titleEu;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_titleEn;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_titleEs;

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
    protected $_text;

    /**
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_textEu;

    /**
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_textEn;

    /**
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_textEs;

    /**
     * Database var type timestamp
     *
     * @var string
     */
    protected $_lastModified;




    protected $_columnsList = array(
        'id'=>'id',
        'pictureFileSize'=>'pictureFileSize',
        'pictureMimeType'=>'pictureMimeType',
        'pictureBaseName'=>'pictureBaseName',
        'title'=>'title',
        'title_eu'=>'titleEu',
        'title_en'=>'titleEn',
        'title_es'=>'titleEs',
        'orderField'=>'orderField',
        'text'=>'text',
        'text_eu'=>'textEu',
        'text_en'=>'textEn',
        'text_es'=>'textEs',
        'lastModified'=>'lastModified',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'pictureFileSize'=> array('FSO'),
            'title'=> array('ml'),
            'text'=> array('ml'),
        ));

        $this->setMultiLangColumnsList(array(
            'title'=>'Title',
            'text'=>'Text',
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
        $this->_pictureFso = new \Iron_Model_Fso($this, $this->getPictureSpecs());

        return $this;
    }

    public function getFileObjects()
    {

        return array('picture');
    }

    public function getPictureSpecs()
    {
        return array(
            'basePath' => 'picture',
            'sizeName' => 'pictureFileSize',
            'mimeName' => 'pictureMimeType',
            'baseNameName' => 'pictureBaseName',
        );
    }

    public function putPicture($filePath = '',$baseName = '')
    {
        $this->_pictureFso->put($filePath);

        if (!empty($baseName)) {

            $this->_pictureFso->setBaseName($baseName);
        }
    }

    public function fetchPicture($autoload = true)
    {
        if ($autoload === true && $this->getpictureFileSize() > 0) {

            $this->_pictureFso->fetch();
        }

        return $this->_pictureFso;
    }

    public function removePicture()
    {
        $this->_pictureFso->remove();

        $this->_pictureFso = null;

        return true;
    }



    /**************************************************************************
    *********************************** /FSO ***********************************
    ***************************************************************************/

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Txoko
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
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setPictureFileSize($data)
    {

        if ($this->_pictureFileSize != $data) {
            $this->_logChange('pictureFileSize');
        }

        if (!is_null($data)) {
            $this->_pictureFileSize = (int) $data;
        } else {
            $this->_pictureFileSize = $data;
        }
        return $this;
    }

    /**
     * Gets column pictureFileSize
     *
     * @return int
     */
    public function getPictureFileSize()
    {
            return $this->_pictureFileSize;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setPictureMimeType($data)
    {

        if ($this->_pictureMimeType != $data) {
            $this->_logChange('pictureMimeType');
        }

        if (!is_null($data)) {
            $this->_pictureMimeType = (string) $data;
        } else {
            $this->_pictureMimeType = $data;
        }
        return $this;
    }

    /**
     * Gets column pictureMimeType
     *
     * @return string
     */
    public function getPictureMimeType()
    {
            return $this->_pictureMimeType;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setPictureBaseName($data)
    {

        if ($this->_pictureBaseName != $data) {
            $this->_logChange('pictureBaseName');
        }

        if (!is_null($data)) {
            $this->_pictureBaseName = (string) $data;
        } else {
            $this->_pictureBaseName = $data;
        }
        return $this;
    }

    /**
     * Gets column pictureBaseName
     *
     * @return string
     */
    public function getPictureBaseName()
    {
            return $this->_pictureBaseName;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setTitle($data, $language = '')
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }

        $language = $this->_getCurrentLanguage($language);

        $methodName = "setTitle". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            $this->_title = $data;
            return $this;
        }
        $this->$methodName($data);
        return $this;
    }

    /**
     * Gets column title
     *
     * @return string
     */
    public function getTitle($language = '')
    {
    
        $language = $this->_getCurrentLanguage($language);

        $methodName = "getTitle". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            return $this->_title;
        }

        return $this->$methodName();

    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setTitleEu($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_titleEu != $data) {
            $this->_logChange('titleEu');
        }

        if (!is_null($data)) {
            $this->_titleEu = (string) $data;
        } else {
            $this->_titleEu = $data;
        }
        return $this;
    }

    /**
     * Gets column title_eu
     *
     * @return string
     */
    public function getTitleEu()
    {
            return $this->_titleEu;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setTitleEn($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_titleEn != $data) {
            $this->_logChange('titleEn');
        }

        if (!is_null($data)) {
            $this->_titleEn = (string) $data;
        } else {
            $this->_titleEn = $data;
        }
        return $this;
    }

    /**
     * Gets column title_en
     *
     * @return string
     */
    public function getTitleEn()
    {
            return $this->_titleEn;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setTitleEs($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_titleEs != $data) {
            $this->_logChange('titleEs');
        }

        if (!is_null($data)) {
            $this->_titleEs = (string) $data;
        } else {
            $this->_titleEs = $data;
        }
        return $this;
    }

    /**
     * Gets column title_es
     *
     * @return string
     */
    public function getTitleEs()
    {
            return $this->_titleEs;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Txoko
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
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setText($data, $language = '')
    {


        $language = $this->_getCurrentLanguage($language);

        $methodName = "setText". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            $this->_text = $data;
            return $this;
        }
        $this->$methodName($data);
        return $this;
    }

    /**
     * Gets column text
     *
     * @return text
     */
    public function getText($language = '')
    {
    
        $language = $this->_getCurrentLanguage($language);

        $methodName = "getText". ucfirst(str_replace('_', '', $language));
        if (!method_exists($this, $methodName)) {

            // new \Exception('Unavailable language');
            return $this->_text;
        }

        return $this->$methodName();

    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setTextEu($data)
    {

        if ($this->_textEu != $data) {
            $this->_logChange('textEu');
        }

        if (!is_null($data)) {
            $this->_textEu = (string) $data;
        } else {
            $this->_textEu = $data;
        }
        return $this;
    }

    /**
     * Gets column text_eu
     *
     * @return text
     */
    public function getTextEu()
    {
            return $this->_textEu;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setTextEn($data)
    {

        if ($this->_textEn != $data) {
            $this->_logChange('textEn');
        }

        if (!is_null($data)) {
            $this->_textEn = (string) $data;
        } else {
            $this->_textEn = $data;
        }
        return $this;
    }

    /**
     * Gets column text_en
     *
     * @return text
     */
    public function getTextEn()
    {
            return $this->_textEn;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Txoko
     */
    public function setTextEs($data)
    {

        if ($this->_textEs != $data) {
            $this->_logChange('textEs');
        }

        if (!is_null($data)) {
            $this->_textEs = (string) $data;
        } else {
            $this->_textEs = $data;
        }
        return $this;
    }

    /**
     * Gets column text_es
     *
     * @return text
     */
    public function getTextEs()
    {
            return $this->_textEs;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Txoko
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
     * @return Librecon\Mapper\Sql\Txoko
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Txoko')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Txoko);

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
     * @return null | \Librecon\Model\Validator\Txoko
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Txoko')) {

                $this->setValidator(new \Librecon\Validator\Txoko);
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
     * @see \Mapper\Sql\Txoko::delete
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
