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
 * Table definition for KlearRolesSections
 *
 * @package Librecon\Mapper\Sql\DbTable
 * @subpackage DbTable
 * @author vvargas
 */

namespace Librecon\Mapper\Sql\DbTable;
class KlearRolesSections extends TableAbstract
{
    /**
     * $_name - name of database table
     *
     * @var string
     */
    protected $_name = 'KlearRolesSections';

    /**
     * $_id - this is the primary key name
     *
     * @var int
     */
    protected $_id = 'id';

    protected $_rowClass = 'Librecon\\Model\\KlearRolesSections';
    protected $_rowMapperClass = 'Librecon\\Mapper\\Sql\\KlearRolesSections';

    protected $_sequence = true; // int
    protected $_referenceMap = array(
        'KlearRolesSectionsIbfk1' => array(
            'columns' => 'klearRoleId',
            'refTableClass' => 'Librecon\\Mapper\\Sql\\DbTable\\KlearRoles',
            'refColumns' => 'id'
        ),
        'KlearRolesSectionsIbfk2' => array(
            'columns' => 'klearSectionId',
            'refTableClass' => 'Librecon\\Mapper\\Sql\\DbTable\\KlearSections',
            'refColumns' => 'id'
        )
    );
    
    protected $_metadata = array (
	  'id' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'KlearRolesSections',
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
	  'klearRoleId' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'KlearRolesSections',
	    'COLUMN_NAME' => 'klearRoleId',
	    'COLUMN_POSITION' => 2,
	    'DATA_TYPE' => 'mediumint',
	    'DEFAULT' => NULL,
	    'NULLABLE' => false,
	    'LENGTH' => NULL,
	    'SCALE' => NULL,
	    'PRECISION' => NULL,
	    'UNSIGNED' => true,
	    'PRIMARY' => false,
	    'PRIMARY_POSITION' => NULL,
	    'IDENTITY' => false,
	  ),
	  'klearSectionId' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'KlearRolesSections',
	    'COLUMN_NAME' => 'klearSectionId',
	    'COLUMN_POSITION' => 3,
	    'DATA_TYPE' => 'mediumint',
	    'DEFAULT' => NULL,
	    'NULLABLE' => false,
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
