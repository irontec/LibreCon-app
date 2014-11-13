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
 * Data Mapper implementation for Librecon\Model\Meeting
 *
 * @package Mapper
 * @subpackage Sql
 * @author vvargas
 */
namespace Librecon\Mapper\Sql;
class Meeting extends Raw\Meeting
{
    public function save(\Librecon\Model\Raw\Meeting $model)
    {
        $versionUpdater = new \Librecon_Helper_Version();
        $versionUpdater->updateVersion(get_class($this));

        $new = is_null($model->getId());

        date_default_timezone_set('UTC');

        if ($new || $model->hasChange('atRightNow') || $model->hasChange('atHalfHour') || $model->hasChange('atOneHour')) {

            $model->setAtRightNowWhen(NULL);
            $model->setAtHalfHour(NULL);
            $model->setAtOneHourWhen(NULL);

            if ($model->getAtRightNow() == 1) {
                $model->setAtRightNowWhen(date("Y-m-d H:i:s"));
            } elseif ($model->getAtHalfHour() == 1) {
                $nuevaHora = strtotime ( '+30 minute' , strtotime (date("Y-m-d H:i:s")) ) ;
                $model->setAtHalfHour(date("Y-m-d H:i:s", $nuevaHora));
            } elseif ($model->getAtOneHour() == 1) {
                $nuevaHora = strtotime ( '+1 hour' , strtotime (date("Y-m-d H:i:s")) ) ;
                $model->setAtOneHourWhen(date("Y-m-d H:i:s", $nuevaHora));
            }
        }

        if (!$new) {
            //ACTUALIZADOR DE FECHA
            $model->setCreatedAt(date("Y-m-d H:i:s"));
        }

        $resp = parent::save($model, false, false);

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
