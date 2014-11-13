<?php

namespace App\Models;

class TxokosModel extends BaseModel
{
    public $title_es;
    public $title_eu;
    public $title_en;

    public $text_es;
    public $text_eu;
    public $text_en;
    public $orderField;
    
    public $picUrl;
    private $lastModified;

    public function __construct(){}

    public function loadFromSqlRow($row){
        $this->setId($row['id']);

        $this->setTitle($row['title_es']);
        $this->setTitleEU($row['title_eu']);
        $this->setTitleEN($row['title_en']);

        $this->setText($row['text_es']);
        $this->setTextEU($row['text_eu']);
        $this->setTextEN($row['text_en']);
        $this->setPicUrl($this->generateMediaUrl('txoko', $row['id'], $row['pictureFileSize']));
        $this->setOrderField($row['orderField']);
        
        $this->setLastModified($row['lastModified']);
    }

    public function setTitle($name){
        if($name != null){
            $this->title_es = $name;
        }else{
            $this->title_es = "";
        }
    }

    public function setTitleEU($name){
        if($name != null){
            $this->title_eu = $name;
        }else{
            $this->title_eu = "";
        }
    }

    public function setTitleEN($name){
        if($name != null){
            $this->title_en = $name;
        }else{
            $this->title_en = "";
        }
    }

    public function setText($name){
        if($name != null){
            $this->text_es = $name;
        }else{
            $this->text_es = "";
        }
    }

    public function setTextEU($name){
        if($name != null){
            $this->text_eu = $name;
        }else{
            $this->text_eu = "";
        }
    }

    public function setTextEN($name){
        if($name != null){
            $this->text_en = $name;
        }else{
            $this->text_en = "";
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
