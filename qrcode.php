<?php

class QRCode
{

  static $qr_api = 'http://chart.googleapis.com/chart';
  static $default_sizes = array('S' => '100x100',
                                'M' => '200x200',
                                'L' => '300x300');
  
  public static function create($text, $size = 'M', $height = null) {
    if (is_int($size)) {
      // definded width chosen, set height
      $chosen_size = (is_int($height)) ? $size . 'x' . $height :
                                         $size . 'x' . $size;
    } else {
      // set based on entered default size
      $chosen_size = (array_key_exists(strtoupper($size[0]), self::$default_sizes)) ?
                        self::$default_sizes[strtoupper($size[0])] :
                        self::$default_sizes['M'];
    }
    
    // create a new stream to access the google qr api
    $ctx = stream_context_create(array(
                                  'http' => array(
                                      'method'  => 'POST',
                                      'content' => 'cht=qr&chl=' . $text . '&chs=' . $chosen_size)));
    // open stream and retrieve contents
    $fp = fopen(self::$qr_api, 'rb', false, $ctx);
    // create an image object from returned stream contents
    $orig_qr_code = imagecreatefromstring(stream_get_contents($fp));
    fclose($fp);
    
    // remove white border around qr image
    // handle white padding in css if desired
    $border = 0;
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
  }
  
}

