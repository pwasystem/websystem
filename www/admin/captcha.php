<?PHP
$t=rand(100,999);
$act=isset($_GET['act'])?$_GET['act']:0;
if ($act==1) {
	setcookie('captcha',$t,time()+3600000);
	die("document.cookie='captcha=$t';
	captcha=$t;");
}if ($act==2) {
	header("Content-type: image/png");
	$im=imagecreate(30,15);
	$f=imagecolorallocate($im,rand(200,255),rand(200,255),rand(200,255));
	$c=imagecolorallocate($im,rand(0,150),rand(0,150),rand(0,150));
	imagestring($im,10,2,0,$_COOKIE['captcha'],$c);
	imagepng($im);
	imagedestroy($im);
} else {	
	$captcha=isset($_COOKIE['captcha'])?$_COOKIE['captcha']:'';
}
?>