<?php

require_once('qrcode.php');

header('Content-Type: image/png');

$text   = @$_GET['text'];
$size   = @$_GET['size'];
$width  = @$_GET['width'];
$height = @$_GET['height'];
$border = @$_GET['border'];

$params = array();
$params['text'] = $text;
$params['border'] = (bool)$border;
if ($width && $height) {
  $params['width'] = $width;
  $params['height'] = $height;
} else {
  $params['size'] = $size;
}

QRCode::create($params);