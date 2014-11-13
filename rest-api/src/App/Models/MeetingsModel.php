<?php

namespace App\Models;

class MeetingsModel extends BaseModel
{
    public $assistant;
    public $sendedByMe;
    public $createdAt;
    public $status;
    public $emailShare = false;
    public $cellphoneShare = false;

    public $moment;
    public $responseDate;

    public function __construct(){}

    public function loadFromSqlRow($row){
        $this->setId($row['id']);
        $this->setCreatedAt($row['createdAt']);
        $this->setStatus($row['status']);
        $this->setEmailShare($row['emailShare']);
        $this->setCellphoneShare($row['cellphoneShare']);
        $this->setMoment($row);
        $this->setResponseDate($row['responseDate']);
    }

    public function setCreatedAt($createdAt){
        if($createdAt != null){
            $this->createdAt = $createdAt;
        }else{
            $this->createdAt = "";
        }
    }

    public function setStatus($status){
        if($status != null){
            $this->status = $status;
        }else{
            $this->status = "";
        }
    }

    public function setEmailShare($emailShare){
        if($emailShare != null){
            $this->emailShare = (bool)filter_var($emailShare, FILTER_VALIDATE_BOOLEAN);
        }else{
            $this->emailShare = false;
        }
    }

    public function setCellphoneShare($cellphoneShare){
        if($cellphoneShare != null){
            $this->cellphoneShare = (bool)filter_var($cellphoneShare, FILTER_VALIDATE_BOOLEAN);
        }else{
            $this->cellphoneShare = false;
        }
    }
    
    public function getEmailShare()
    {
        return $this->emailShare;
    }

    public function getCellphoneShare()
    {
        return $this->cellphoneShare;
    }
    
    public function setMoment($row){

      if($row['atRightNow'] != 0){
        $this->moment = 'atRightNow';
      }
      else if($row['atHalfHour'] != 0){
        $this->moment = 'atHalfHour';
      }
      else if($row['atOneHour'] != 0){
        $this->moment = 'atOneHour';
      }
      else if($row['status'] == 'canceled'){
        $this->moment = 'never';
      }

      if($this->moment == null){
        $this->moment = "";
      }

    }

    public function setResponseDate($responseDate) {
      if($responseDate != null){
          $this->responseDate = $responseDate;
      }else{
          $this->responseDate = "";
      }
    }
    
    public function setSendedByMe($sendedByMe){
        if($sendedByMe != null){
            $this->sendedByMe = $sendedByMe;
        }else{
            $this->sendedByMe = false;
        }
    }

    public function setAssistant($assistant){
        if($assistant != null){
            $this->assistant = $assistant;
        }else{
            $this->assistant = "";
        }
        
    }
}
