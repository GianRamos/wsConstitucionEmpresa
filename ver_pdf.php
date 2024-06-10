<?php

$pathPdf = $_GET['pathPdf'];
//file_get_contents is standard function
$content = file_get_contents($pathPdf);

$ext = pathinfo($pathPdf, PATHINFO_EXTENSION);
$ext=strtolower($ext);
switch ($ext) {
	case 'jpg':
		header("Content-type: image/jpeg");
		break;
	case 'jpeg':
		header("Content-type: image/jpeg");
		break;
	case 'png':
		header("Content-type: image/png");
		break;
	case 'mp4':
		header("Content-Type: video/mp4");
		break;

	case 'quicktime':
		header("Content-Type: video/quicktime");
		break;
    case 'docx':
		header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
		break;
	default:
	header('Content-Type: application/pdf');
		break;
}

//video/quicktime,video/mp4,image/png,image/jpg,image/jpeg

header('Content-Length: '.strlen( $content ));
header('Content-disposition: inline; filename="' . $pathPdf . '"');
header('Cache-Control: public, must-revalidate, max-age=0');
header('Pragma: public');
echo $content;

