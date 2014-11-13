<?php

namespace App\Services;
use App\Models\SponsorsModel as Sponsor;

class SponsorsService extends BaseService
{
    public static $table = 'Sponsors';

    public function getAll(){
        $sponsors = [];

		$sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table." order by orderField");

        foreach ($sqlResult as $key => $value) {
            $sponsor = new Sponsor();
            $sponsor->loadFromSqlRow($value);

            $sponsors[] = $sponsor;
        }
        return $sponsors;
    }
}