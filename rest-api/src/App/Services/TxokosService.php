<?php

namespace App\Services;
use App\Models\TxokosModel as Txoko;

class TxokosService extends BaseService
{
    public static $table = 'Txoko';

    public function getAll(){
        $txokos = [];

		$sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table." order by orderField");

        foreach ($sqlResult as $key => $value) {
            $txoko = new Txoko();
            $txoko->loadFromSqlRow($value);

            $txokos[] = $txoko;
        }
        return $txokos;
    }
}