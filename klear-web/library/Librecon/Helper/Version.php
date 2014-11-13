<?php
class Librecon_Helper_Version extends Zend_Controller_Action_Helper_Abstract
{

  /**
   * Actualiza las Versiones
   * @param string $className
   * @return boolean true if everything was fine
   * @throws Exception
   */
  public function updateVersion($className)
  {
    $versionsMapper = new \Librecon\Mapper\Sql\Versions();

    if (preg_match('@\\\\([\w]+)$@', $className, $matches)) {
        $className = $matches[1];
    }

    $version = $versionsMapper->fetchOne('`table` = "'. $className . '"');

    if($version != null){
      $version->setVersion('CURRENT_TIMESTAMP');
      $version->save();
    }else{
      $version = new \Librecon\Model\Versions();
      $version->setTable($className);
      $version->setVersion('CURRENT_TIMESTAMP');
      $version->save();
    }
  }
}
