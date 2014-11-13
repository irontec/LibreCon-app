<?php

class MediaController extends Zend_Controller_Action
{
    public $header;

    protected $_cache;
    protected $_defaultHeaders = array();
    protected $_fileObject = null;
    protected $_uniqIden = null;

    public function init()
    {
      $this->_helper->viewRenderer->setNoRender(true);

      $this->_cache = new \Librecon_Helper_Cachepath();

      $this->_defaultHeaders = array(
        'Pragma' => 'public',
        'Cache-control' => 'maxage=' . 10,
        'Expires' => gmdate('D, d M Y H:i:s', (time() + 10)) . 'GMT'
      );
    }

    protected function getData(){

      $data = array();
      $data['id'] = $this->getParam('id');
      $data['field'] = 'picture';

      switch ($this->getParam('entity')) {
        case 'assistant':
          $data['mapper'] = 'Librecon\Mapper\Sql\Assistants';
          $data['config'] = $this->getConfig($this->getParam('entity'), $this->getParam('section'));
          break;
        case 'expositor':
          $data['mapper'] = 'Librecon\Mapper\Sql\Expositor';
          $data['config'] = $this->getConfig($this->getParam('entity'));
          $data['field'] = 'logo';
          break;
        case 'schedule':
          $data['mapper'] = 'Librecon\Mapper\Sql\Schedule';
          $data['config'] = $this->getConfig($this->getParam('entity'), $this->getParam('section'));
          //if($this->getParam('section') == 'square'){
            $data['field']= 'icon';
          //}
          break;
        case 'speaker':
          $data['mapper'] = 'Librecon\Mapper\Sql\Speaker';
          $data['config'] = $this->getConfig($this->getParam('entity'), $this->getParam('section'));
          break;
        case 'sponsor':
          $data['mapper'] = 'Librecon\Mapper\Sql\Sponsors';
          $data['config'] = $this->getConfig($this->getParam('entity'));
          $data['field'] = 'logo';
          break;
        case 'txoko':
          $data['mapper'] = 'Librecon\Mapper\Sql\Txoko';
          $data['config'] = $this->getConfig($this->getParam('entity'));
          break;
      }
      return $data;
    }

    protected function getConfig($section, $subsection = false)
    {
        $dim = array('w'=>0,'h'=>0);
        $imageConfig = \Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getOption('image');
        if(!$subsection){
          $dim['w'] = $imageConfig[$section]['width'];
          $dim['h'] = $imageConfig[$section]['height'];
          if (isset($imageConfig[$section]['circle'])) {
              $dim['circle'] =(bool)$imageConfig[$section]['circle'];
          }
        }else{
          $dim['w'] = $imageConfig[$section][$subsection]['width'];
          $dim['h'] = $imageConfig[$section][$subsection]['height'];
          if (isset($imageConfig[$section][$subsection]['circle'])) {
              $dim['circle'] =(bool)$imageConfig[$section][$subsection]['circle'];
          }
        }


        return $dim;
    }

    public function imageAction()
    {
      if(!$this->hasParam('entity') || !$this->hasParam('id')) {
        return false;
      }

      $data = $this->getData();
      $mapper = new $data['mapper']();

      if(!$this->_fileObject = $mapper->findOneByField('id', $data['id'])) {
        return false;
      }

      $fileObj = $this->_fileObject->getFileObjects();
      $specs = $this->_fileObject->{'get' . ucfirst($data['field']) . 'Specs'}();

      $fileData = array(
        'size' => $this->_fileObject->{'get' . $specs['sizeName']}(),
        'mime' => $this->_fileObject->{'get' . $specs['mimeName']}(),
        'baseName' => $this->_fileObject->{'get' . $specs['baseNameName']}()
      );

      if(!$fileData['size'] || $fileData['size']<=0){
        return false;
      }

      $this->_uniqIden = md5(
        get_class($mapper)
        . $data['config']['w']
        . $data['config']['h']
        . $data['field']
        . $fileData['baseName']
        . $this->_fileObject->getPrimaryKey() . 'GENERADA'
      );

      $response = $this->getResponse();

      if($this->_hashMatches($this->_uniqIden)) {
        $this->_sendHeaders();
        $response->setHttpResponseCode(304);
        return;
      }
      $this->getPicture($this->_fileObject->{'fetch' . ucfirst($data['field'])}(), $data['config']);
    }

    protected function _sendHeaders($headers = array())
    {
      $response = $this->getResponse();

      foreach ($this->_defaultHeaders as $key => $value) {
        if(!isset($headers[$key])){
          $response->setHeader($key, $value, true);
        }
      }

      foreach ($headers as $key => $value) {
        $response->setHeader($key, $value, true);
      }
      $response->sendHeaders();
    }

    protected function _hashMatches($hash)
    {
      $matchHash = $this->getRequest()->getHeader('If-None-Match');
      if($matchHash == $hash) {
        return true;
      }
      return false;
    }

    public function getPicture(\Iron_Model_Fso $data, $config)
    {
      $disposition = 'inline';
      if (strpos($data->getMimeType(), 'image/') === false){
        $disposition = 'attachment';
      }
      $options = array(
        'filename' => $data->getBaseName(),
        'Content-type' => $data->getMimeType(),
        'Content-Disposition' => $disposition,
        'ETag' => $this->_uniqIden
      );

      foreach ($this->_defaultHeaders as $key => $value) {
        $options[$key] = $value;
      }

      if ($data->getBinary() != "") {
        if (!is_null($config['w']) && !is_null($config['h'])){
          $binary = $this->_resizeImage($data->getFilePath(), $config);
          $this->_helper->sendFileToClient($binary, $options, true);
          return;
        }
        $this->_helper->sendFileToClient($data->getFilePath(), $options, true);
        return;
      }

      return false;
    }
    
    public function importAction()
    {
        $url = base64_decode($this->getParam("u"));
        $id = $this->getParam("i");
        $hash = $this->getParam("h");
        $ret = "ko";
        if ($hash == md5($this->getParam("u") . $id . "avestruz")) {
            $aMapper = new Librecon\Mapper\Sql\Assistants;
            $assistant = $aMapper->find($id);
$ret = "ko2";
            if ($assistant) {
$ret = "ko3";
                $tempFile = tempnam (sys_get_temp_dir(), "IMAGEIMPORTER");
$ret = $url ." >> " . $tempFile;
                file_put_contents($tempFile, file_get_contents($url));
                if (@getimagesize($tempFile)) {
                    $assistant->putPicture($tempFile, basename($url));
                    $assistant->save();
                    $ret = "ok";
                }
         //       @unlink($tempFile);
            }
        }
        file_put_contents("/tmp/imageDownloader", $id . " > " . $ret . "\n", FILE_APPEND);
        
        die($ret);
        
        exit;
        
        
    }

    protected function _resizeImage($imagePath, $config){
      $resizedImageCacheKey = md5_file($imagePath) . intval($config['w']) . intval($config['h']);

      if(($rawImagePath = $this->_retrieveResizedImageCache($resizedImageCacheKey)) === false) {
        $imagick = new Imagick();
        $imagick->readimage($imagePath);
        
        \Iron_Utils_PngFix::process($imagick);
        
        $imagick->cropThumbnailImage($config['w'], $config['h']);
        
        if( isset($config['circle']) && (bool)$config['circle']) {
          $mask = $this->_getMaskCircle($config['w'], $config['h']);
          $imagick->setimageformat('png');
          $imagick->compositeimage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);
        }
        $rawImageContent = $imagick->getimageblob();
        $this->_storeResizedImageFromCache($resizedImageCacheKey, $rawImageContent);

        return $rawImageContent;
      }

      return file_get_contents($rawImagePath);
    }

    protected function _getMaskCircle($w, $h)
    {

        $maskCircle = new Imagick();
        $maskCircle->newImage($w, $h, 'none');
        $maskCircle->setimageformat('png');
        $maskCircle->setimagematte(true);
        $draw = new ImagickDraw();
        $draw->setfillcolor('#ffffff');
        $draw->circle($w/2, $w/2, $h/2, $h);
        $maskCircle->drawimage($draw);

        return $maskCircle;

    }

    protected function _retrieveResizedImageCache($resizedImageCacheKey){
      $mapper = new \Librecon_Helper_Cachepath();
      $imageCacheHandler = $this->_getCacheHandler($this->_cache->resizedImageCachePath());
      return $imageCacheHandler->getBackend()->getCacheFilePath($resizedImageCacheKey);
    }

    protected function _storeResizedImageFromCache($resizedImageCacheKey, $binary)
    {
      $imageCacheHandler = $this->_getCacheHandler($this->_cache->resizedImageCachePath());
      $imageCacheHandler->save($binary, $resizedImageCacheKey);
    }

    protected function _getCacheHandler($cacheDir)
    {
      return Zend_Cache::factory(
        'Core',
        new Iron_Cache_Backend_File(
          array(
            'cache_dir' => $cacheDir
          )
        ),
        array(
          'lifetime' => 86400,
          'automatic_cleaning_factor' => 0,
          'automatic_serialization' => false,
          'write_control' => false
        )
      );
    }
}
