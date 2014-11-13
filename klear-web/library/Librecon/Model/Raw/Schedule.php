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
class Schedule extends ModelAbstract
{
    /*
     * @var \Iron_Model_Fso
     */
    protected $_pictureFso;
    /*
     * @var \Iron_Model_Fso
     */
    protected $_iconFso;

    protected $_targetDateAcceptedValues = array(
        '1',
        '2',
    );

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_id;

    /**
     * [enum:1|2]
     * Database var type tinyint
     *
     * @var int
     */
    protected $_targetDate;

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
     * [ml][html]
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_description;

    /**
     * [html]
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_descriptionEu;

    /**
     * [html]
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_descriptionEn;

    /**
     * [html]
     * Database var type mediumtext
     *
     * @var text
     */
    protected $_descriptionEs;

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
     * Database var type datetime
     *
     * @var string
     */
    protected $_startDateTime;

    /**
     * Database var type datetime
     *
     * @var string
     */
    protected $_finishDateTime;

    /**
     * Database var type timestamp
     *
     * @var string
     */
    protected $_lastModified;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_link1;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_link1Type;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_link2;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_link2Type;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_link3;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_link3Type;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_link4;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_link4Type;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_location;

    /**
     * [FSO]
     * Database var type int
     *
     * @var int
     */
    protected $_iconFileSize;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_iconMimeType;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_iconBaseName;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_color;


    /**
     * Parent relation Schedule_ibfk_1
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink1Type;

    /**
     * Parent relation Schedule_ibfk_2
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink2Type;

    /**
     * Parent relation Schedule_ibfk_3
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink3Type;

    /**
     * Parent relation Schedule_ibfk_4
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink4Type;


    /**
     * Dependent relation RelScheduleSpeaker_ibfk_3
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\RelScheduleSpeaker[]
     */
    protected $_RelScheduleSpeaker;

    /**
     * Dependent relation RelTagScheduele_ibfk_2
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\RelTagScheduele[]
     */
    protected $_RelTagScheduele;


    protected $_columnsList = array(
        'id'=>'id',
        'targetDate'=>'targetDate',
        'name'=>'name',
        'name_eu'=>'nameEu',
        'name_en'=>'nameEn',
        'name_es'=>'nameEs',
        'description'=>'description',
        'description_eu'=>'descriptionEu',
        'description_en'=>'descriptionEn',
        'description_es'=>'descriptionEs',
        'pictureFileSize'=>'pictureFileSize',
        'pictureMimeType'=>'pictureMimeType',
        'pictureBaseName'=>'pictureBaseName',
        'startDateTime'=>'startDateTime',
        'finishDateTime'=>'finishDateTime',
        'lastModified'=>'lastModified',
        'link1'=>'link1',
        'link1Type'=>'link1Type',
        'link2'=>'link2',
        'link2Type'=>'link2Type',
        'link3'=>'link3',
        'link3Type'=>'link3Type',
        'link4'=>'link4',
        'link4Type'=>'link4Type',
        'location'=>'location',
        'iconFileSize'=>'iconFileSize',
        'iconMimeType'=>'iconMimeType',
        'iconBaseName'=>'iconBaseName',
        'color'=>'color',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'targetDate'=> array('enum:1|2'),
            'name'=> array('ml'),
            'description'=> array('ml','html'),
            'description_eu'=> array('html'),
            'description_en'=> array('html'),
            'description_es'=> array('html'),
            'pictureFileSize'=> array('FSO'),
            'iconFileSize'=> array('FSO'),
        ));

        $this->setMultiLangColumnsList(array(
            'name'=>'Name',
            'description'=>'Description',
        ));

        $this->setAvailableLangs(array('es', 'en', 'eu'));

        $this->setParentList(array(
            'ScheduleIbfk1'=> array(
                    'property' => 'LinksByLink1Type',
                    'table_name' => 'Links',
                ),
            'ScheduleIbfk2'=> array(
                    'property' => 'LinksByLink2Type',
                    'table_name' => 'Links',
                ),
            'ScheduleIbfk3'=> array(
                    'property' => 'LinksByLink3Type',
                    'table_name' => 'Links',
                ),
            'ScheduleIbfk4'=> array(
                    'property' => 'LinksByLink4Type',
                    'table_name' => 'Links',
                ),
        ));

        $this->setDependentList(array(
            'RelScheduleSpeakerIbfk3' => array(
                    'property' => 'RelScheduleSpeaker',
                    'table_name' => 'RelScheduleSpeaker',
                ),
            'RelTagSchedueleIbfk2' => array(
                    'property' => 'RelTagScheduele',
                    'table_name' => 'RelTagScheduele',
                ),
        ));


        $this->setOnDeleteSetNullRelationships(array(
            'RelScheduleSpeaker_ibfk_3',
            'RelTagScheduele_ibfk_2'
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
        $this->_pictureFso = new \Iron_Model_Fso($this, $this->getPictureSpecs());
        $this->_iconFso = new \Iron_Model_Fso($this, $this->getIconSpecs());

        return $this;
    }

    public function getFileObjects()
    {

        return array('picture','icon');
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

    public function getIconSpecs()
    {
        return array(
            'basePath' => 'icon',
            'sizeName' => 'iconFileSize',
            'mimeName' => 'iconMimeType',
            'baseNameName' => 'iconBaseName',
        );
    }

    public function putIcon($filePath = '',$baseName = '')
    {
        $this->_iconFso->put($filePath);

        if (!empty($baseName)) {

            $this->_iconFso->setBaseName($baseName);
        }
    }

    public function fetchIcon($autoload = true)
    {
        if ($autoload === true && $this->geticonFileSize() > 0) {

            $this->_iconFso->fetch();
        }

        return $this->_iconFso;
    }

    public function removeIcon()
    {
        $this->_iconFso->remove();

        $this->_iconFso = null;

        return true;
    }



    /**************************************************************************
    *********************************** /FSO ***********************************
    ***************************************************************************/

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Schedule
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
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setTargetDate($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_targetDate != $data) {
            $this->_logChange('targetDate');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_targetDateAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for targetDate'));
            }
            $this->_targetDate = (int) $data;
        } else {
            $this->_targetDate = $data;
        }
        return $this;
    }

    /**
     * Gets column targetDate
     *
     * @return int
     */
    public function getTargetDate()
    {
            return $this->_targetDate;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
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
     * @return \Librecon\Model\Raw\Schedule
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
     * @return \Librecon\Model\Raw\Schedule
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
     * @return \Librecon\Model\Raw\Schedule
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
     * @param text $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setDescription($data, $language = '')
    {


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
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setDescriptionEu($data)
    {

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
    
        $pathFixer = new \Iron_Filter_PathFixer;
        return $pathFixer->fix($this->_descriptionEu);
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setDescriptionEn($data)
    {

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
    
        $pathFixer = new \Iron_Filter_PathFixer;
        return $pathFixer->fix($this->_descriptionEn);
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setDescriptionEs($data)
    {

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
    
        $pathFixer = new \Iron_Filter_PathFixer;
        return $pathFixer->fix($this->_descriptionEs);
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Schedule
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
     * @return \Librecon\Model\Raw\Schedule
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
     * @return \Librecon\Model\Raw\Schedule
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
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setStartDateTime($data)
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



        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_startDateTime != $data) {
            $this->_logChange('startDateTime');
        }

        $this->_startDateTime = $data;
        return $this;
    }

    /**
     * Gets column startDateTime
     *
     * @param boolean $returnZendDate
     * @return Zend_Date|null|string Zend_Date representation of this datetime if enabled, or ISO 8601 string if not
     */
    public function getStartDateTime($returnZendDate = false)
    {
    
        if (is_null($this->_startDateTime)) {

            return null;
        }

        if ($returnZendDate) {
            $zendDate = new \Zend_Date($this->_startDateTime->getTimestamp(), \Zend_Date::TIMESTAMP);
            $zendDate->setTimezone('UTC');
            return $zendDate;
        }


        return $this->_startDateTime->format('Y-m-d H:i:s');

    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setFinishDateTime($data)
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



        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_finishDateTime != $data) {
            $this->_logChange('finishDateTime');
        }

        $this->_finishDateTime = $data;
        return $this;
    }

    /**
     * Gets column finishDateTime
     *
     * @param boolean $returnZendDate
     * @return Zend_Date|null|string Zend_Date representation of this datetime if enabled, or ISO 8601 string if not
     */
    public function getFinishDateTime($returnZendDate = false)
    {
    
        if (is_null($this->_finishDateTime)) {

            return null;
        }

        if ($returnZendDate) {
            $zendDate = new \Zend_Date($this->_finishDateTime->getTimestamp(), \Zend_Date::TIMESTAMP);
            $zendDate->setTimezone('UTC');
            return $zendDate;
        }


        return $this->_finishDateTime->format('Y-m-d H:i:s');

    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Schedule
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
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink1($data)
    {

        if ($this->_link1 != $data) {
            $this->_logChange('link1');
        }

        if (!is_null($data)) {
            $this->_link1 = (string) $data;
        } else {
            $this->_link1 = $data;
        }
        return $this;
    }

    /**
     * Gets column link1
     *
     * @return string
     */
    public function getLink1()
    {
            return $this->_link1;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink1Type($data)
    {

        if ($this->_link1Type != $data) {
            $this->_logChange('link1Type');
        }

        if (!is_null($data)) {
            $this->_link1Type = (int) $data;
        } else {
            $this->_link1Type = $data;
        }
        return $this;
    }

    /**
     * Gets column link1Type
     *
     * @return int
     */
    public function getLink1Type()
    {
            return $this->_link1Type;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink2($data)
    {

        if ($this->_link2 != $data) {
            $this->_logChange('link2');
        }

        if (!is_null($data)) {
            $this->_link2 = (string) $data;
        } else {
            $this->_link2 = $data;
        }
        return $this;
    }

    /**
     * Gets column link2
     *
     * @return string
     */
    public function getLink2()
    {
            return $this->_link2;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink2Type($data)
    {

        if ($this->_link2Type != $data) {
            $this->_logChange('link2Type');
        }

        if (!is_null($data)) {
            $this->_link2Type = (int) $data;
        } else {
            $this->_link2Type = $data;
        }
        return $this;
    }

    /**
     * Gets column link2Type
     *
     * @return int
     */
    public function getLink2Type()
    {
            return $this->_link2Type;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink3($data)
    {

        if ($this->_link3 != $data) {
            $this->_logChange('link3');
        }

        if (!is_null($data)) {
            $this->_link3 = (string) $data;
        } else {
            $this->_link3 = $data;
        }
        return $this;
    }

    /**
     * Gets column link3
     *
     * @return string
     */
    public function getLink3()
    {
            return $this->_link3;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink3Type($data)
    {

        if ($this->_link3Type != $data) {
            $this->_logChange('link3Type');
        }

        if (!is_null($data)) {
            $this->_link3Type = (int) $data;
        } else {
            $this->_link3Type = $data;
        }
        return $this;
    }

    /**
     * Gets column link3Type
     *
     * @return int
     */
    public function getLink3Type()
    {
            return $this->_link3Type;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink4($data)
    {

        if ($this->_link4 != $data) {
            $this->_logChange('link4');
        }

        if (!is_null($data)) {
            $this->_link4 = (string) $data;
        } else {
            $this->_link4 = $data;
        }
        return $this;
    }

    /**
     * Gets column link4
     *
     * @return string
     */
    public function getLink4()
    {
            return $this->_link4;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLink4Type($data)
    {

        if ($this->_link4Type != $data) {
            $this->_logChange('link4Type');
        }

        if (!is_null($data)) {
            $this->_link4Type = (int) $data;
        } else {
            $this->_link4Type = $data;
        }
        return $this;
    }

    /**
     * Gets column link4Type
     *
     * @return int
     */
    public function getLink4Type()
    {
            return $this->_link4Type;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLocation($data)
    {

        if ($this->_location != $data) {
            $this->_logChange('location');
        }

        if (!is_null($data)) {
            $this->_location = (string) $data;
        } else {
            $this->_location = $data;
        }
        return $this;
    }

    /**
     * Gets column location
     *
     * @return string
     */
    public function getLocation()
    {
            return $this->_location;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setIconFileSize($data)
    {

        if ($this->_iconFileSize != $data) {
            $this->_logChange('iconFileSize');
        }

        if (!is_null($data)) {
            $this->_iconFileSize = (int) $data;
        } else {
            $this->_iconFileSize = $data;
        }
        return $this;
    }

    /**
     * Gets column iconFileSize
     *
     * @return int
     */
    public function getIconFileSize()
    {
            return $this->_iconFileSize;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setIconMimeType($data)
    {

        if ($this->_iconMimeType != $data) {
            $this->_logChange('iconMimeType');
        }

        if (!is_null($data)) {
            $this->_iconMimeType = (string) $data;
        } else {
            $this->_iconMimeType = $data;
        }
        return $this;
    }

    /**
     * Gets column iconMimeType
     *
     * @return string
     */
    public function getIconMimeType()
    {
            return $this->_iconMimeType;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setIconBaseName($data)
    {

        if ($this->_iconBaseName != $data) {
            $this->_logChange('iconBaseName');
        }

        if (!is_null($data)) {
            $this->_iconBaseName = (string) $data;
        } else {
            $this->_iconBaseName = $data;
        }
        return $this;
    }

    /**
     * Gets column iconBaseName
     *
     * @return string
     */
    public function getIconBaseName()
    {
            return $this->_iconBaseName;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Schedule
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
     * Sets parent relation Link1Type
     *
     * @param \Librecon\Model\Raw\Links $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLinksByLink1Type(\Librecon\Model\Raw\Links $data)
    {
        $this->_LinksByLink1Type = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setLink1Type($primaryKey);
        }

        $this->_setLoaded('ScheduleIbfk1');
        return $this;
    }

    /**
     * Gets parent Link1Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink1Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_LinksByLink1Type = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_LinksByLink1Type;
    }

    /**
     * Sets parent relation Link2Type
     *
     * @param \Librecon\Model\Raw\Links $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLinksByLink2Type(\Librecon\Model\Raw\Links $data)
    {
        $this->_LinksByLink2Type = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setLink2Type($primaryKey);
        }

        $this->_setLoaded('ScheduleIbfk2');
        return $this;
    }

    /**
     * Gets parent Link2Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink2Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_LinksByLink2Type = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_LinksByLink2Type;
    }

    /**
     * Sets parent relation Link3Type
     *
     * @param \Librecon\Model\Raw\Links $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLinksByLink3Type(\Librecon\Model\Raw\Links $data)
    {
        $this->_LinksByLink3Type = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setLink3Type($primaryKey);
        }

        $this->_setLoaded('ScheduleIbfk3');
        return $this;
    }

    /**
     * Gets parent Link3Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink3Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk3';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_LinksByLink3Type = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_LinksByLink3Type;
    }

    /**
     * Sets parent relation Link4Type
     *
     * @param \Librecon\Model\Raw\Links $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setLinksByLink4Type(\Librecon\Model\Raw\Links $data)
    {
        $this->_LinksByLink4Type = $data;

        $primaryKey = $data->getPrimaryKey();
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey['id'];
        }

        if (!is_null($primaryKey)) {
            $this->setLink4Type($primaryKey);
        }

        $this->_setLoaded('ScheduleIbfk4');
        return $this;
    }

    /**
     * Gets parent Link4Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink4Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'ScheduleIbfk4';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_LinksByLink4Type = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_LinksByLink4Type;
    }

    /**
     * Sets dependent relations RelScheduleSpeaker_ibfk_3
     *
     * @param array $data An array of \Librecon\Model\Raw\RelScheduleSpeaker
     * @return \Librecon\Model\Raw\Schedule
     */
    public function setRelScheduleSpeaker(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_RelScheduleSpeaker === null) {

                $this->getRelScheduleSpeaker();
            }

            $oldRelations = $this->_RelScheduleSpeaker;

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

        $this->_RelScheduleSpeaker = array();

        foreach ($data as $object) {
            $this->addRelScheduleSpeaker($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations RelScheduleSpeaker_ibfk_3
     *
     * @param \Librecon\Model\Raw\RelScheduleSpeaker $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function addRelScheduleSpeaker(\Librecon\Model\Raw\RelScheduleSpeaker $data)
    {
        $this->_RelScheduleSpeaker[] = $data;
        $this->_setLoaded('RelScheduleSpeakerIbfk3');
        return $this;
    }

    /**
     * Gets dependent RelScheduleSpeaker_ibfk_3
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\RelScheduleSpeaker
     */
    public function getRelScheduleSpeaker($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelScheduleSpeakerIbfk3';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_RelScheduleSpeaker = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_RelScheduleSpeaker;
    }

    /**
     * Sets dependent relations RelTagScheduele_ibfk_2
     *
     * @param array $data An array of \Librecon\Model\Raw\RelTagScheduele
     * @return \Librecon\Model\Raw\Schedule
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
     * Sets dependent relations RelTagScheduele_ibfk_2
     *
     * @param \Librecon\Model\Raw\RelTagScheduele $data
     * @return \Librecon\Model\Raw\Schedule
     */
    public function addRelTagScheduele(\Librecon\Model\Raw\RelTagScheduele $data)
    {
        $this->_RelTagScheduele[] = $data;
        $this->_setLoaded('RelTagSchedueleIbfk2');
        return $this;
    }

    /**
     * Gets dependent RelTagScheduele_ibfk_2
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\RelTagScheduele
     */
    public function getRelTagScheduele($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSchedueleIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_RelTagScheduele = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_RelTagScheduele;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\Schedule
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Schedule')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Schedule);

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
     * @return null | \Librecon\Model\Validator\Schedule
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Schedule')) {

                $this->setValidator(new \Librecon\Validator\Schedule);
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
     * @see \Mapper\Sql\Schedule::delete
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
