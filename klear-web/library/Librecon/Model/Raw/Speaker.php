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
class Speaker extends ModelAbstract
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
     * Database var type varchar
     *
     * @var string
     */
    protected $_name;

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
     * Database var type varchar
     *
     * @var string
     */
    protected $_company;

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
     * Parent relation Speaker_ibfk_1
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink1Type;

    /**
     * Parent relation Speaker_ibfk_2
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink2Type;

    /**
     * Parent relation Speaker_ibfk_3
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink3Type;

    /**
     * Parent relation Speaker_ibfk_4
     *
     * @var \Librecon\Model\Raw\Links
     */
    protected $_LinksByLink4Type;


    /**
     * Dependent relation RelScheduleSpeaker_ibfk_4
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\RelScheduleSpeaker[]
     */
    protected $_RelScheduleSpeaker;

    /**
     * Dependent relation RelTagSpeaker_ibfk_2
     * Type: One-to-Many relationship
     *
     * @var \Librecon\Model\Raw\RelTagSpeaker[]
     */
    protected $_RelTagSpeaker;


    protected $_columnsList = array(
        'id'=>'id',
        'name'=>'name',
        'pictureFileSize'=>'pictureFileSize',
        'pictureMimeType'=>'pictureMimeType',
        'pictureBaseName'=>'pictureBaseName',
        'description'=>'description',
        'description_eu'=>'descriptionEu',
        'description_en'=>'descriptionEn',
        'description_es'=>'descriptionEs',
        'company'=>'company',
        'lastModified'=>'lastModified',
        'link1'=>'link1',
        'link1Type'=>'link1Type',
        'link2'=>'link2',
        'link2Type'=>'link2Type',
        'link3'=>'link3',
        'link3Type'=>'link3Type',
        'link4'=>'link4',
        'link4Type'=>'link4Type',
    );

    /**
     * Sets up column and relationship lists
     */
    public function __construct()
    {
        $this->setColumnsMeta(array(
            'pictureFileSize'=> array('FSO'),
            'description'=> array('ml'),
        ));

        $this->setMultiLangColumnsList(array(
            'description'=>'Description',
        ));

        $this->setAvailableLangs(array('es', 'en', 'eu'));

        $this->setParentList(array(
            'SpeakerIbfk1'=> array(
                    'property' => 'LinksByLink1Type',
                    'table_name' => 'Links',
                ),
            'SpeakerIbfk2'=> array(
                    'property' => 'LinksByLink2Type',
                    'table_name' => 'Links',
                ),
            'SpeakerIbfk3'=> array(
                    'property' => 'LinksByLink3Type',
                    'table_name' => 'Links',
                ),
            'SpeakerIbfk4'=> array(
                    'property' => 'LinksByLink4Type',
                    'table_name' => 'Links',
                ),
        ));

        $this->setDependentList(array(
            'RelScheduleSpeakerIbfk4' => array(
                    'property' => 'RelScheduleSpeaker',
                    'table_name' => 'RelScheduleSpeaker',
                ),
            'RelTagSpeakerIbfk2' => array(
                    'property' => 'RelTagSpeaker',
                    'table_name' => 'RelTagSpeaker',
                ),
        ));


        $this->setOnDeleteSetNullRelationships(array(
            'RelScheduleSpeaker_ibfk_4',
            'RelTagSpeaker_ibfk_2'
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @param int $data
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @param text $data
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
            return $this->_descriptionEu;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Speaker
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
            return $this->_descriptionEn;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param text $data
     * @return \Librecon\Model\Raw\Speaker
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
            return $this->_descriptionEs;
    }

    /**
     * Sets column Stored in ISO 8601 format.     *
     * @param string $data
     * @return \Librecon\Model\Raw\Speaker
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
     * @param string|Zend_Date $date
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * @return \Librecon\Model\Raw\Speaker
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
     * Sets parent relation Link1Type
     *
     * @param \Librecon\Model\Raw\Links $data
     * @return \Librecon\Model\Raw\Speaker
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

        $this->_setLoaded('SpeakerIbfk1');
        return $this;
    }

    /**
     * Gets parent Link1Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink1Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk1';

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
     * @return \Librecon\Model\Raw\Speaker
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

        $this->_setLoaded('SpeakerIbfk2');
        return $this;
    }

    /**
     * Gets parent Link2Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink2Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk2';

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
     * @return \Librecon\Model\Raw\Speaker
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

        $this->_setLoaded('SpeakerIbfk3');
        return $this;
    }

    /**
     * Gets parent Link3Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink3Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk3';

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
     * @return \Librecon\Model\Raw\Speaker
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

        $this->_setLoaded('SpeakerIbfk4');
        return $this;
    }

    /**
     * Gets parent Link4Type
     * TODO: Mejorar esto para los casos en que la relación no exista. Ahora mismo siempre se pediría el padre
     * @return \Librecon\Model\Raw\Links
     */
    public function getLinksByLink4Type($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'SpeakerIbfk4';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('parent', $fkName, $this, $where, $orderBy);
            $this->_LinksByLink4Type = array_shift($related);
            $this->_setLoaded($fkName);
        }

        return $this->_LinksByLink4Type;
    }

    /**
     * Sets dependent relations RelScheduleSpeaker_ibfk_4
     *
     * @param array $data An array of \Librecon\Model\Raw\RelScheduleSpeaker
     * @return \Librecon\Model\Raw\Speaker
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
     * Sets dependent relations RelScheduleSpeaker_ibfk_4
     *
     * @param \Librecon\Model\Raw\RelScheduleSpeaker $data
     * @return \Librecon\Model\Raw\Speaker
     */
    public function addRelScheduleSpeaker(\Librecon\Model\Raw\RelScheduleSpeaker $data)
    {
        $this->_RelScheduleSpeaker[] = $data;
        $this->_setLoaded('RelScheduleSpeakerIbfk4');
        return $this;
    }

    /**
     * Gets dependent RelScheduleSpeaker_ibfk_4
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\RelScheduleSpeaker
     */
    public function getRelScheduleSpeaker($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelScheduleSpeakerIbfk4';

        if (!$avoidLoading && !$this->_isLoaded($fkName)) {
            $related = $this->getMapper()->loadRelated('dependent', $fkName, $this, $where, $orderBy);
            $this->_RelScheduleSpeaker = $related;
            $this->_setLoaded($fkName);
        }

        return $this->_RelScheduleSpeaker;
    }

    /**
     * Sets dependent relations RelTagSpeaker_ibfk_2
     *
     * @param array $data An array of \Librecon\Model\Raw\RelTagSpeaker
     * @return \Librecon\Model\Raw\Speaker
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
     * Sets dependent relations RelTagSpeaker_ibfk_2
     *
     * @param \Librecon\Model\Raw\RelTagSpeaker $data
     * @return \Librecon\Model\Raw\Speaker
     */
    public function addRelTagSpeaker(\Librecon\Model\Raw\RelTagSpeaker $data)
    {
        $this->_RelTagSpeaker[] = $data;
        $this->_setLoaded('RelTagSpeakerIbfk2');
        return $this;
    }

    /**
     * Gets dependent RelTagSpeaker_ibfk_2
     *
     * @param string or array $where
     * @param string or array $orderBy
     * @param boolean $avoidLoading skip data loading if it is not already
     * @return array The array of \Librecon\Model\Raw\RelTagSpeaker
     */
    public function getRelTagSpeaker($where = null, $orderBy = null, $avoidLoading = false)
    {
        $fkName = 'RelTagSpeakerIbfk2';

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
     * @return Librecon\Mapper\Sql\Speaker
     */
    public function getMapper()
    {
        if ($this->_mapper === null) {

            \Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

            if (class_exists('\Librecon\Mapper\Sql\Speaker')) {

                $this->setMapper(new \Librecon\Mapper\Sql\Speaker);

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
     * @return null | \Librecon\Model\Validator\Speaker
     */
    public function getValidator()
    {
        if ($this->_validator === null) {

            if (class_exists('\Librecon\\Validator\Speaker')) {

                $this->setValidator(new \Librecon\Validator\Speaker);
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
     * @see \Mapper\Sql\Speaker::delete
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
