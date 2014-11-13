<?php

namespace App\Services;
use App\Models\AssistantsModel as Assistant;
use App\Models\AuthModel as AuthUser;

class AuthService extends BaseService
{
    public static $table = 'Assistants';

    public function authenticate($value, $type = 'code'){

    	  $sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE ' . $type . ' LIKE BINARY ?', [$value]);

      	if($sqlResult){
          $authUser = new AuthUser();
          $authUser->loadFromSqlRow($sqlResult);
          $authUser->setHash($sqlResult['secretHash']);
          return $authUser;
      	}else{
          return false;
      	}

    }

    public function getIdentity($request){
        $this->updateHeaders($request);

        $hash = $request->headers->get('Authorization');
        $sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE secretHash LIKE BINARY ?', [$hash]);


        return  array(
            'hash' => $hash,
            'userId' => $sqlResult['id']
        );
    }

    public function check($request){

      $this->updateHeaders($request);

      $hash = $request->headers->get('Authorization');
      if($hash == null){
        return false;
      }
      $sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE secretHash LIKE BINARY ?', [$hash]);

      if($sqlResult){
      	return $hash;
      }else{
      	return false;
      }
    }

    public function checkFromValue($hash){
      if($hash == null){
        return false;
      }
      $sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE secretHash LIKE BINARY ?', [$hash]);
      if($sqlResult){
        return true;
      }
      return false;
    }

    public function updateHeaders($request){
        if (!$request->headers->has('Authorization') && function_exists('apache_request_headers')) {
            $all = apache_request_headers();
            if (isset($all['Authorization'])) {
                $request->headers->set('Authorization', $all['Authorization']);
            }
        }
    }
}
