<?php

/**
 * Application Model Mapper
 *
 * @package Mapper
 * @subpackage Sql
 * @author vvargas
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Data Mapper implementation for Librecon\Model\Schedule
 *
 * @package Mapper
 * @subpackage Sql
 * @author vvargas
 */
namespace Librecon\Mapper\Sql;
class Schedule extends Raw\Schedule
{
    public function save(\Librecon\Model\Raw\Schedule $model)
    {
      $versionUpdater = new \Librecon_Helper_Version();
      $versionUpdater->updateVersion(get_class($this));

      $new = is_null($model->getId());

      if (!$new && $model->hasChange()) {
          //ACTUALIZADOR DE FECHA
          date_default_timezone_set('UTC');
          $model->setLastModified(date("Y-m-d H:i:s"));
      }

      $resp = parent::save($model, false, false);

      return $resp;
    }

    public function saveRecursive(\Librecon\Model\Raw\Schedule $model, $useTransaction = true, $transactionTag = null)
    {
      $versionUpdater = new \Librecon_Helper_Version();
      $versionUpdater->updateVersion(get_class($this));

      $new = is_null($model->getId());

      if (!$new && $model->hasChange()) {
          //ACTUALIZADOR DE FECHA
          date_default_timezone_set('UTC');
          $model->setLastModified(date("Y-m-d H:i:s"));
      }

      $resp = parent::saveRecursive($model, $useTransaction, $transactionTag);

      return $resp;
    }

    public function delete(\Librecon\Model\Raw\ModelAbstract $model)
    {
        $versionUpdater = new \Librecon_Helper_Version();
        $versionUpdater->updateVersion(get_class($this));

        $result = $this->getDbTable()->delete('id = '.$model->getId());

        return $result;
    }
}
