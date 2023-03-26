<?php

class Synology extends Image {


    
  public static function url($album, $image, $size = false){
    // split album name if is in sub dir because slash should't be encoded with rawurlencode
    if(strpos($album, '/')){
      $pathArray = explode('/', $album);
      $path = '';
      foreach ($pathArray as $value) {
        if($path){
          $path = rawurlencode($value);
        } else {
          $path = $path.'/'.rawurlencode($value);
        }
      }
    } else {
      $album = rawurlencode($album);
    }

    

    // split image name if is in sub dir because contains sub dirs
    if(strpos($image, '/')){
      $pathArray = explode('/', $image);
      $image = array_pop($pathArray); // remove last entry from array because, it's the file
      foreach ($pathArray as $value) {
        $album .= '/'.rawurlencode($value);
      }
    }

    $url = IMAGES_URL.'/'.$album.'/@eaDir/';

    if(!$image){
      $image = 'noimage';
    }

    $fullUrl = $url.rawurlencode($image).'/SYNOPHOTO_THUMB_'.($size ? $size : 'SM').'.jpg';

    return $fullUrl;


  }




}
