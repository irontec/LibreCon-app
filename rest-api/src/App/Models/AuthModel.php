<?php

namespace App\Models;

class AuthModel extends AssistantsModel
{
	public $hash;

	public function setHash($hash){
		if($hash != null){
			$this->hash = $hash;
		}else{
			$this->hash = "";
		}
	}
}
