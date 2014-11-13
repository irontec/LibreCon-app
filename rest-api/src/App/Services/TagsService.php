<?php

namespace App\Services;
use App\Models\TagsModel as Tag;

class TagsService extends BaseService
{
    public static $table = 'Tags';

    public function getAll(){
        $tags = [];

        $sqlResult = $this->db->fetchAll("SELECT * FROM ".self::$table);

        foreach ($sqlResult as $key => $value) {
            $tag = new Tag();
            $tag->loadFromSqlRow($value);
            $tags[] = $tag;
        }
        return $tags;
    }

    public function getTagsFromJoin($tagsJoin){
        $tags = [];

        foreach ($tagsJoin as $key => $value) {

            $tag = self::get($value['idTag']);
            if(null != $tag){
              $tags[] = $tag;
            }
        }
        return $tags;
    }

    public function get($id){
        $sqlResult = $this->db->fetchAssoc('SELECT * FROM '. self::$table . ' WHERE id = ?', [$id]);
        if($sqlResult){
          $tag = new Tag();
          $tag->loadFromSqlRow($sqlResult);
          return $tag;
        }else{
          return null;
        }


    }
}
