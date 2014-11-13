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
 * Data Mapper implementation for Librecon\Model\Tags
 *
 * @package Mapper
 * @subpackage Sql
 * @author vvargas
 */
namespace Librecon\Mapper\Sql;
class Tags extends Raw\Tags
{
    public function save(\Librecon\Model\Raw\Tags $model)
    {
        $new = is_null($model->getId());

        if (!$new && $model->hasChange()) {
            //ACTUALIZADOR DE FECHA
            $model->setLastModified(\Zend_Date::now()->setTimezone('UTC'));
        }

        $resp = parent::save($model, false, false);

        return $resp;
    }

    public function saveRecursive(\Librecon\Model\Raw\Tags $model, $useTransaction = true, $transactionTag = null)
    {
        $new = is_null($model->getId());

        if (!$new && $model->hasChange()) {
            //ACTUALIZADOR DE FECHA
            $model->setLastModified(\Zend_Date::now()->setTimezone('UTC'));
        }

        $resp = parent::saveRecursive($model, $useTransaction, $transactionTag);

        return $resp;
    }
}
