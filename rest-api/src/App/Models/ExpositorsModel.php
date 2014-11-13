<?php

namespace App\Models;

class ExpositorsModel extends BaseModel
{
    public $companyName;
    public $description_es;
    public $description_eu;
    public $description_en;
    public $picUrl;
    public $orderField;
    private $lastModified;

    public function __construct(){}

    public function loadFromSqlRow($row){
        $this->setId($row['id']);
        $this->setCompanyName($row['companyName']);
        $this->setDescription($row['description_es']);
        $this->setDescriptionEU($row['description_eu']);
        $this->setDescriptionEN($row['description_en']);
        $this->setPicUrl($this->generateMediaUrl('expositor', $row['id'], $row['logoFileSize']));
        $this->setLastModified($row['lastModified']);
        $this->setOrderField($row['orderField']);
    }

    public function setCompanyName($companyName){
        if($companyName != null){
            $this->companyName = $companyName;
        }else{
            $this->companyName = "";
        }
    }

    public function setDescription($description){
        if($description != null){
            $this->description_es = $description;
        }else{
            $this->description_es = "";
        }

    }

    public function setDescriptionEU($description){
        if($description != null){
            $this->description_eu = $description;
        }else{
            $this->description_eu = "";
        }
    }

    public function setDescriptionEN($description){
        if($description != null){
            $this->description_en = $description;
        }else{
            $this->description_en = "";
        }
    }

    public function setPicUrl($picUrl){
        if($picUrl != null){
            $this->picUrl = $picUrl;
        }else{
            $this->picUrl = "";
        }
    }

    public function setLastModified($datetime){
        if($datetime != null){
            $this->lastModified = $datetime;
        }else{
            $this->lastModified = "";
        }
    }
    
    public function setOrderField($orderField){
        if($orderField != null){
            $this->orderField = (int)$orderField;
        }else{
            $this->orderField = 0;
        }
    }

}
