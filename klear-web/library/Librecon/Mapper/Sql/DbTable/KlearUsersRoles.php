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
 * Table definition for KlearUsersRoles
 *
 * @package Librecon\Mapper\Sql\DbTable
 * @subpackage DbTable
 * @author vvargas
 */

namespace Librecon\Mapper\Sql\DbTable;
class KlearUsersRoles extends TableAbstract
{
    /**
     * $_name - name of database table
     *
     * @var string
     */
    protected $_name = 'KlearUsersRoles';

    /**
     * $_id - this is the primary key name
     *
     * @var int
     */
    protected $_id = 'id';

    protected $_rowClass = 'Librecon\\Model\\KlearUsersRoles';
    protected $_rowMapperClass = 'Librecon\\Mapper\\Sql\\KlearUsersRoles';

    protected $_sequence = true; // int
    protected $_referenceMap = array(
        'KlearUsersRolesIbfk1' => array(
            'columns' => 'klearUserId',
            'refTableClass' => 'Librecon\\Mapper\\Sql\\DbTable\\KlearUsers',
            'refColumns' => 'userId'
        ),
        'KlearUsersRolesIbfk2' => array(
            'columns' => 'klearRoleId',
            'refTableClass' => 'Librecon\\Mapper\\Sql\\DbTable\\KlearRoles',
            'refColumns' => 'id'
        )
    );
    
    protected $_metadata = array (
	  'id' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'KlearUsersRoles',
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
	  'klearUserId' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'KlearUsersRoles',
	    'COLUMN_NAME' => 'klearUserId',
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
	  'klearRoleId' => 
	  array (
	    'SCHEMA_NAME' => NULL,
	    'TABLE_NAME' => 'KlearUsersRoles',
	    'COLUMN_NAME' => 'klearRoleId',
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
