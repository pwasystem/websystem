<?PHP
include 'dados.php';
if ($l>0){
	$sistema=addslashes(isset($_GET['sistema'])?$_GET['sistema']:(isset($_COOKIE['sistema'])?$_COOKIE['sistema']:''));
	setcookie("sistema",$sistema,0);
	extract($mysql->query("SELECT CONCAT(',',nivel) ts FROM arquivos WHERE id=$id")->fetch(PDO::FETCH_ASSOC));	
	extract($mysql->query("SELECT anexo FROM sistemas WHERE id=$sistema")->fetch(PDO::FETCH_ASSOC));
	$anexo=utf8_encode($anexo);
	$f=function($id,$mysql) use ($anexo){
		return eval($anexo);
	};
	echo strstr($ts,',1,')||strstr($ts,",$n,")||$n==1?$f($id,$mysql):"<script>window.location='index.php'</script>";
} else {
	header("location=index.php");
}
?>