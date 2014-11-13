<?php

namespace App\Services;

class VersionService extends BaseService
{
	public static $table = 'Versions';

	public function checkLastVersion($table, $version){

		if ($version == null || !is_numeric($version)){
            $version = -1;
    }

		$max = $this->getLastVersion($table);

		if($max != null || is_numeric($max)){

			if($max == $version){
				return true;

			}

		}

		return false;
	}

	public function getLastVersion($table){
		$items = [];

		$items = $this->getRelationsVersions($table);

		$max = max($items);
		if($max == null || $max == false || is_numeric($max) == false)
			return 0;

		return (string)$max;
	}

	public function getRelationsVersions($table){

		$items = [];

		switch ($table) {
			case 'Schedule':
				$item = $this->db->fetchAssoc("select version from " . self::$table . " where `table` = '" . $table . "'");
				$items[] = strtotime($item['version']);
				$item = $this->db->fetchAssoc("select version from " . self::$table . " where `table` = 'Speaker'");
				$items[] = strtotime($item['version']);
				break;
			case 'Meeting':
				$item = $this->db->fetchAssoc("select version from " . self::$table . " where `table` = '" . $table . "'");
				$items[] = strtotime($item['version']);
				$item = $this->db->fetchAssoc("select version from " . self::$table . " where `table` = 'Assistants'");
				$items[] = strtotime($item['version']);
				break;
			default:
				$item = $this->db->fetchAssoc("select version from " . self::$table . " where `table` = '" . $table . "'");
				$items[] = strtotime($item['version']);
				break;
		}
		return $items;

	}

	public function update($table){
		$sqlResult = $this->db->fetchAssoc("select version, id from Versions where `table` = '" . $table . "'");
		$version = [ '`version`' => date('Y-m-d H:i:s'),  ];
		if($sqlResult == null || $sqlResult['id'] == null){
			$version['`table`'] = $table;
			$this->db->insert(self::$table, $version);
		}else{
			$this->db->update(self::$table, $version, ['id' => $sqlResult['id']]);
		}
	}

}
