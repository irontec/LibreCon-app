<?php

/**
 * Application Model Mapper
 *
 * @package Librecon\Mapper\Sql
 * @subpackage Raw
 * @author vvargas
 * @copyright ZF model generator
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Data Mapper implementation for Librecon\Model\Schedule
 *
 * @package Librecon\Mapper\Sql
 * @subpackage Raw
 * @author vvargas
 */

namespace Librecon\Mapper\Sql\Raw;
class Schedule extends MapperAbstract
{
    protected $_modelName = 'Librecon\\Model\\Schedule';


    protected $_urlIdentifiers = array();

    /**
     * Returns an array, keys are the field names.
     *
     * @param Librecon\Model\Raw\Schedule $model
     * @return array
     */
    public function toArray($model)
    {
        if (!$model instanceof \Librecon\Model\Raw\Schedule) {
            if (is_object($model)) {
                $message = get_class($model) . " is not a \Librecon\Model\Raw\Schedule object in toArray for " . get_class($this);
            } else {
                $message = "$model is not a \\Librecon\Model\\Schedule object in toArray for " . get_class($this);
            }

            $this->_logger->log($message, \Zend_Log::ERR);
            throw new \Exception('Unable to create array: invalid model passed to mapper', 2000);
        }

        $result = array(
            'id' => $model->getId(),
            'targetDate' => $model->getTargetDate(),
            'name_eu' => $model->getNameEu(),
            'name_en' => $model->getNameEn(),
            'name_es' => $model->getNameEs(),
            'description_eu' => $model->getDescriptionEu(),
            'description_en' => $model->getDescriptionEn(),
            'description_es' => $model->getDescriptionEs(),
            'pictureFileSize' => $model->getPictureFileSize(),
            'pictureMimeType' => $model->getPictureMimeType(),
            'pictureBaseName' => $model->getPictureBaseName(),
            'startDateTime' => $model->getStartDateTime(),
            'finishDateTime' => $model->getFinishDateTime(),
            'lastModified' => $model->getLastModified(),
            'link1' => $model->getLink1(),
            'link1Type' => $model->getLink1Type(),
            'link2' => $model->getLink2(),
            'link2Type' => $model->getLink2Type(),
            'link3' => $model->getLink3(),
            'link3Type' => $model->getLink3Type(),
            'link4' => $model->getLink4(),
            'link4Type' => $model->getLink4Type(),
            'location' => $model->getLocation(),
            'iconFileSize' => $model->getIconFileSize(),
            'iconMimeType' => $model->getIconMimeType(),
            'iconBaseName' => $model->getIconBaseName(),
            'color' => $model->getColor(),
        );

        return $result;
    }

    /**
     * Returns the DbTable class associated with this mapper
     *
     * @return Librecon\\Mapper\\Sql\\DbTable\\Schedule
     */
    public function getDbTable()
    {
        if (is_null($this->_dbTable)) {
            $this->setDbTable('Librecon\\Mapper\\Sql\\DbTable\\Schedule');
        }

        return $this->_dbTable;
    }

    /**
     * Deletes the current model
     *
     * @param Librecon\Model\Raw\Schedule $model The model to delete
     * @see Librecon\Mapper\DbTable\TableAbstract::delete()
     * @return int
     */
    public function delete(\Librecon\Model\Raw\ModelAbstract $model)
    {
        if (!$model instanceof \Librecon\Model\Raw\Schedule) {
            if (is_object($model)) {
                $message = get_class($model) . " is not a \\Librecon\\Model\\Schedule object in delete for " . get_class($this);
            } else {
                $message = "$model is not a \\Librecon\\Model\\Schedule object in delete for " . get_class($this);
            }

            $this->_logger->log($message, \Zend_Log::ERR);
            throw new \Exception('Unable to delete: invalid model passed to mapper', 2000);
        }

        $useTransaction = true;

        $dbTable = $this->getDbTable();
        $dbAdapter = $dbTable->getAdapter();

        try {

            $dbAdapter->beginTransaction();

        } catch (\Exception $e) {

            //Transaction already started
            $useTransaction = false;
        }

        try {

            //onDeleteCascades emulation
            if ($this->_simulateReferencialActions && count($deleteCascade = $model->getOnDeleteCascadeRelationships()) > 0) {

                $depList = $model->getDependentList();

                foreach ($deleteCascade as $fk) {

                    $capitalizedFk = '';
                    foreach (explode("_", $fk) as $part) {

                        $capitalizedFk .= ucfirst($part);
                    }

                    if (!isset($depList[$capitalizedFk])) {

                        continue;

                    } else {

                        $relDbAdapName = 'Librecon\\Mapper\\Sql\\DbTable\\' . $depList[$capitalizedFk]["table_name"];
                        $depMapperName = 'Librecon\\Mapper\\Sql\\' . $depList[$capitalizedFk]["table_name"];
                        $depModelName = 'Librecon\\Model\\' . $depList[$capitalizedFk]["table_name"];

                        if ( class_exists($relDbAdapName) && class_exists($depModelName) ) {

                            $relDbAdapter = new $relDbAdapName;
                            $references = $relDbAdapter->getReference('Librecon\\Mapper\\Sql\\DbTable\\Schedule', $capitalizedFk);

                            $targetColumn = array_shift($references["columns"]);
                            $where = $relDbAdapter->getAdapter()->quoteInto($targetColumn . ' = ?', $model->getPrimaryKey());

                            $depMapper = new $depMapperName;
                            $depObjects = $depMapper->fetchList($where);

                            if (count($depObjects) === 0) {

                                continue;
                            }

                            foreach ($depObjects as $item) {

                                $item->delete();
                            }
                        }
                    }
                }
            }

            //onDeleteSetNull emulation
            if ($this->_simulateReferencialActions && count($deleteSetNull = $model->getOnDeleteSetNullRelationships()) > 0) {

                $depList = $model->getDependentList();

                foreach ($deleteSetNull as $fk) {

                    $capitalizedFk = '';
                    foreach (explode("_", $fk) as $part) {

                        $capitalizedFk .= ucfirst($part);
                    }

                    if (!isset($depList[$capitalizedFk])) {

                        continue;

                    } else {

                        $relDbAdapName = 'Librecon\\Mapper\\Sql\\DbTable\\' . $depList[$capitalizedFk]["table_name"];
                        $depMapperName = 'Librecon\\Mapper\\Sql\\' . $depList[$capitalizedFk]["table_name"];
                        $depModelName = 'Librecon\\Model\\' . $depList[$capitalizedFk]["table_name"];

                        if ( class_exists($relDbAdapName) && class_exists($depModelName) ) {

                            $relDbAdapter = new $relDbAdapName;
                            $references = $relDbAdapter->getReference('Librecon\\Mapper\\Sql\\DbTable\\Schedule', $capitalizedFk);

                            $targetColumn = array_shift($references["columns"]);
                            $where = $relDbAdapter->getAdapter()->quoteInto($targetColumn . ' = ?', $model->getPrimaryKey());

                            $depMapper = new $depMapperName;
                            $depObjects = $depMapper->fetchList($where);

                            if (count($depObjects) === 0) {

                                continue;
                            }

                            foreach ($depObjects as $item) {

                                $setterName = 'set' . ucfirst($targetColumn);
                                $item->$setterName(null);
                                $item->save();
                            } //end foreach

                        } //end if
                    } //end else

                }//end foreach ($deleteSetNull as $fk)
            } //end if

            $where = $dbAdapter->quoteInto($dbAdapter->quoteIdentifier('id') . ' = ?', $model->getId());
            $result = $dbTable->delete($where);

            if ($this->_cache) {

                $this->_cache->remove(get_class($model) . "_" . $model->getPrimarykey());
            }

            $fileObjects = array();
            $availableObjects = $model->getFileObjects();

            foreach ($availableObjects as $fso) {

                $removeMethod = 'remove' . $fso;
                $model->$removeMethod();
            }


            if ($useTransaction) {
                $dbAdapter->commit();
            }
        } catch (\Exception $exception) {

            $message = 'Exception encountered while attempting to delete ' . get_class($this);
            if (!empty($where)) {
                $message .= ' Where: ';
                $message .= $where;
            } else {
                $message .= ' with an empty where';
            }

            $message .= ' Exception: ' . $exception->getMessage();
            $this->_logger->log($message, \Zend_Log::ERR);
            $this->_logger->log($exception->getTraceAsString(), \Zend_Log::DEBUG);

            if ($useTransaction) {

                $dbAdapter->rollback();
            }

            throw $exception;
        }

        return $result;
    }

    /**
     * Saves current row
     * @return boolean If the save action was successful
     */
    public function save(\Librecon\Model\Raw\Schedule $model)
    {
        return $this->_save($model, false, false);
    }

    /**
     * Saves current and all dependent rows
     *
     * @param \Librecon\Model\Raw\Schedule $model
     * @param boolean $useTransaction Flag to indicate if save should be done inside a database transaction
     * @return boolean If the save action was successful
     */
    public function saveRecursive(\Librecon\Model\Raw\Schedule $model, $useTransaction = true, $transactionTag = null)
    {
        return $this->_save($model, true, $useTransaction, $transactionTag);
    }

    protected function _save(\Librecon\Model\Raw\Schedule $model,
        $recursive = false, $useTransaction = true, $transactionTag = null
    )
    {
        $this->_setCleanUrlIdentifiers($model);

        $fileObjects = array();

        $availableObjects = $model->getFileObjects();
        $fileSpects = array();

        foreach ($availableObjects as $item) {

            $objectMethod = 'fetch' . $item;
            $fso = $model->$objectMethod(false);

            if (!is_null($fso) && $fso->mustFlush()) {

                $fileObjects[$item] = $fso;
                $specMethod = 'get' . $item . 'Specs';
                $fileSpects[$item] = $model->$specMethod();

                $fileSizeSetter = 'set' . $fileSpects[$item]['sizeName'];
                $baseNameSetter = 'set' . $fileSpects[$item]['baseNameName'];
                $mimeTypeSetter = 'set' . $fileSpects[$item]['mimeName'];

                $model->$fileSizeSetter($fso->getSize())
                      ->$baseNameSetter($fso->getBaseName())
                      ->$mimeTypeSetter($fso->getMimeType());
            }
        }

        $data = $model->sanitize()->toArray();

        $primaryKey = $model->getId();
        $success = true;

        if ($useTransaction) {

            try {

                if ($recursive && is_null($transactionTag)) {

                    //$this->getDbTable()->getAdapter()->query('SET transaction_allow_batching = 1');
                }

                $this->getDbTable()->getAdapter()->beginTransaction();

            } catch (\Exception $e) {

                //transaction already started
            }


            $transactionTag = 't_' . rand(1, 999) . str_replace(array('.', ' '), '', microtime());
        }

        unset($data['id']);

        try {
            if (is_null($primaryKey) || empty($primaryKey)) {
                $primaryKey = $this->getDbTable()->insert($data);
                if ($primaryKey) {
                    $model->setId($primaryKey);
                } else {
                    throw new \Exception("Insert sentence did not return a valid primary key", 9000);
                }

                if ($this->_cache) {

                    $parentList = $model->getParentList();

                    foreach ($parentList as $constraint => $values) {

                        $refTable = $this->getDbTable();

                        $ref = $refTable->getReference('Librecon\\Mapper\\Sql\\DbTable\\' . $values["table_name"], $constraint);
                        $column = array_shift($ref["columns"]);

                        $cacheHash = 'Librecon\\Model\\' . $values["table_name"] . '_' . $data[$column] .'_' . $constraint;

                        if ($this->_cache->test($cacheHash)) {

                            $cachedRelations = $this->_cache->load($cacheHash);
                            $cachedRelations->results[] = $primaryKey;

                            if ($useTransaction) {

                                $this->_cache->save($cachedRelations, $cacheHash, array($transactionTag));

                            } else {

                                $this->_cache->save($cachedRelations, $cacheHash);
                            }
                        }
                    }
                }
            } else {
                $this->getDbTable()
                     ->update(
                         $data,
                         array(
                             $this->getDbTable()->getAdapter()->quoteIdentifier('id') . ' = ?' => $primaryKey
                         )
                     );
            }

            if (is_numeric($primaryKey) && !empty($fileObjects)) {

                foreach ($fileObjects as $key => $fso) {

                    $baseName = $fso->getBaseName();

                    if (!empty($baseName)) {

                        $fso->flush($primaryKey);
                    }
                }
            }


            if ($recursive) {
                if ($model->getRelScheduleSpeaker(null, null, true) !== null) {
                    $relScheduleSpeaker = $model->getRelScheduleSpeaker();

                    if (!is_array($relScheduleSpeaker)) {

                        $relScheduleSpeaker = array($relScheduleSpeaker);
                    }

                    foreach ($relScheduleSpeaker as $value) {
                        $value->setIdSchedule($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

                if ($model->getRelTagScheduele(null, null, true) !== null) {
                    $relTagScheduele = $model->getRelTagScheduele();

                    if (!is_array($relTagScheduele)) {

                        $relTagScheduele = array($relTagScheduele);
                    }

                    foreach ($relTagScheduele as $value) {
                        $value->setIdScheduele($primaryKey)
                              ->saveRecursive(false, $transactionTag);
                    }
                }

            }

            if ($success === true) {

                foreach ($model->getOrphans() as $itemToDelete) {

                    $itemToDelete->delete();
                }

                $model->resetOrphans();
            }

            if ($useTransaction && $success) {

                $this->getDbTable()->getAdapter()->commit();

            } elseif ($useTransaction) {

                $this->getDbTable()->getAdapter()->rollback();

                if ($this->_cache) {

                    $this->_cache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($transactionTag));
                }
            }

        } catch (\Exception $e) {
            $message = 'Exception encountered while attempting to save ' . get_class($this);
            if (!empty($primaryKey)) {
                $message .= ' id: ' . $primaryKey;
            } else {
                $message .= ' with an empty primary key ';
            }

            $message .= ' Exception: ' . $e->getMessage();
            $this->_logger->log($message, \Zend_Log::ERR);
            $this->_logger->log($e->getTraceAsString(), \Zend_Log::DEBUG);

            if ($useTransaction) {
                $this->getDbTable()->getAdapter()->rollback();

                if ($this->_cache) {

                    if ($transactionTag) {

                        $this->_cache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($transactionTag));

                    } else {

                        $this->_cache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG);
                    }
                }
            }

            throw $e;
        }

        if ($success && $this->_cache) {

            if ($useTransaction) {

                $this->_cache->save($model->toArray(), get_class($model) . "_" . $model->getPrimaryKey(), array($transactionTag));

            } else {

                $this->_cache->save($model->toArray(), get_class($model) . "_" . $model->getPrimaryKey());
            }
        }

        if ($success === true) {

            return $primaryKey;
        }

        return $success;
    }

    /**
     * Loads the model specific data into the model object
     *
     * @param \Zend_Db_Table_Row_Abstract|array $data The data as returned from a \Zend_Db query
     * @param Librecon\Model\Raw\Schedule|null $entry The object to load the data into, or null to have one created
     * @return Librecon\Model\Raw\Schedule The model with the data provided
     */
    public function loadModel($data, $entry = null)
    {
        if (!$entry) {
            $entry = new \Librecon\Model\Schedule();
        }

        // We don't need to log changes as we will reset them later...
        $entry->stopChangeLog();

        if (is_array($data)) {
            $entry->setId($data['id'])
                  ->setTargetDate($data['targetDate'])
                  ->setNameEu($data['name_eu'])
                  ->setNameEn($data['name_en'])
                  ->setNameEs($data['name_es'])
                  ->setDescriptionEu($data['description_eu'])
                  ->setDescriptionEn($data['description_en'])
                  ->setDescriptionEs($data['description_es'])
                  ->setPictureFileSize($data['pictureFileSize'])
                  ->setPictureMimeType($data['pictureMimeType'])
                  ->setPictureBaseName($data['pictureBaseName'])
                  ->setStartDateTime($data['startDateTime'])
                  ->setFinishDateTime($data['finishDateTime'])
                  ->setLastModified($data['lastModified'])
                  ->setLink1($data['link1'])
                  ->setLink1Type($data['link1Type'])
                  ->setLink2($data['link2'])
                  ->setLink2Type($data['link2Type'])
                  ->setLink3($data['link3'])
                  ->setLink3Type($data['link3Type'])
                  ->setLink4($data['link4'])
                  ->setLink4Type($data['link4Type'])
                  ->setLocation($data['location'])
                  ->setIconFileSize($data['iconFileSize'])
                  ->setIconMimeType($data['iconMimeType'])
                  ->setIconBaseName($data['iconBaseName'])
                  ->setColor($data['color']);
        } else if ($data instanceof \Zend_Db_Table_Row_Abstract || $data instanceof \stdClass) {
            $entry->setId($data->{'id'})
                  ->setTargetDate($data->{'targetDate'})
                  ->setNameEu($data->{'name_eu'})
                  ->setNameEn($data->{'name_en'})
                  ->setNameEs($data->{'name_es'})
                  ->setDescriptionEu($data->{'description_eu'})
                  ->setDescriptionEn($data->{'description_en'})
                  ->setDescriptionEs($data->{'description_es'})
                  ->setPictureFileSize($data->{'pictureFileSize'})
                  ->setPictureMimeType($data->{'pictureMimeType'})
                  ->setPictureBaseName($data->{'pictureBaseName'})
                  ->setStartDateTime($data->{'startDateTime'})
                  ->setFinishDateTime($data->{'finishDateTime'})
                  ->setLastModified($data->{'lastModified'})
                  ->setLink1($data->{'link1'})
                  ->setLink1Type($data->{'link1Type'})
                  ->setLink2($data->{'link2'})
                  ->setLink2Type($data->{'link2Type'})
                  ->setLink3($data->{'link3'})
                  ->setLink3Type($data->{'link3Type'})
                  ->setLink4($data->{'link4'})
                  ->setLink4Type($data->{'link4Type'})
                  ->setLocation($data->{'location'})
                  ->setIconFileSize($data->{'iconFileSize'})
                  ->setIconMimeType($data->{'iconMimeType'})
                  ->setIconBaseName($data->{'iconBaseName'})
                  ->setColor($data->{'color'});

        } else if ($data instanceof \Librecon\Model\Raw\Schedule) {
            $entry->setId($data->getId())
                  ->setTargetDate($data->getTargetDate())
                  ->setNameEu($data->getNameEu())
                  ->setNameEn($data->getNameEn())
                  ->setNameEs($data->getNameEs())
                  ->setDescriptionEu($data->getDescriptionEu())
                  ->setDescriptionEn($data->getDescriptionEn())
                  ->setDescriptionEs($data->getDescriptionEs())
                  ->setPictureFileSize($data->getPictureFileSize())
                  ->setPictureMimeType($data->getPictureMimeType())
                  ->setPictureBaseName($data->getPictureBaseName())
                  ->setStartDateTime($data->getStartDateTime())
                  ->setFinishDateTime($data->getFinishDateTime())
                  ->setLastModified($data->getLastModified())
                  ->setLink1($data->getLink1())
                  ->setLink1Type($data->getLink1Type())
                  ->setLink2($data->getLink2())
                  ->setLink2Type($data->getLink2Type())
                  ->setLink3($data->getLink3())
                  ->setLink3Type($data->getLink3Type())
                  ->setLink4($data->getLink4())
                  ->setLink4Type($data->getLink4Type())
                  ->setLocation($data->getLocation())
                  ->setIconFileSize($data->getIconFileSize())
                  ->setIconMimeType($data->getIconMimeType())
                  ->setIconBaseName($data->getIconBaseName())
                  ->setColor($data->getColor());

        }

        $entry->resetChangeLog()->initChangeLog()->setMapper($this);

        return $entry;
    }
}
