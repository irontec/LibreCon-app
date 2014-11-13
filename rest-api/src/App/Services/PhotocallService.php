<?php

namespace App\Services;
use App\Models\PhotocallModel as Photo;

class PhotocallService extends BaseService
{
  protected $_path;
  protected $_url;
//photocall_(md5).jpg
//thumbnail_(md5).jpg

  public function __construct($data)
  {
      $this->_path = $data['path'];
      $this->_url = $data['url'];
  }

  public function getPhotos(){
      
    $photos = [];
    $count = 0;

    //Obtener las imagenes de la ruta
    
    
    $items = glob($this->_path.'/photocall{*.jpg}', GLOB_BRACE);
    array_multisort(array_map('filemtime', $items), SORT_NUMERIC, SORT_DESC, $items);
    
    foreach($items as $file){
      //sacamos el basename (photocall_nombreDeArchivo.jpg)
      $basename = basename($file);
      $thumbnailName = $this->generateThumbnailName($basename);

      //instanciar Modelo y establecer propiedades
      $photo = new Photo();
      $photo->setId(md5($title));
      $photo->setTitle($basename);
      $photo->setDate(date('Y-m-d H:i:s',filemtime($file)));
      $photo->setUrl($this->_url . $basename);
      $photo->setThumbnailUrl($this->_url.$thumbnailName);
      $photos[] = $photo;
      $count++;
    }

    return $photos;
  }

    public function generateThumbnailName($basename){
        return preg_replace("/^photocall_(.*)/","thumbnail_$1", $basename);
    }

}
