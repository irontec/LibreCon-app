<?php

namespace App\Models;

class SpeakersModel extends BaseModel
{
    public $name;
    public $picUrl;
    public $picUrlCircle;
    public $description_es;
    public $description_eu;
    public $description_en;
    public $company;
    private $lastModified;

    public $links;
    public $tags;

    public function __construct(){
        $this->links = [];
        $this->tags = [];
    }

    public function loadFromSqlRow($row){
        $this->setId($row['id']);
        $this->setName($row['name']);
        $this->setPicUrl($this->generateMediaUrl('speaker', $row['id'], $row['pictureFileSize'], 'landscape'));
        $this->setPicUrlCircle($this->generateMediaUrl('speaker', $row['id'], $row['pictureFileSize'], 'circle'));
        $this->setDescription($row['description_es']);
        $this->setDescriptionEU($row['description_eu']);
        $this->setDescriptionEN($row['description_en']);
        $this->setCompany($row['company']);
        $this->setLastModified($row['lastModified']);
        $this->addLink($row['link1'], $row['link1Type']);
        $this->addLink($row['link2'], $row['link2Type']);
        $this->addLink($row['link3'], $row['link3Type']);
        $this->addLink($row['link4'], $row['link4Type']);
    }

    public function addLink($url, $type){
        if($url != null){
            $link = [];
            $link['url'] = (empty($url))? '' : $url;
            $link['type'] = (empty($type))? '' : $type;
            $this->links[] = $link;
        }

    }

    public function setName($name){
        if($name != null){
            $this->name = $name;
        }else{
            $this->name = "";
        }
    }

    public function setPicUrl($picUrl){
        if($picUrl != null){
            $this->picUrl = $picUrl;
        }else{
            $this->picUrl = "";
        }
    }

    public function setPicUrlCircle($iconUrl){
        if($iconUrl != null){
            $this->picUrlCircle = $iconUrl;
        }else{
            $this->picUrlCircle = "";
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

    public function setCompany($company){
        if($company != null){
            $this->company = $company;
        }else{
            $this->company = "";
        }
    }

    public function setLastModified($datetime){
        if($datetime != null){
            $this->lastModified = $datetime;
        }else{
            $this->lastModified = "";
        }
    }

    public function setTags($tags){
        if($tags != null){
            $this->tags = $tags;
        }else{
            $this->tags = [];
        }
    }

    public function getName(){ return $this->name; }

    public function getDescription(){ return $this->description_es; }

    public function getDescriptionEU(){ return $this->description_eu; }

    public function getDescriptionEN(){ return $this->description_en; }

    public function getColor(){ return $this->color; }

    public function getLastModified(){ return $this->lastModified; }

    public function getTags(){ return $this->tags; }

}
