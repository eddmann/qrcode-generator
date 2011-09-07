<?php

require_once('qrcode.php');

header('Content-Type: image/png');
header('Pragma: public'); 
header('Expires: 0'); 
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

// creates an QR code image based on http referer link
$ref = ($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'http://eddmann.com';
echo QRCode::create($ref, 'M');