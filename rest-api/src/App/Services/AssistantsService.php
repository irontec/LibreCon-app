<?php

namespace App\Services;
use App\Models\AssistantsModel as Assistant;

class AssistantsService extends BaseService
{
    public static $table = 'Assistants';

    public function getAll($requestHash = false){

    	$assistants = [];

      if($requestHash){
          $sqlResult = $this->db->fetchAll('SELECT * FROM '. self::$table . ' WHERE secretHash != ? and hidden=0', [$requestHash]);
      }else{
          $sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table . ' WHERE hidden=0');
      }

  		foreach ($sqlResult as $key => $value) {
  			$assistant = new Assistant();
  			$assistant->loadFromSqlRowPublic($value);
  			$assistants[] = $assistant;
  		}

		  return $assistants;
    }

    public function get($id, $meeting){

      $sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE id = ?', [$id]);

      $assistant = new Assistant();

      $emailShare = $meeting->sendedByMe == false? false:$meeting->getEmailShare();
      $cellphoneShare = $meeting->sendedByMe == false? false:$meeting->getCellphoneShare();
      
      $assistant->loadFromSqlRowPublic($sqlResult, $emailShare, $cellphoneShare);

      return $assistant;
    }

    public function update($userId, $uuid, $device, $lang){

      $assistant = [
        "uuid" => $uuid,
        "device" => $device,
        "lang" => $lang
      ];

      $this->db->update(self::$table, $assistant, ['id' => $userId]);

      return [
        'statusCode' => 200,
        'message' => 'Completed'
      ];
    }

}
