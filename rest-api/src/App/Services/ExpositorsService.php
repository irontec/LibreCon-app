<?php

namespace App\Services;
use App\Models\ExpositorsModel as Expositor;

class ExpositorsService extends BaseService
{
    public static $table = 'Expositor';

    public function getAll(){
        $expositors = [];

		$sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table." order by orderField");

        foreach ($sqlResult as $key => $value) {
            $expositor = new Expositor();
            $expositor->loadFromSqlRow($value);

            $expositors[] = $expositor;
        }
        return $expositors;
    }
}