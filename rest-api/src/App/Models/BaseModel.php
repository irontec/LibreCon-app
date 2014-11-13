<?php

namespace App\Models;

class BaseModel
{
	public $id;

	public function __construct(){}

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function generateMediaUrl($entity, $id, $filesize, $section = false){

		if($filesize > 0){

			$mediaUrl = "";
			if(getenv('APPLICATION_ENV') == 'development'){
				$mediaHost = 'http:/url/to/klear';
			}else{
				$mediaHost = 'https:/url/to/prod/klear';
			}

			if(!$section){
				$mediaUrl = $mediaHost.'media/image/entity/'.$entity.'/id/'.$id.'/';
			}else{
				$mediaUrl = $mediaHost.'media/image/entity/'.$entity.'/id/'.$id.'/section/'.$section.'/';
			}
			return $mediaUrl;
		}else{
			return "";
		}



	}
}
