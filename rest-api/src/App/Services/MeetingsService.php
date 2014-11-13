<?php

namespace App\Services;
use App\Models\MeetingsModel as Meeting;


class MeetingsService extends BaseService
{
    public static $table = 'Meeting';

    public function getAll($userId){

    	$meetings = [];

    	$where = " WHERE sender =" . $userId . " OR receiver =" . $userId;

    	$sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table.$where);

    	foreach ($sqlResult as $key => $value) {
    		$meeting = new Meeting();
    		$meeting->loadFromSqlRow($value);
    		
    		
            if($value['sender'] == $userId){
                $meeting->setSendedByMe(true);
                $meeting->setAssistant(AssistantsService::get($value['receiver'], $meeting));
            }else{
                $meeting->setSendedByMe(false);
                $meeting->setAssistant(AssistantsService::get($value['sender'], $meeting));
            }
    		$meetings[] = $meeting;
    	}
	    return $meetings;
    }

    public function getOne($userId, $meetingId){

      $meetings = [];

      $where = " WHERE (sender =" . $userId . " OR receiver =" . $userId . ") AND id = " . $meetingId;

      $sqlResult = $this->db->fetchAssoc("SELECT * FROM ".self::$table.$where);

      if($sqlResult != false){
          
        $meeting = new Meeting();
        $meeting->loadFromSqlRow($sqlResult);
        if($sqlResult['sender'] == $userId){
          $meeting->setSendedByMe(true);
          $meeting->setAssistant(AssistantsService::get($sqlResult['receiver'], $meeting));
        }else{
          $meeting->setSendedByMe(false);
          $meeting->setAssistant(AssistantsService::get($sqlResult['sender'], $meeting));
        }
        
        return [
          'status' => 'successs',
          'statusCode' => 200,
          'data' => array(
            'meeting' => $meeting
          )
        ];
      }else{
        return [
          'status' => 'fail',
          'statusCode' => 404,
          'data' => array(
            'message' => 'Not Found'
          )

        ];
      }


      foreach ($sqlResult as $key => $value) {
        $meeting = new Meeting();
        $meeting->loadFromSqlRow($value);
            if($value['sender'] == $userId){
                $meeting->setAssistant(AssistantsService::get($value['receiver'], $meeting), true);
            }else{
                $meeting->setAssistant(AssistantsService::get($value['sender'], $meeting), false);
            }
        $meetings[] = $meeting;
      }
      return $meetings;
    }

    public function create($sender, $receiver){

      $where = " WHERE id =" . $receiver;
      $sqlResult = $this->db->fetchAssoc("SELECT * FROM Assistants ".$where);

      if(!$sqlResult){
        return [
          'status' => 'fail',
          'statusCode' => 406,
          'message' => 'Receiver not found'
        ];
      }

      $where = " WHERE sender =" . $sender . " AND receiver =" . $receiver . " AND status = 'pending'";
      $sqlResult = $this->db->fetchAssoc("SELECT * FROM ".self::$table.$where);

      if($sqlResult != false){
        return [
          'status' => 'fail',
          'statusCode' => 406,
          'message' => 'Not Acceptable'
        ];
      }

      $where = " WHERE sender =" . $receiver . " AND receiver =" . $sender . " AND status = 'pending'";
      $sqlResult = $this->db->fetchAssoc("SELECT * FROM ".self::$table.$where);

      if($sqlResult != false){
        return [
          'status' => 'fail',
          'statusCode' => 409,
          'message' => 'Conflict'
        ];
      }

    	$meeting = [
    		"sender" => (int)$sender,
    		"receiver" => (int)$receiver
    	];

        $this->db->insert(self::$table, $meeting);
        
        $idMetting = $this->db->lastInsertId();
        
        return [
            'status' => 'success',
            'statusCode' => 200,
            'message' => 'Completed',
            'id' => $idMetting
        ];
    }

    public function update($userId, $meetingId, $options){

    	$where = " WHERE id =" . $meetingId;
    	$sqlResult = $this->db->fetchAssoc("SELECT * FROM ".self::$table.$where);

    	if($sqlResult['receiver'] != $userId ){
    		return [
          'status' => 'fail',
    			'statusCode' => 403,
    			'message' => 'Forbidden'
    		];
    	}

      if($sqlResult['status'] != "pending"){
        return [
          'status' => 'fail',
          'statusCode' => 405,
          'message' => 'Method Not Allowed'
        ];
      }

    	$momentColumn = $this->getColumnFromMoment($options["moment"]);

      if(!$momentColumn){
        return [
          'status' => 'fail',
          'statusCode' => 400,
          'message' => 'Bad Request'
        ];
      }

      if($momentColumn != 'canceled'){
          $meeting = [
              "status" => "accepted",
              "emailShare" => (int)filter_var($options["emailShare"], FILTER_VALIDATE_BOOLEAN),
              "cellphoneShare" => (int)filter_var($options["cellphoneShare"], FILTER_VALIDATE_BOOLEAN),
              $momentColumn => 1,
              "responseDate" => date('Y-m-d H:i:s')
          ];
          
      } else {
          $meeting = [
              "status" => $momentColumn,
              "emailShare" => (int)filter_var($options["emailShare"], FILTER_VALIDATE_BOOLEAN),
              "cellphoneShare" => (int)filter_var($options["cellphoneShare"], FILTER_VALIDATE_BOOLEAN),
              "responseDate" => date('Y-m-d H:i:s')
          ];
      }

      $this->db->update(self::$table, $meeting, ['id' => $meetingId]);
      return [
        'status' => 'success',
        'statusCode' => 200,
        'message' => 'Completed',
        'id' => $meetingId,
        'sender' => $sqlResult['sender']
        ];
    }

    public function getColumnFromMoment($moment){
    	$column = '';
    	switch ($moment) {
    		case 'now':
    			$column = 'atRightNow';
    			break;
    		case 'half':
    			$column = 'atHalfHour';
    			break;
    		case 'hour':
    			$column = 'atOneHour';
    			break;
        case 'never':
            $column = 'canceled';
            break;
        default:
            $column = false;
            break;
    	}
    	return $column;
    }
}
