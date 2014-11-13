<?php

namespace App\Models;

class PhotocallModel extends BaseModel
{
    public $title;
    public $date;
    public $url;
    public $thumbnailUrl;

    /**
     * Set the value of Title
     *
     * @param mixed title
     *
     * @return self
     */
    public function setTitle($value)
    {
        $value = $this->validate($value);

        $this->title = $value;

        return $this;
    }

    /**
     * Set the value of Date
     *
     * @param mixed date
     *
     * @return self
     */
    public function setDate($value)
    {
        $value = $this->validate($value);

        $this->date = $value;

        return $this;
    }

    /**
     * Set the value of Url
     *
     * @param mixed url
     *
     * @return self
     */
    public function setUrl($value)
    {
        $value = $this->validate($value);

        $this->url = $value;

        return $this;
    }

    /**
     * Set the value of ThumbnailUrl
     *
     * @param mixed url
     *
     * @return self
     */
    public function setThumbnailUrl($value)
    {
        $value = $this->validate($value);

        $this->thumbnailUrl = $value;

        return $this;
    }

    public function validate($value){
      if($value == null || $value == undefined)
        $value = "";
      return $value;
    }

}



?>
