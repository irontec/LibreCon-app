<?php

namespace App\Models;

class AssistantsModel extends BaseModel
{
    public $name;
    public $lastName;
    public $email;
    public $cellPhone;
    public $company;
    public $position;
    public $picUrl;
    public $picUrlCircle;
    public $address;
    public $location;
    public $country;
    public $postalCode;
    public $interests;
    private $uuid;
    private $code;
    private $secretHash;
    private $lastModified;

    public function __construct(){}

    public function loadFromSqlRow($row){
        $this->setId($row['id']);
        $this->setName($row['name']);
        $this->setLastName($row['lastName']);
        $this->setEmail($row['email']);
        $this->setCellPhone($row['cellPhone']);
        $this->setCompany($row['company']);
        $this->setPosition($row['position']);
        $this->setPicUrl($this->generateMediaUrl('assistant', $row['id'], $row['pictureFileSize'], 'landscape'));
        $this->setPicUrlCircle($this->generateMediaUrl('assistant', $row['id'], $row['pictureFileSize'], 'circle'));
        $this->setAddress($row['address']);
        $this->setLocation($row['location']);
        $this->setCountry($row['country']);
        $this->setPostalCode($row['codePostal']);
        $this->setInterests($row['interests']);
        $this->setUuid($row['uuid']);
        $this->setCode($row['code']);
        $this->setSecretHash($row['secretHash']);
        $this->setLastModified($row['lastModified']);
    }

    public function loadFromSqlRowPublic($row, $showEmail = false, $showCellphone = false){
        $this->setId($row['id']);
        $this->setName($row['name']);
        $this->setLastName($row['lastName']);
        $this->setEmail(null);
        if ($showEmail) {
            $this->setEmail($row['email']);
        } 
        
        $this->setCellPhone(null);
        if ($showCellphone) {
            $this->setCellPhone($row['cellPhone']);
        }
        
        $this->setCompany($row['company']);
        $this->setPosition($row['position']);
        $this->setPicUrl($this->generateMediaUrl('assistant', $row['id'], $row['pictureFileSize'], 'landscape'));
        $this->setPicUrlCircle($this->generateMediaUrl('assistant', $row['id'], $row['pictureFileSize'], 'circle'));
        $this->setAddress(null);
        $this->setLocation(null);
        $this->setCountry(null);
        $this->setPostalCode(null);
        $this->setInterests($row['interests']);
        $this->setUuid(null);
        $this->setCode(null);
        $this->setSecretHash(null);
        $this->setLastModified(null);
    }
    
    public function setName($name){
        if($name != null){
            $this->name = $name;
        }else{
            $this->name = "";
        }
    }

    public function setLastName($name){
        if($name != null){
            $this->lastName = $name;
        }else{
            $this->lastName = "";
        }
    }

    public function setEmail($mail){
        if($mail != null){
            $this->email = $mail;
        }else{
            $this->email = "";
        }
    }

    public function setCellPhone($phone){
        if($phone != null){
            $this->cellPhone = $phone;
        }else{
            $this->cellPhone = "";
        }
    }

    public function setCompany($company){
        if($company != null){
            $this->company = $company;
        }else{
            $this->company = "";
        }
    }

    public function setPosition($position){
        if($position != null){
            $this->position = $position;
        }else{
            $this->position = "";
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

    public function setAddress($address){
        if($address != null){
            $this->address = $address;
        }else{
            $this->address = "";
        }
    }

    public function setLocation($location){
        if($location != null){
            $this->location = $location;
        }else{
            $this->location = "";
        }
    }

    public function setCountry($country){
        if($country != null){
            $this->country = $country;
        }else{
            $this->country = "";
        }
    }

    public function setPostalCode($postalCode){
        if($postalCode != null){
            $this->postalCode = $postalCode;
        }else{
            $this->postalCode = "";
        }
    }

    public function setInterests($interests){
        if($interests != null){
            $this->interests = $interests;
        }else{
            $this->interests = "";
        }
    }

    public function setUuid($uuid){
        if($uuid != null){
            $this->uuid = $uuid;
        }else{
            $this->uuid = "";
        }
    }

    public function setCode($code){
        if($code != null){
            $this->code = $code;
        }else{
            $this->code = "";
        }
    }

    public function setSecretHash($hash){
        if($hash != null){
            $this->secretHash = $hash;
        }else{
            $this->secretHash = "";
        }
    }

    public function setLastModified($datetime){
        if($datetime != null){
            $this->lastModified = $datetime;
        }else{
            $this->lastModified = "";
        }
    }


}
