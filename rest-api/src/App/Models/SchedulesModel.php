<?php

namespace App\Models;

class SchedulesModel extends BaseModel
{
    public $targetDate;
    public $name_es;
    public $name_eu;
    public $name_en;
    public $description_es;
    public $description_eu;
    public $description_en;
    public $picUrl;
    public $picUrlSquare;
    public $startDateTime;
    public $finishDateTime;
    public $location;
    public $color;

    private $lastModified;

    public $tags;
    public $links;
    public $speakers;

    public function __construct(){
        $this->tags = [];
        $this->links = [];
        $this->speakers = [];
    }

    public function loadFromSqlRow($row){
        $this->setId($row['id']);
        $this->setTargetDate($row['targetDate']);
        $this->setName($row['name_es']);
        $this->setNameEU($row['name_eu']);
        $this->setNameEN($row['name_en']);
        $this->setDescription($row['description_es']);
        $this->setDescriptionEU($row['description_eu']);
        $this->setDescriptionEN($row['description_en']);
        $this->setColor($row['color']);
        $this->setPicUrlSquare($this->generateMediaUrl('schedule', $row['id'], $row['iconFileSize'], 'square'));
        $this->setPicUrl($this->generateMediaUrl('schedule', $row['id'], $row['iconFileSize'], 'landscape'));
        $this->setStartDateTime($row['startDateTime']);
        $this->setFinishDateTime($row['finishDateTime']);
        $this->setLocation($row['location']);
        $this->setLastModified($row['lastModified']);
        $this->addLink($row['link1'], $row['link1Type']);
        $this->addLink($row['link2'], $row['link2Type']);
        $this->addLink($row['link3'], $row['link3Type']);
        $this->addLink($row['link4'], $row['link4Type']);
    }

    //GETTERS & SETTERS

    public function addLink($url, $type){
        if($url != null){
            $link = [];
            $link['url'] = $url;
            $link['type'] = $type;
            $this->links[] = $link;
        }

    }

    public function setTargetDate($targetDate){
        if($targetDate != null){
            $this->targetDate = $targetDate;
        }else{
            $this->targetDate = "";
        }
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

    public function setPicUrlSquare($iconUrl){
        if($iconUrl != null){
            $this->picUrlSquare = $iconUrl;
        }else{
            $this->picUrlSquare = "";
        }
    }

    public function setTags($tags){
        if($tags != null){
            $this->tags = $tags;
        }else{
            $this->tags = [];
        }
    }

    public function setSpeakers($speakers){
        if($speakers != null){
            $this->speakers = $speakers;
        }else{
            $this->speakers = [];
        }
    }

    public function setStartDateTime($datetime){
        if($datetime != null){
            $date = new \DateTime($datetime, new \DateTimeZone('UTC'));
            $this->startDateTime = $date->setTimezone(new \DateTimeZone(date_default_timezone_get()))->format('Y-m-d H:i:s');
        }else{
            $this->startDateTime = "";
        }
    }

    public function setFinishDateTime($datetime){
        if($datetime != null){
            $date = new \DateTime($datetime, new \DateTimeZone('UTC'));
            $this->finishDateTime = $date->setTimezone(new \DateTimeZone(date_default_timezone_get()))->format('Y-m-d H:i:s');

        }else{
            $this->finishDateTime = "";
        }
    }

    public function setLastModified($datetime){
        if($datetime != null){
            $this->lastModified = $datetime;
        }else{
            $this->lastModified = "";
        }
    }

    public function setLink1($link){
        if($link != null){
            $this->link1 = $link;
        }else{
            $this->link1 = "";
        }
    }

    public function setLink1Type($type){
        if($type != null){
            $this->link1Type = $type;
        }else{
            $this->link1Type = "";
        }
    }

    public function setLink2($link){
        if($link != null){
            $this->link2 = $link;
        }else{
            $this->link2 = "";
        }
    }

    public function setLink2Type($type){
        if($type != null){
            $this->link2Type = $type;
        }else{
            $this->link2Type = "";
        }
    }

    public function setLink3($link){
        if($link != null){
            $this->link3 = $link;
        }else{
            $this->link3 = "";
        }
    }

    public function setLink3Type($type){
        if($type != null){
            $this->link3Type = $type;
        }else{
            $this->link3Type = "";
        }
    }

    public function setLink4($link){
        if($link != null){
            $this->link4 = $link;
        }else{
            $this->link4 = "";
        }
    }

    public function setLink4Type($type){
        if($type != null){
            $this->link4Type = $type;
        }else{
            $this->link4Type = "";
        }
    }

    public function setLocation($location){
        if($location != null){
            $this->location = $location;
        }else{
            $this->location = "";
        }
    }

    
    public function setColor($color){
        if($color != null){
            $this->color = $color;
        }else{
            $this->color = "";
        }
    }

    public function getTargetDate(){ return $this->targetDate; }

    public function getName(){ return $this->name_es; }

    public function getNameEU(){ return $this->name_eu; }

    public function getNameEN(){ return $this->name_en; }

    public function getDescription(){ return $this->description_es; }

    public function getDescriptionEU(){ return $this->description_eu; }

    public function getDescriptionEN(){ return $this->description_en; }

    public function getTags(){ return $this->tags; }

    public function getSpeakers(){ return $this->speakers; }

    public function getPicUrl(){ return $this->picUrl; }

    public function getStartDateTime(){ return $this->startDateTime; }

    public function getFinishDateTime(){ return $this->finishDateTime; }

    public function getLastModified(){ return $this->lastModified; }
    
    public function getColor(){ return $this->color; }


}
