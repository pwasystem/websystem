<?PHP
//Parametros iniciais
ini_set("memory_limit","1024M");
set_time_limit(0);
error_reporting(E_ALL);

//Registro
$dominio='dominio';
$extensao='com';
$webmaster="webmaster@".str_replace('www.','',$_SERVER['HTTP_HOST']);

//Lê dados de conexão
$path=strstr($_SERVER['PHP_SELF'],'admin')?'../':'';
include($path."_gravar/mysql.php");

$host=addslashes(isset($_POST['host'])?$_POST['host']:$host);
$user=addslashes(isset($_POST['user'])?$_POST['user']:$user);
$pass=addslashes(isset($_POST['pass'])?$_POST['pass']:$pass);
$chave=addslashes(isset($_POST['chave'])?$_POST['chave']:$pass);
$db=addslashes(isset($_POST['db'])?$_POST['db']:$db);
$mq=addslashes(isset($_GET['mq'])?$_GET['mq']:0);
$quebra=isset($_SERVER["OS"])?(strstr(strtolower($_SERVER["OS"]),'win')?"\r\n":"\r\n"):"\r\n";

global $mysql;
try {
	$mysql = new PDO("mysql:host=$host;port=3306;dbname=$db",$user,$pass);	
} catch (PDOException $erro) {
	echo $erro->getCode() .' - '. $erro->getMessage();
	switch ($r=$erro->getCode()) {
		case 1045||1049||2002 :
			if ($r==2002) $aviso='Host inválido!';
			if ($r==1045) $aviso='Login inválido!';
			if ($r==1049) $aviso='Base de dados não localizada!';
			@mail("$webmaster" , 'Falha de comunicação' , 'Falha de comunicação com a base de dados: '.date('d/m/Y - H:i')."<BR>$aviso" , "Reply-To:$webmaster{$quebra}MIME-Version: 1.0{$quebra}Content-type: text/plain; charset=iso-8859-1{$quebra}From: $webmaster <$webmaster>{$quebra}");
			die($registrar==0?"<style>p{font:10px verdana}</style><p align=center>Erro ao conectar com a base de dados MySQL</p>":"<title>$dominio</title>
				<!--".md5($dominio)."-->
				<style>td{font:10px verdana};input{font:10px verdana}</style>
				<table border=0 align=center><form method=post action=index.php?registra_mysql=1>
					<tr><td align=center colspan=2><b>Altere os dados para configurar sua conexão com o banco de dados MySQL</b><DIV align=center style=color:red;font-weight:bold>$aviso</DIV></td></tr>
					<tr><td align=right>Host:</td><td><input name=host value='$host'></td></tr>
					<tr><td align=right>Usuário:</td><td><input name=user value='$user'></td></tr>
					<tr><td align=right>Senha:</td><td><input type=password name=pass></td></tr>
					<tr><td align=right>Banco de dados:</td><td><input name=db value='$db'></td></tr>
					<tr><td align=right>Chave de acesso:</td><td><input name=chave></td></tr>
					<tr><td align=center colspan=2><input type=submit value=Registrar></td></tr>
				</form></table>");
			break;
		default :
			$this->fonte=$erro->getMessage();
	}
}
?>