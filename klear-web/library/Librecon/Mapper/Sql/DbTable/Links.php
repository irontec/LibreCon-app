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
 * Table definition for Links
 *
 * @package Librecon\Mapper\Sql\DbTable
 * @subpackage DbTable
 * @author vvargas
 */

namespace Librecon\Mapper\Sql\DbTable;
class Links extends TableAbstract
{
    /**
     * $_name - name of database table
     *
     * @var string
     */
    protected $_name = 'Links';

    /**
     * $_id - this is the primary key name
     *
     * @var int
     */
    protected $_id = 'id';

    protected $_rowClass = 'Librecon\\Model\\Links';
    protected $_rowMapperClass = 'Librecon\\Mapper\\Sql\\Links';

    protected $_sequence = true; // int
    
    protected $_dependentTables = array(
        'Librecon\\Mapper\\Sql\\DbTable\\Schedule',
        'Librecon\\Mapper\\Sql\\DbTable\\Schedule',
        'Librecon\\Mapper\\Sql\\DbTable\\Schedule',
        'Librecon\\Mapper\\Sql\\DbTable\\Schedule',
        'Librecon\\Mapper\\Sql\\DbTable\\Speaker',
        'Librecon\\Mapper\\Sql\\DbTable\\Speaker',
        'Librecon\\Mapper\\Sql\\DbTable\\Speaker',
        'Librecon\\Mapper\\Sql\\DbTable\\Speaker'
    );
    protected $_metadata = array (
	  'id' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'Links',
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
	  'name' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'Links',
	    'COLUMN_NAME' => 'name',
	    'COLUMN_POSITION' => 2,
	    'DATA_TYPE' => 'varchar',
	    'DEFAULT' => NULL,
	    'NULLABLE' => true,
	    'LENGTH' => '100',
	    'SCALE' => NULL,
	    'PRECISION' => NULL,
	    'UNSIGNED' => NULL,
	    'PRIMARY' => false,
	    'PRIMARY_POSITION' => NULL,
	    'IDENTITY' => false,
	  ),
	  'type' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'Links',
	    'COLUMN_NAME' => 'type',
	    'COLUMN_POSITION' => 3,
	    'DATA_TYPE' => 'varchar',
	    'DEFAULT' => NULL,
	    'NULLABLE' => true,
	    'LENGTH' => '100',
	    'SCALE' => NULL,
	    'PRECISION' => NULL,
	    'UNSIGNED' => NULL,
	    'PRIMARY' => false,
	    'PRIMARY_POSITION' => NULL,
	    'IDENTITY' => false,
	  ),
	);




}
