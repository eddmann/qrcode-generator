<?php

class QRCode
{

  static $qr_api = 'http://chart.googleapis.com/chart';
  
  static $default_text = '';
  static $default_size = 'M';
  static $default_border = true;
  static $default_sizes = array('S' => '100x100',
                                'M' => '200x200',
                                'L' => '300x300');
  
  /**
  * Create a QRCode based on parsed in options
  *
  * @param string|array     $a  options array or text string
  * @param string|integer   $b  size type as string or width integer
  * @param integer          $c  height integer
  * @return png
  *
  */
  public static function create($a, 
                                $b = null, 
                                $c = null) {
    list($text, $size, $border) = self::_retrieve_params($a, $b, $c);
    
    // create a new stream to access the google qr api
    $ctx = stream_context_create(array(
                                  'http' => array(
                                      'method'  => 'POST',
                                      'content' => 'cht=qr&chl=' . $text . '&chs=' . $size)));
    // open stream and retrieve contents
    $fp = fopen(self::$qr_api, 'rb', false, $ctx);
    // create an image object from returned stream contents
    $orig_qr_code = imagecreatefromstring(stream_get_contents($fp));
    fclose($fp);
    
    // remove white border around qr image if desired
    if (!$border) {
      $border = 0; // reuse border var for border size
      while(imagecolorat($orig_qr_code, $border, $border) == 0xFFFFFF)
        $border++;
      
      // create the new qr image object based on calculated border
      $new_qr_code = imagecreatetruecolor(imagesx($orig_qr_code) - ($border * 2), 
                                          imagesy($orig_qr_code) - ($border * 2));
      imagecopy($new_qr_code, $orig_qr_code, 
                0, 0, $border, $border, 
                imagesx($new_qr_code), imagesy($new_qr_code));
      
      $tmp_qr = imagepng($new_qr_code);
       // destroy in-memory image objects
      imagedestroy($orig_qr_code);
      imagedestroy($new_qr_code);
    
      // return image object in png format
      return $tmp_qr;
    } else {
      // return orginal object in png format
      return imagepng($orig_qr_code);
    }
  }
  
  /**
  * Returns correct text, size and border from parsed in options
  *
  * @param string|array     $a                      options array or text string
  * @param string|integer   $b                      size type as string or width integer
  * @param integer          $c                      height integer
  * @return array           $text, $size, $border   formatted options
  *
  */
  public static function _retrieve_params($a, $b, $c) {
    $text = self::$default_text;
    $size = self::$default_sizes[self::$default_size];
    $border = self::$default_border;
    
    if (is_array($a)) {
      $text = (@$a['text']) ? $a['text'] : '';
      
      // check if width and height set
      if (@is_int($a['width']) && @is_int($a['height']))
        $size = $a['width'] . 'x' . $a['height'];
        
      // check if size set as a default value
      else if (@is_string($a['size']) && array_key_exists(strtoupper($a['size'][0]), self::$default_sizes))
        $size = self::$default_sizes[strtoupper($a['size'][0])];
      
      // check if size set to number
      else if (@is_int($a['size']))
        $size = $a['size'] . 'x' . $a['size'];
      
      // check if border set
      if (@is_bool($a['border']))
        $border = $a['border'];
        
    } else {
      $text = $a;
      
      // check if width and height set
      if (@is_int($b) && @is_int($c))
        $size = $b . 'x' . $c;
      
      // check if size set as a default value
      else if (@is_string($b) && array_key_exists(strtoupper($b[0]), self::$default_sizes))
        $size = self::$default_sizes[strtoupper($b[0])];
      
      // check if size set to number
      else if (@is_int($b))
        $size = $b . 'x' . $b;
    }
    
    // returns an array with text, size and border
    return array($text, $size, $border);
  }
    
}