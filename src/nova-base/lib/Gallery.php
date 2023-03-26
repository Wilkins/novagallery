<?php
/**
 * Gallery - List Images and Albums
 * @author novafacile OÜ
 * @copyright Copyright (c) 2021 by novafacile OÜ
 * @license AGPL-3.0
 * @version 1.1.1
 * @link https://novagallery.org
 * to disable cache just set maxCacheAge to 'false' on initialization
 **/

class Gallery {
  
  protected $dir = '';
  protected $images = [];
  protected $albums = [];
  protected $processed = false;
  protected $recursive = false;

  public function __construct($dir, $recursive = false)
  {
    $this->dir = $dir;
    $this->recursive = $recursive;
    $this->listAlbums();
    $this->processImages();
      //if ($this->recursive) {
        //  $this->processAlbums();
      //}
    //$this->process();

  }

/*
  public function process(): void
  {
      $this->removeEmptyAlbums2();
  }
*/

  protected function listAlbums(): void
  {
    $dirs = glob($this->dir.'/'."*", GLOB_ONLYDIR);
    //echo "glob($this->dir/*)<br>\n";
    $this->albums = $this->fileList($dirs);
    unset($this->albums["@eaDir"]);
   //   print_R($this->albums);
  }
/*
  protected function processAlbums(): void
  {
      foreach ($this->albums as $album => $image) {
          $this->albums[$album] = new Gallery($this->dir.'/'.$album, true);
      }
      $this->processed = true;
  }
*/
  protected function processImages(): void
  {
      $images = glob($this->dir.'/*{jpg,jpeg,JPG,JPEG,png,PNG}', GLOB_BRACE );
      //echo "glob($this->dir/*{jpg,jpeg,JPG,JPEG,png,PNG})<br>\n";
      $this->images = $this->fileList($images, false);
  }


  // create array of files or dirs without path & with last modification date
  protected function fileList($list, $withCaptureDate = false): array
  {
    $fileList = [];
    foreach ($list as $element) {
      if($withCaptureDate){ // add modification date if requested
        $value = $this->getImageCaptureDate($element);
      } else {
        $value = []; // else add as array for sub files
      }
      $element = strrchr($element, '/');
      $element = substr($element, 1);
      $fileList[$element] = $value;
    }
    return $fileList;
  }

  protected function getImageCaptureDate($file)
  {
    if(!file_exists($file)) { return false;  }

    if(preg_match('/\.(JPEG|jpeg|JPG|jpg|png|PNG)$/', $file) === 0){
      return filemtime($file); // use filetime, if no image
    }

    // Get the photo's EXIF tags
    try {
      @$exif_data = exif_read_data($file);
      if($exif_data === false) {
        return filemtime($file); // use filemtime, if no exif data
      }
    } catch (Exception $e) {
      return filemtime($file); // use filemtime, if exif data error
    }
    

    // default value, which represents no date
    $date = false;
    // Array of EXIF date tags to check
    $date_tags = [
      'DateTimeOriginal',
      'DateTimeDigitized',
      'DateTime',
      //'FileDateTime'
    ];

    // Check for the EXIF date tags, in the order specified above. First value wins.
    foreach($date_tags as $date_tag){
      if(isset($exif_data[$date_tag])){
        $date = $exif_data[$date_tag];
        $date = $this->timestampFromExif($date);
        break;
      }
    }

    // If no date tags were found use filemtime
    if(!$date) { return filemtime($file);}

    //If the date that was extracted is a string, convert it to an integer
    if( is_string($date) ) $date = strtotime($date);

    return $date;
  }

  protected function timestampFromExif($string): string
  {
    if (!(preg_match('/\d\d\d\d:\d\d:\d\d \d\d:\d\d:\d\d/', $string))) {
      return $string; // wrong date
    }

    $iTimestamp = mktime(
            substr( $string, 11, 2 ), 
            substr( $string, 14, 2 ), 
            substr( $string, 17, 2 ), 
            substr( $string, 5, 2 ), 
            substr( $string, 8, 2 ), 
            substr( $string, 0, 4 ));
    return $iTimestamp;
  }

  protected function shuffle_assoc($array): array
  {
        $keys = array_keys($array);
        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        return $new;
  }
  
  protected function order($list, $order): array
  {
    switch ($order) {
      case 'oldest':
        asort($list);
        break;
      case 'newest':
        arsort($list);
        break;
      case 'random':
        $list = $this->shuffle_assoc($list);
        break;
      default:
        // order by name
        $list = $this->orderByName($list);
        break;
    }
    return $list;
  }

  // sort array by natcasesort with german umlaute
  // solution based on http://www.marcokrings.de/arrays-sortieren-mit-umlauten/
  protected function orderByName($list){
    // swap key (name) value (timestamp) for order operations
    $nameList = [];
    //print_R($list);
    foreach ($list as $album => $value) {
      array_push($nameList, $album);
    }

    // sort based on http://www.marcokrings.de/arrays-sortieren-mit-umlauten/
    $aOriginal = $nameList;
    if (count($aOriginal) == 0) { return $aOriginal; }
    $aModified = [];
    $aReturn   = [];
    $aSearch   = array("Ä","ä","Ö","ö","Ü","ü","ß","-");
    $aReplace  = array("A","a","O","o","U","u","ss"," ");
    foreach($aOriginal as $key => $val) {
      $aModified[$key] = str_replace($aSearch, $aReplace, $val);
    }
    natcasesort($aModified);
    foreach($aModified as $key => $val) {
      $aReturn[$key] = $aOriginal[$key];
    }

    // swap back to have a orderd list with the correct key (album) value (timestamp) format
    $orderedList = [];
    foreach ($aReturn as $value) {
      $orderedList[$value] = $list[$value];

    }

    return $orderedList;
  }

  public function getFirstImage()
  {
      //echo "getFirstImage()<br>\n";
      //print_R($this->images);
      if (count($this->images)) {
          //echo "Image trouvée<br>\n";
          //echo array_key_first($this->images);
            return array_key_first($this->images);
      }
      if (count($this->albums)) {
          foreach ($this->albums as $album => $images) {
              //echo "DIR :".$this->dir."/".$album."<br>\n";
              if (! $this->albums[$album]) {
                  $this->albums[$album] = new Gallery($this->dir . '/' . $album);
              }
              $firstPhoto = $this->albums[$album]->getFirstImage();
              if ($firstPhoto) {
                  //echo "album trouvé<br>\n";
                  //echo "$firstPhoto<br>\n";
                  return $album.'/'.$firstPhoto;
              } else {
                  //echo "<br>remove $this->dir/$album<br> \n";
                  unset($this->albums[$album]);

              }
          }
          //print_R($this->albums);
      }
      //$this->
      //unset ($this->albums)
      //echo "<br>remove $this->dir<br> \n";
      return false;

  }


  protected function removeEmptyAlbums2(){
     // echo "removeEmptyAlbums2<br>\n";
    foreach ($this->albums as $album => $modDate) {
      if(!$this->hasImages($album)){
        // return false; // free version
        $subAlbum = new Gallery($this->dir.'/'.$album, true); // only for version with sub albums
          //echo "REMOVE EMPTY ALBUM<br>\n";
          $firstImage = $subAlbum->getFirstImage();
          //echo "album $album<br>\n";
          //echo "firstImage : $firstImage<br>\n";
        if(!$firstImage){ // only for version with sub albums
          unset($this->albums[$album]); // only for version with sub albums
        }
      }
    }
    //return $this->albums;
  }

  public function getAlbums($order = 'default'){

      //print_r($this->albums);
    // order images in albums
    $orderedImages = [];
    foreach ($this->albums as $album => $images) {
      $orderedImages[$album] = $this->order($images, $order);
    }

    // order albums based on first image
    $orderedAlbums = [];
      // create array with albums and timestamp of first image
    foreach ($orderedImages as $album => $images) {
      if(!empty($images)){
        $orderedAlbums[$album] = array_values($images)[0];  
      } else {
        $orderedAlbums[$album] = '';
      }
    }
    $orderedAlbums = $this->order($orderedAlbums, $order);
      // create array with all albums and all images orderd
    $albums = [];
    foreach ($orderedAlbums as $album => $value) {
      $albums[$album] = $orderedImages[$album];
    }

    return $albums;
  }

  public function images($order = 'default'){
    return $this->order($this->images, $order);
  }



  public function coverImage($album, $order = 'default'){
    if($this->hasImages($album)){
      $images = $this->order($this->albums["$album"], $order);
      reset($images);
      return key($images);
    }

    $subGallery = new Gallery($this->dir.'/'.$album);
    
    $coverImage2 = $subGallery->getFirstImage();
   // if (!$coverImage2) {
        //unset($this->albums[$album]);
    //}
    return $coverImage2;

  }

  public function hasAlbums(){
    return !empty($this->albums);
  }

  public function hasImages($album = false){
    // choose correct image array
    if($album){
      $imageList = &$this->albums[$album];
    } else {
      $imageList = &$this->images;
    }

    return !empty($imageList);
  }
  
  public function parentAlbum($album){
    $parent = strrpos($album, '/');
    return substr($album, 0, $parent);
  }

}
