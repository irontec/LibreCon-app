<?php
class Librecon_Auth_Model_User extends Klear_Model_User
{
    protected $_administrator = false;
    protected $_accessSections;

    public function setUserAccessSections($data)
    {
        $this->access = $data;
        $this->_accessSections = $data;
        return $this;
    }

    public function getUserAccessSections()
    {
        return $this->_accessSections;
    }

    public function setAdministrator($data)
    {
        $this->_administrator = $data;
        return $this;
    }

    public function getAdministrator()
    {
        return $this->_administrator;
    }
}