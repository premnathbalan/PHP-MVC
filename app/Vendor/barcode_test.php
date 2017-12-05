<?php

require_once ("tcpdf_barcodes_2d.php");

$code = "<table><tr><td>palani1</td><td>Tamil</td></table>";
$type = "PDF417";

$barcodeobj = new TCPDF2DBarcode($code, $type);
//print_r($barcodeobj);
$barcodeobj->getBarcodePNG();



//copy('http://localhost/tcpdf_min/barcode_test.php', 'paalni.jpg');


//echo file_get_contents('http://localhost/tcpdf_min/barcode_test.php'));

/*$input = 'http://localhost/tcpdf_min/barcode_test.php';
$output = 'aaaaaaaaa.png';
file_put_contents($output, file_get_contents($input));*/

/*$image = imagecreatefromjpeg("http://images.websnapr.com/?size=size&key=Y64Q44QLt12u&url=http://google.com");
imagecopy($image, $image, 0, 140, 0, 0, imagesx($image), imagesy($image));
imagejpeg($image, "folder/file.jpg");
*/
?>


