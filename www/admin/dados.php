<?PHP
require_once("funcoes.php");
require("conexao.php");

//Lê dados
extract($mysql->query("SELECT * FROM dados")->fetch(PDO::FETCH_ASSOC)) or novo();
//Reportar erros
error_reporting($erro==0?0:E_ALL);

//Variaveis
$m='';																											//Retorno
$k='cor2';																										//Cores
$i=(int)addslashes(isset($_GET['i'])?$_GET['i']:0);																//Inicio
$z=(int)addslashes(isset($_GET['z']) ? $_GET['z']:0);															//Ação
$l=isset($_COOKIE['l']) ? $_COOKIE['l']:0;																		//Condição do Login
$n=isset($_COOKIE['n']) ? $_COOKIE['n']:0;																		//Nível do administrador
$t=isset($_COOKIE['t']) ? $_COOKIE['t']:0;																		//Hash do login
$o=(int)addslashes(isset($_GET['o'])?$_GET['o']:(isset($_COOKIE['o'])?$_COOKIE['o']:1)); 						//idioma
$id=(int)addslashes(isset($_GET['id'])?$_GET['id']:(isset($_COOKIE['id'])?$_COOKIE['id']:0)); 					//id
$pai=(int)addslashes(isset($_GET['pai'])?$_GET['pai']:(isset($_COOKIE['pai'])?$_COOKIE['pai']:0)); 				//pai
$e=(int)addslashes(isset($_GET['e'])?$_GET['e']:(isset($_COOKIE['e'])?$_COOKIE['e']:50)); 						//exibições
$w=preg_replace('/[^A-z]/','',addslashes(isset($_GET['w'])?$_GET['w']:(isset($_COOKIE['w'])?$_COOKIE['w']:0))); //busca
$o=$o==0?1:$o;
$e=$e==0?50:$e;
setcookie("w",$w,0);
setcookie("o",$o,0);
setcookie("e",$e,0);
setcookie("id",$id,0);
setcookie("pai",$pai,0);

//Segurança dois logins
@extract($mysql->query("SELECT md5(a.data) dia,b.nome logado FROM log a INNER JOIN usuarios b ON b.usuario=a.usuario WHERE b.id=$l AND a.senha='logou!' ORDER BY a.data DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC));
if (@$dia!=$t&&$login==0) {
	@mail($webmaster , 'Cookie force attack' , "Was reported an attempted attack simulation cookie, origin $_SERVER[REMOTE_ADDR] or use the user login: $logado by two persons simultaneously." , "Reply-To:$webmaster{$quebra}MIME-Version: 1.0{$quebra}Content-type: text/plain; charset=iso-8859-1{$quebra}From: $webmaster <$webmaster>{$quebra}");
	die("Your login may be being used in two different browsers or computers. <BR> Close all windows browsers before trying again.");
}

//Carrega as permissões
$per=$mysql->query("SELECT restrito,pagina_c, pagina_r, pagina_a, estrutura, menu, habilitado, privilegio, redirecionamento, chave, conteudo, sistema_e, sistema_a, usuarios_e, usuarios_n, ordem, idioma, master, ftp, estatistica, enquete, banner,sistemas,menu_e,log,raiz,xql,nivel,edita,sistema_n,sistema_d,ajax,pagina_p,pagina_e,xmaix FROM nivel WHERE id=$n")->fetch(PDO::FETCH_NUM);

$per[11]==1? die('<script>window.location="../"</script>'):'';
//Cria menu
$meni=$l>0 ? " <a href=estrutura.php?w=><img src=i/med.gif border=0 align=absmiddle title='Administer structure and files'></a>":'';
$per[21]==1 ? $meni.=" <a href=banner.php?w=><img src=i/ban.gif border=0 align=absmiddle title='Manage banner'></a>" :null;
$per[20]==1 ? $meni.=" <a href=enquete.php?w=><img src=i/enq.gif border=0 align=absmiddle title='Manage poll'></a>" :null;
$per[14]==1 ? $meni.=" <a href=usuarios.php?w=><img src=i/usu.gif border=0 align=absmiddle title='Manage user'></a>" :null;
$per[27]==1 ? $meni.=" <a href=nivel.php?w=><img src=i/cae.gif border=0 align=absmiddle title='Manage user level'></a>" :null;
$per[19]==1 ? $meni.=" <a href=estatisticas.php?w=><img src=i/est.gif border=0 align=absmiddle title='View visitor statistics'></a>" :null;
$per[22]==1&&$per[12]==1 ? $meni.=" <a href=sistemas.php?w=><img src=i/alt.gif border=0 align=absmiddle title='Manage attachments systems'></a>" :null;
$per[5]==1&&$per[23]==1 ? $meni.=" <a href=menu.php?w=><img src=i/men.gif border=0 align=absmiddle title='Manage menu'></a>" :null;
$per[26]==1 ? $meni.=" <a href='sql.php?db=0'><img src=i/tab.gif border=0 align=absmiddle title='Manage MySQL'></a>" :null;
$per[18]==1 ? $meni.=" <a href=ftp.php?w=><img src=i/ftp.gif border=0 align=absmiddle title='Virtual FTP'></a>" :null;
$per[24]==1 ? $meni.=" <a href=log.php?w=><img src=i/log.gif border=0 align=absmiddle title='View user log'></a>" :null;
$per[34]==1 ? $meni.=" <a href=mail.php?w=><img src=i/mai.gif border=0 align=absmiddle title='Sending authenticated email options and message log'></a>" :null;
$per[17]==1 ? $meni.=" <a href=master.php?w=><img src=i/prod.gif border=0 align=absmiddle title='Manage system settings and generate backup'></a>" :null;

//Layout administrativo
$inicio_pagina="
<HTML>
	<HEAD>
		<TITLE>$titulo - Administrator</TITLE>
		<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<LINK rel='shortcut icon' href=../_gravar/ico.ico>
		<LINK rel=stylesheet href=../_gravar/css.css charset=UTF-8>
		<SCRIPT src=../_gravar/js.js charset=UTF-8></SCRIPT>
	</HEAD>
	<BODY class='cor1' onload=classe() topmargin=0 leftmargin=0>
		<DIV class=cor1 style='position:absolute;top:0;left:0;display:none;width:1000%;height:1000%;text-align:right;z-index:9000;-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=50);filter:alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5' id=jax><IMG src=i/save.gif></DIV>
		<!--aviso - AVISO -->
		<TABLE border=0 class='camada cor3' style=z-index:9001 cellspacing=0 id=aviso width=300>
			<TR id=barra name=barra>
				<TD><B>Notice</B></TD>
				<TD align=right><IMG src=i/clo.gif style=cursor:hand onclick=fechaviso()></TD>
			</TR><TR>
				<TD colspan=2 align=center height=100 style=color:dimgray id=mensagem></TD>
			</TR>
		</TABLE>
		<TABLE border=0 cellpadding=0 cellspacing=0 width=100%>
			<INPUT type=hidden name=i id=i value=$i>
			<INPUT type=hidden name=pai id=pai value='$pai'>
			<INPUT type=hidden name=lcp>
			<INPUT type=hidden name=cora value='#00b3ad'>
			<TR class=cor4>
				<TD>&nbsp;<A href=../index.php target=home><IMG src=../_gravar/logo.png height=40 border=0></A> $meni &nbsp;</TD>
			</TR><TR>
				<TD>";
$final_pagina="</TD>
			</TR>
		</TABLE>
	</BODY>
</HTML>";

//Forca atualização
header("Pragma: no-cache"); 
?>