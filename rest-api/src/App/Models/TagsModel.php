<?php

namespace App\Models;

class TagsModel extends BaseModel
{
    public $name_es;
    public $name_eu;
    public $name_en;
    public $color;
    private $lastModified;

    public function __construct(){}

    public function loadFromSqlRow($row){
        $this->setId($row['id']);
        $this->setName($row['name_es']);
        $this->setNameEU($row['name_eu']);
        $this->setNameEN($row['name_en']);
        $this->setColor($row['color']);
        $this->setLastModified($row['lastModified']);
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

    public function setColor($color){ 
        if($color != null){
            $this->color = $color; 
        }else{
            $this->color = ""; 
        }
    }

    public function setLastModified($datetime){
        if($datetime != null){
            $this->lastModified = $datetime; 
        }else{
            $this->lastModified = ""; 
        }
    }

    public function getName(){ return $this->name; }

    public function getNameEU(){ return $this->name_eu; }

    public function getNameEN(){ return $this->name_en; }

    public function getColor(){ return $this->color; }

    public function getLastModified(){ return $this->lastModified; }

}