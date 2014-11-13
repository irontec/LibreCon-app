<?php
class Librecon_Helper_Cachepath extends Zend_Controller_Action_Helper_Abstract
{
  public function resizedImageCachePath()
  {
    $path = APPLICATION_PATH . '/cache/images/';
    $this->_checkPath($path);
    return $path;
  }

  /**
   * Comprueba que el directorio exista y lo crea de no ser así
   * @param string $path
   * @return boolean true if everything was fine
   * @throws Exception
   */
   private function _checkPath ($path)
   {
     if (file_exists($path) && is_dir($path)) {
       return true;
     }

     if (file_exists($path)) {
       throw new Exception($path . "is not a directory");
     }

     if(!mkdir($path, 0777, true)) {
       throw new Exception("Could not create directory : " . $path);
     }

     return true;
   }
}
