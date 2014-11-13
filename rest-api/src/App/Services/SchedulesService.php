<?php

namespace App\Services;
use App\Models\SchedulesModel as Schedule;

class SchedulesService extends BaseService
{
    public static $table = 'Schedule';

    public function getAll($targetDate = null){

    	$schedules = [];

    	$where = " ";

    	if($targetDate != null){
    		$where = " WHERE targetDate =".$targetDate;
    	}

    	$sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table.$where);

    	foreach ($sqlResult as $key => $value) {
    		$value['link1Type'] = $this->getLinkType($value['link1Type']);
    		$value['link2Type'] = $this->getLinkType($value['link2Type']);
    		$value['link3Type'] = $this->getLinkType($value['link3Type']);
    		$value['link4Type'] = $this->getLinkType($value['link4Type']);

    		$schedule = new Schedule();
    		$schedule->loadFromSqlRow($value);

    		$tagsJoin = $this->db->fetchAll("SELECT * FROM RelTagScheduele WHERE idScheduele =".$schedule->getId());
    		$schedule->setTags(TagsService::getTagsFromJoin($tagsJoin));

    		$speakersJoin = $this->db->fetchAll("SELECT * FROM RelScheduleSpeaker WHERE idSchedule =".$schedule->getId());
        
    		$schedule->setSpeakers(SpeakersService::getSpeakersFromJoin($speakersJoin));

    		$schedules[] = $schedule;
    	}
		return $schedules;

    }

    public function get($id){

    	$sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE id = ?', [$id]);

    	$sqlResult['link1Type'] = $this->getLinkType($sqlResult['link1Type']);
  		$sqlResult['link2Type'] = $this->getLinkType($sqlResult['link2Type']);
  		$sqlResult['link3Type'] = $this->getLinkType($sqlResult['link3Type']);
  		$sqlResult['link4Type'] = $this->getLinkType($sqlResult['link4Type']);

    	$schedule = new Schedule();
    	$schedule->loadFromSqlRow($sqlResult);

    	$tagsJoin = $this->db->fetchAll("SELECT * FROM RelTagScheduele WHERE idScheduele =".$id);
    	$schedule->setTags(TagsService::getTagsFromJoin($tagsJoin));

    	$speakersJoin = $this->db->fetchAll("SELECT * FROM RelScheduleSpeaker WHERE idSchedule =".$id);
		$schedule->setSpeakers(SpeakersService::getSpeakersFromJoin($speakersJoin));

    	return $schedule;
    }

    public function getLinkType($typeId){
    	$link = $this->db->fetchAssoc('SELECT * FROM Links WHERE id = ?', [$typeId]);
    	return $link['type'];
    }
}
