<?PHP
include 'dados.php';

error_reporting(E_ALL);

//Variáveis
$ip=$_SERVER['REMOTE_ADDR'];
$l>0?header("location:estrutura.php?w="):null;
$brutal=isset($_COOKIE['brutal'])?$_COOKIE['brutal']:0;
$usuario=isset($_POST['usuario']) ? addslashes(htmlentities(strip_tags($_POST['usuario']))):'';
$senha=isset($_POST['senha']) ?  addslashes(htmlentities(strip_tags($_POST['senha']))):'';
$reverso=isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:'Desabilitado';

//Login
if (@extract($mysql->query("SELECT id usuarioId,nivel usuarioNivel FROM usuarios WHERE usuario='$usuario' AND senha='".md5($senha)."' AND habilitado=1")->fetch(PDO::FETCH_ASSOC))){
	if ($brutal<10){
		$mysql->exec("DELETE FROM log WHERE TO_DAYS(NOW())-TO_DAYS(data)>30");
		$mysql->exec("INSERT INTO log VALUES(NOW(),'$ip','$usuario','logou!','$reverso')");
		extract($mysql->query("SELECT MD5(data) token FROM log WHERE ip='$ip' AND usuario='$usuario' AND senha='logou!' ORDER BY data DESC")->fetch(PDO::FETCH_ASSOC));
		setcookie ('l',$usuarioId,0);
		setcookie ('n',$usuarioNivel,0);
		setcookie ('t',$token,0);
		die("<SCRIPT>window.location='estrutura.php?w='</SCRIPT>");
	}
} elseif (!empty($usuario)) {
	setcookie('brutal',$brutal+1,time()+600);
	if ($brutal>10) @mail($webmaster , 'Brutal Force Attack' , "An attempted attack 'brutal force' was reported, the data are recorded in the log." , "Reply-To:$webmaster{$quebra}MIME-Version: 1.0{$quebra}Content-type: text/plain; charset=utf-8{$quebra}From: $webmaster <$webmaster>{$quebra}");
	$mysql->exec("INSERT INTO log VALUES(NOW(),'$ip','$usuario','$senha','$reverso')");
}

//Conteudo
echo "$inicio_pagina<form method=post action=index.php><table border=0 cellpadding=0 align=center width=100%>
	<tr>
		<td colspan=2 height=15>&nbsp;</td>
	</tr>".($brutal<10?"<tr>
		<td align=right width=50%><b>User:&nbsp;</b></td>
		<td width=50%> <input type=text name=usuario class=text autocomplete=off></td>
	</tr><tr>
		<td align=right><b>Password:&nbsp;</b></td>
		<td> <input type=password name=senha class=text autocomplete=off></td>
	</tr><tr>
		<td colspan=2 height=15>&nbsp;</td>
	</tr><tr>
		<td colspan=2 align=center class=cor4><input type=submit value=Entrar class=button></td>
	</tr>":'<tr><td colspan=2 align=center><b>Your login has been blocked for 10 minutes to prevent intrusion attempts</b></td></tr>')."</table></form>$final_pagina";
?>