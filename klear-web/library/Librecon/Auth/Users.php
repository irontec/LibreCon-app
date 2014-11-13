<?php
use \Librecon\Mapper\Sql\KlearUsers as KlearUsersMapper;

class Librecon_Auth_Users implements Klear_Auth_Adapter_Interfaces_BasicUserMapper
{
    protected $_dbTable;

    /**
     *
     * @var \Domoalert\Model\KlearUsers
     */
    protected $_atezateUser;

    public function __construct()
    {
        $this->_dbTable = new Klear_Model_DbTable_Users();
    }

    public function findByLogin($login)
    {
        $select = $this->_dbTable->select()->where('login = ?', $login);
        $row = $this->_dbTable->fetchRow($select);

        if ($row) {

            $user = new Librecon_Auth_Model_User();
            return $this->_poblateUser($user, $row);
        }
        
        return null;
    }

    protected function _poblateUser(Klear_Model_User $user, Zend_Db_Table_Row $row)
    {
        $user->setId($row->userId);
        $user->setLogin($row->login);
        $user->setEmail($row->email);
        $user->setPassword($row->pass);
        $user->setActive($row->active);
        
        $userMapper = new KlearUsersMapper();
        $this->_atezateUser = $userMapper->findOneByField('userId', $row->userId);
        $roles = $this->_atezateUser->getKlearUsersRoles();
        $aSections = array();
        

        foreach ($roles as $rol) {

            $secs = $rol->getKlearRole()->getKlearRolesSections();

            foreach ($secs as $sec) {

                $aSections[] = $sec->getKlearSection()->getIdentifier();
            }
        }
        array_unique($aSections);

        $user->setUserAccessSections($aSections);

        if ($user->getId() == '1') {

            $user->setAdministrator(true);
        }

        return $user;
    }
}