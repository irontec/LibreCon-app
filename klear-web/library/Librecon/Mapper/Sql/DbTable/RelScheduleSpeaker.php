<?php

/**
 * Application Model DbTables
 *
 * @package Librecon\Mapper\Sql\DbTable
 * @subpackage DbTable
 * @author vvargas
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Table definition for RelScheduleSpeaker
 *
 * @package Librecon\Mapper\Sql\DbTable
 * @subpackage DbTable
 * @author vvargas
 */

namespace Librecon\Mapper\Sql\DbTable;
class RelScheduleSpeaker extends TableAbstract
{
    /**
     * $_name - name of database table
     *
     * @var string
     */
    protected $_name = 'RelScheduleSpeaker';

    /**
     * $_id - this is the primary key name
     *
     * @var int
     */
    protected $_id = 'id';

    protected $_rowClass = 'Librecon\\Model\\RelScheduleSpeaker';
    protected $_rowMapperClass = 'Librecon\\Mapper\\Sql\\RelScheduleSpeaker';

    protected $_sequence = true; // int
    protected $_referenceMap = array(
        'RelScheduleSpeakerIbfk3' => array(
            'columns' => 'idSchedule',
            'refTableClass' => 'Librecon\\Mapper\\Sql\\DbTable\\Schedule',
            'refColumns' => 'id'
        ),
        'RelScheduleSpeakerIbfk4' => array(
            'columns' => 'idSpeaker',
            'refTableClass' => 'Librecon\\Mapper\\Sql\\DbTable\\Speaker',
            'refColumns' => 'id'
        )
    );
    
    protected $_metadata = array (
	  'id' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'RelScheduleSpeaker',
	    'COLUMN_NAME' => 'id',
	    'COLUMN_POSITION' => 1,
	    'DATA_TYPE' => 'mediumint',
	    'DEFAULT' => NULL,
	    'NULLABLE' => false,
	    'LENGTH' => NULL,
	    'SCALE' => NULL,
	    'PRECISION' => NULL,
	    'UNSIGNED' => true,
	    'PRIMARY' => true,
	    'PRIMARY_POSITION' => 1,
	    'IDENTITY' => true,
	  ),
	  'idSchedule' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'RelScheduleSpeaker',
	    'COLUMN_NAME' => 'idSchedule',
	    'COLUMN_POSITION' => 2,
	    'DATA_TYPE' => 'mediumint',
	    'DEFAULT' => NULL,
	    'NULLABLE' => true,
	    'LENGTH' => NULL,
	    'SCALE' => NULL,
	    'PRECISION' => NULL,
	    'UNSIGNED' => true,
	    'PRIMARY' => false,
	    'PRIMARY_POSITION' => NULL,
	    'IDENTITY' => false,
	  ),
	  'idSpeaker' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'RelScheduleSpeaker',
	    'COLUMN_NAME' => 'idSpeaker',
	    'COLUMN_POSITION' => 3,
	    'DATA_TYPE' => 'mediumint',
	    'DEFAULT' => NULL,
	    'NULLABLE' => true,
	    'LENGTH' => NULL,
	    'SCALE' => NULL,
	    'PRECISION' => NULL,
	    'UNSIGNED' => true,
	    'PRIMARY' => false,
	    'PRIMARY_POSITION' => NULL,
	    'IDENTITY' => false,
	  ),
	);




}
