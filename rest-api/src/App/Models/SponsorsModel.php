<?php

namespace App\Models;

class SponsorsModel extends BaseModel
{
    public $name_es;
    public $name_en;
    public $name_eu;
    public $picUrl;
    public $url;
    public $orderField;
    
    private $lastModified;

    public function __construct(){}

    public function loadFromSqlRow($row){
        $this->setId($row['id']);
        $this->setName($row['name_es']);
        $this->setNameEU($row['name_eu']);
        $this->setNameEN($row['name_en']);
        $this->setPicUrl($this->generateMediaUrl('sponsor', $row['id'], $row['logoFileSize']));
        $this->setUrl($row['url']);
        $this->setLastModified($row['lastModified']);
        $this->setOrderField($row['orderField']);
        
    }

    public function setName($name){
        if($name != null){
            $this->name_es = $name;
        }else{
            $this->name_es = "";
        }
    }

    public function setNameEU($name){
        if($name != null){
            $this->name_eu = $name;
        }else{
            $this->name_eu = "";
        }
    }

    public function setNameEN($name){
        if($name != null){
            $this->name_en = $name;
        }else{
            $this->name_en = "";
        }
    }

    public function setPicUrl($picUrl){
        if($picUrl != null){
            $this->picUrl = $picUrl;
        }else{
            $this->picUrl = "";
        }
    }

    public function setUrl($url){
        if($url != null){
            $this->url = $url;
        }else{
            $this->url = "";
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
