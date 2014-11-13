<?php

namespace App\Services;
use App\Models\SpeakersModel as Speaker;

class SpeakersService extends BaseService
{
    public static $table = 'Speaker';

    public function getAll(){
        $speakers = [];

		$sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table);

        foreach ($sqlResult as $key => $value) {
            $speaker = new Speaker();
            $speaker->loadFromSqlRow($value);

            $tagsJoin = $this->db->fetchAll("SELECT * FROM RelTagSpeaker WHERE idSpeaker =".$speaker->getId());

            $speaker->setTags(TagsService::getTagsFromJoin($tagsJoin));

            $speakers[] = $speaker;
        }
        return $speakers;
    }

    public function getSpeakersFromJoin($speakersJoin){
        $speakers = [];

        foreach ($speakersJoin as $key => $value) {
            $speaker = self::get($value['idSpeaker']);
            if(null != $speaker){
              $speakers[] = $speaker;
            }
        }
        return $speakers;
    }

    public function get($id){

    	$sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE id = ?', [$id]);
      if($sqlResult){
        $sqlResult['link1Type'] = $this->getLinkType($sqlResult['link1Type']);
        $sqlResult['link2Type'] = $this->getLinkType($sqlResult['link2Type']);
        $sqlResult['link3Type'] = $this->getLinkType($sqlResult['link3Type']);
        $sqlResult['link4Type'] = $this->getLinkType($sqlResult['link4Type']);
        $speaker = new Speaker();
        $speaker->loadFromSqlRow($sqlResult);

    	  $tagsJoin = $this->db->fetchAll("SELECT * FROM RelTagSpeaker WHERE idSpeaker =".$id);

        $speaker->setTags(TagsService::getTagsFromJoin($tagsJoin));

      	return $speaker;
      }else{
        return null;
      }
    }

    public function getLinkType($typeId){
      $link = $this->db->fetchAssoc('SELECT * FROM Links WHERE id = ?', [$typeId]);
      return $link['name'];
    }
}
