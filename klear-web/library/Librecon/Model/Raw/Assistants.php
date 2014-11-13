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
class Assistants extends ModelAbstract
{
    /*
     * @var \Iron_Model_Fso
     */
    protected $_pictureFso;

    protected $_deviceAcceptedValues = array(
        'android',
        'ios',
    );
    protected $_langAcceptedValues = array(
        'es',
        'eu',
        'en',
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
    protected $_externalId;

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
    protected $_lastName;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_email;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_company;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_cellPhone;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_position;

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
     * Database var type varchar
     *
     * @var string
     */
    protected $_address;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_location;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_country;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_codePostal;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_uuid;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_code;

    /**
     * Database var type varchar
     *
     * @var string
     */
    protected $_secretHash;

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
    protected $_interests;

    /**
     * [enum:android|ios]
     * Database var type varchar
     *
     * @var string
     */
    protected $_device;

    /**
     * [enum:es|eu|en]
     * Database var type varchar
     *
     * @var string
     */
    protected $_lang;

    /**
     * Database var type tinyint
     *
     * @var int
     */
    protected $_mailOne;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_mailTwo;

    /**
     * Database var type mediumint
     *
     * @var int
     */
    protected $_mailRemember;

    /**
     * Database var type tinyint
     *
     * @var int
     */
    protected $_hidden;



    /**
     * Dependent relation Meeting_ibfk_1
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Meeting[]
     */
    protected $_MeetingBySender;

    /**
     * Dependent relation Meeting_ibfk_2
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\Meeting[]
     */
    protected $_MeetingByReceiver;


    protected $_columnsList = array(
        'id'=>'id',
        'externalId'=>'externalId',
        'name'=>'name',
        'lastName'=>'lastName',
        'email'=>'email',
        'company'=>'company',
        'cellPhone'=>'cellPhone',
        'position'=>'position',
        'pictureFileSize'=>'pictureFileSize',
        'pictureMimeType'=>'pictureMimeType',
        'pictureBaseName'=>'pictureBaseName',
        'address'=>'address',
        'location'=>'location',
        'country'=>'country',
        'codePostal'=>'codePostal',
        'uuid'=>'uuid',
        'code'=>'code',
        'secretHash'=>'secretHash',
        'lastModified'=>'lastModified',
        'interests'=>'interests',
        'device'=>'device',
        'lang'=>'lang',
        'mailOne'=>'mailOne',
        'mailTwo'=>'mailTwo',
        'mailRemember'=>'mailRemember',
        'hidden'=>'hidden',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'pictureFileSize'=> array('FSO'),
            'device'=> array('enum:android|ios'),
            'lang'=> array('enum:es|eu|en'),
        ));

        $this->setMultiLangColumnsList(array(
        ));

        $this->setAvailableLangs(array('es', 'en', 'eu'));

        $this->setParentList(array(
        ));

        $this->setDependentList(array(
            'MeetingIbfk1' => array(
                    'property' => 'MeetingBySender',
                    'table_name' => 'Meeting',
                ),
            'MeetingIbfk2' => array(
                    'property' => 'MeetingByReceiver',
                    'table_name' => 'Meeting',
                ),
        ));




        $this->_defaultValues = array(
            'externalId' => '0',
            'lastModified' => 'CURRENT_TIMESTAMP',
            'device' => 'android',
            'lang' => 'es',
            'mailOne' => '0',
            'mailTwo' => '0',
            'mailRemember' => '0',
            'hidden' => '0',
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
     * @return \Librecon\Model\Raw\Assistants
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
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setExternalId($data)
    {

        if ($this->_externalId != $data) {
            $this->_logChange('externalId');
        }

        if (!is_null($data)) {
            $this->_externalId = (int) $data;
        } else {
            $this->_externalId = $data;
        }
        return $this;
    }

    /**
     * Gets column externalId
     *
     * @return int
     */
    public function getExternalId()
    {
            return $this->_externalId;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
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
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setLastName($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_lastName != $data) {
            $this->_logChange('lastName');
        }

        if (!is_null($data)) {
            $this->_lastName = (string) $data;
        } else {
            $this->_lastName = $data;
        }
        return $this;
    }

    /**
     * Gets column lastName
     *
     * @return string
     */
    public function getLastName()
    {
            return $this->_lastName;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setEmail($data)
    {


        if (is_null($data)) {
            throw new \InvalidArgumentException(_('Required values cannot be null'));
        }
        if ($this->_email != $data) {
            $this->_logChange('email');
        }

        if (!is_null($data)) {
            $this->_email = (string) $data;
        } else {
            $this->_email = $data;
        }
        return $this;
    }

    /**
     * Gets column email
     *
     * @return string
     */
    public function getEmail()
    {
            return $this->_email;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setCompany($data)
    {

        if ($this->_company != $data) {
            $this->_logChange('company');
        }

        if (!is_null($data)) {
            $this->_company = (string) $data;
        } else {
            $this->_company = $data;
        }
        return $this;
    }

    /**
     * Gets column company
     *
     * @return string
     */
    public function getCompany()
    {
            return $this->_company;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setCellPhone($data)
    {

        if ($this->_cellPhone != $data) {
            $this->_logChange('cellPhone');
        }

        if (!is_null($data)) {
            $this->_cellPhone = (string) $data;
        } else {
            $this->_cellPhone = $data;
        }
        return $this;
    }

    /**
     * Gets column cellPhone
     *
     * @return string
     */
    public function getCellPhone()
    {
            return $this->_cellPhone;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setPosition($data)
    {

        if ($this->_position != $data) {
            $this->_logChange('position');
        }

        if (!is_null($data)) {
            $this->_position = (string) $data;
        } else {
            $this->_position = $data;
        }
        return $this;
    }

    /**
     * Gets column position
     *
     * @return string
     */
    public function getPosition()
    {
            return $this->_position;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Assistants
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
     * @return \Librecon\Model\Raw\Assistants
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
     * @return \Librecon\Model\Raw\Assistants
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
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setAddress($data)
    {

        if ($this->_address != $data) {
            $this->_logChange('address');
        }

        if (!is_null($data)) {
            $this->_address = (string) $data;
        } else {
            $this->_address = $data;
        }
        return $this;
    }

    /**
     * Gets column address
     *
     * @return string
     */
    public function getAddress()
    {
            return $this->_address;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
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
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setCountry($data)
    {

        if ($this->_country != $data) {
            $this->_logChange('country');
        }

        if (!is_null($data)) {
            $this->_country = (string) $data;
        } else {
            $this->_country = $data;
        }
        return $this;
    }

    /**
     * Gets column country
     *
     * @return string
     */
    public function getCountry()
    {
            return $this->_country;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setCodePostal($data)
    {

        if ($this->_codePostal != $data) {
            $this->_logChange('codePostal');
        }

        if (!is_null($data)) {
            $this->_codePostal = (string) $data;
        } else {
            $this->_codePostal = $data;
        }
        return $this;
    }

    /**
     * Gets column codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
            return $this->_codePostal;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setUuid($data)
    {

        if ($this->_uuid != $data) {
            $this->_logChange('uuid');
        }

        if (!is_null($data)) {
            $this->_uuid = (string) $data;
        } else {
            $this->_uuid = $data;
        }
        return $this;
    }

    /**
     * Gets column uuid
     *
     * @return string
     */
    public function getUuid()
    {
            return $this->_uuid;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setCode($data)
    {

        if ($this->_code != $data) {
            $this->_logChange('code');
        }

        if (!is_null($data)) {
            $this->_code = (string) $data;
        } else {
            $this->_code = $data;
        }
        return $this;
    }

    /**
     * Gets column code
     *
     * @return string
     */
    public function getCode()
    {
            return $this->_code;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setSecretHash($data)
    {

        if ($this->_secretHash != $data) {
            $this->_logChange('secretHash');
        }

        if (!is_null($data)) {
            $this->_secretHash = (string) $data;
        } else {
            $this->_secretHash = $data;
        }
        return $this;
    }

    /**
     * Gets column secretHash
     *
     * @return string
     */
    public function getSecretHash()
    {
            return $this->_secretHash;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Assistants
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
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setInterests($data)
    {

        if ($this->_interests != $data) {
            $this->_logChange('interests');
        }

        if (!is_null($data)) {
            $this->_interests = (string) $data;
        } else {
            $this->_interests = $data;
        }
        return $this;
    }

    /**
     * Gets column interests
     *
     * @return string
     */
    public function getInterests()
    {
            return $this->_interests;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setDevice($data)
    {

        if ($this->_device != $data) {
            $this->_logChange('device');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_deviceAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for device'));
            }
            $this->_device = (string) $data;
        } else {
            $this->_device = $data;
        }
        return $this;
    }

    /**
     * Gets column device
     *
     * @return string
     */
    public function getDevice()
    {
            return $this->_device;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setLang($data)
    {

        if ($this->_lang != $data) {
            $this->_logChange('lang');
        }

        if (!is_null($data)) {
            if (!in_array($data, $this->_langAcceptedValues) && !empty($data)) {
                throw new \InvalidArgumentException(_('Invalid value for lang'));
            }
            $this->_lang = (string) $data;
        } else {
            $this->_lang = $data;
        }
        return $this;
    }

    /**
     * Gets column lang
     *
     * @return string
     */
    public function getLang()
    {
            return $this->_lang;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setMailOne($data)
    {

        if ($this->_mailOne != $data) {
            $this->_logChange('mailOne');
        }

        if (!is_null($data)) {
            $this->_mailOne = (int) $data;
        } else {
            $this->_mailOne = $data;
        }
        return $this;
    }

    /**
     * Gets column mailOne
     *
     * @return int
     */
    public function getMailOne()
    {
            return $this->_mailOne;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setMailTwo($data)
    {

        if ($this->_mailTwo != $data) {
            $this->_logChange('mailTwo');
        }

        if (!is_null($data)) {
            $this->_mailTwo = (int) $data;
        } else {
            $this->_mailTwo = $data;
        }
        return $this;
    }

    /**
     * Gets column mailTwo
     *
     * @return int
     */
    public function getMailTwo()
    {
            return $this->_mailTwo;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setMailRemember($data)
    {

        if ($this->_mailRemember != $data) {
            $this->_logChange('mailRemember');
        }

        if (!is_null($data)) {
            $this->_mailRemember = (int) $data;
        } else {
            $this->_mailRemember = $data;
        }
        return $this;
    }

    /**
     * Gets column mailRemember
     *
     * @return int
     */
    public function getMailRemember()
    {
            return $this->_mailRemember;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param int $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setHidden($data)
    {

        if ($this->_hidden != $data) {
            $this->_logChange('hidden');
        }

        if (!is_null($data)) {
            $this->_hidden = (int) $data;
        } else {
            $this->_hidden = $data;
        }
        return $this;
    }

    /**
     * Gets column hidden
     *
     * @return int
     */
    public function getHidden()
    {
            return $this->_hidden;
    }


    /**
     * Sets dependent relations Meeting_ibfk_1
     *
     * @param array $data An array of \Librecon\Model\Raw\Meeting
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setMeetingBySender(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_MeetingBySender === null) {

                $this->getMeetingBySender();
            }

            $oldRelations = $this->_MeetingBySender;

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

        $this->_MeetingBySender = array();

        foreach ($data as $object) {
            $this->addMeetingBySender($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Meeting_ibfk_1
     *
     * @param \Librecon\Model\Raw\Meeting $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function addMeetingBySender(\Librecon\Model\Raw\Meeting $data)
    {
        $this->_MeetingBySender[] = $data;
        $this->_setLoaded('MeetingIbfk1');
        return $this;
    }

    /**
     * Gets dependent Meeting_ibfk_1
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Meeting
     */
    public function getMeetingBySender($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'MeetingIbfk1';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_MeetingBySender = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_MeetingBySender;
    }

    /**
     * Sets dependent relations Meeting_ibfk_2
     *
     * @param array $data An array of \Librecon\Model\Raw\Meeting
     * @return \Librecon\Model\Raw\Assistants
     */
    public function setMeetingByReceiver(array $data, $deleteOrphans = false)
    {
        if ($deleteOrphans === true) {

            if ($this->_MeetingByReceiver === null) {

                $this->getMeetingByReceiver();
            }

            $oldRelations = $this->_MeetingByReceiver;

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

        $this->_MeetingByReceiver = array();

        foreach ($data as $object) {
            $this->addMeetingByReceiver($object);
        }

        return $this;
    }

    /**
     * Sets dependent relations Meeting_ibfk_2
     *
     * @param \Librecon\Model\Raw\Meeting $data
     * @return \Librecon\Model\Raw\Assistants
     */
    public function addMeetingByReceiver(\Librecon\Model\Raw\Meeting $data)
    {
        $this->_MeetingByReceiver[] = $data;
        $this->_setLoaded('MeetingIbfk2');
        return $this;
    }

    /**
     * Gets dependent Meeting_ibfk_2
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\Meeting
     */
    public function getMeetingByReceiver($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'MeetingIbfk2';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_MeetingByReceiver = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_MeetingByReceiver;
    }

    /**
     * Returns the mapper class for this model
     *
     * @return Librecon\Mapper\Sql\Assistants
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Assistants')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Assistants);

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
     * @return null | \Librecon\Model\Validator\Assistants
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Assistants')) {

                $this->setValidator(new \Librecon\Validator\Assistants);
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
     * @see \Mapper\Sql\Assistants::delete
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
