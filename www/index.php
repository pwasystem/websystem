<?PHP
function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
}
$tempo_no_inicio=getmicrotime();
require_once("admin/funcoes.php");
include("admin/conexao.php");
$dd=$mysql->query("SELECT titulo,descricao,palavra_chave,script,charset,erro,copia FROM dados");
empty($dd)?novo():null;
extract($dd->fetch(PDO::FETCH_ASSOC));
error_reporting($erro==0?0:E_ALL);
/*______________________________________________________Data_________________________________________________________________*/
$date=date("F d, Y");
/*______________________________________________________Conteúdo_____________________________________________________________*/
$id=(int)addslashes(isset($_REQUEST['id'])?(empty($_REQUEST['id'])?1:$_REQUEST['id']):1);
$id=$id==0?1:$id;
//busca url amigavel
if (isset($_GET['amigo'])) {
	extract($mysql->query("select id ziz from arquivos where replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(lower(nome),'/',''),'amp;',''),'(',''),')',''),',',''),'–','-'),'.','-'),'!',''),'?',''),':',''),'+',''),'&',''),'´','-'),'`','-'),'#43;',''),' ','-'),'---','-'),'--','-')='$_GET[amigo]'")->fetch(PDO::FETCH_ASSOC));
	$id=isset($zis)?$ziz:$id;
}
//carrega dados da página
if(!@extract($mysql->query("SELECT nome,fonte,tipo,enquete,habilitado,pai,privilegio,nivel,chave,ajax,extra FROM arquivos WHERE id='$id'")->fetch(PDO::FETCH_ASSOC))) die('File not found');
//especifica tipo de conteudo e seleciona sistema se necessário
if ($tipo==0){
	extract($mysql->query("SELECT fonte codigo_fonte FROM sistemas WHERE id=$fonte")->fetch(PDO::FETCH_ASSOC));
	$codigo_fonte=utf8_encode($codigo_fonte);
	$sistema=function($id,$mysql) use ($codigo_fonte) {
		return eval($codigo_fonte);
	};
	$conteudo=$sistema($id,$mysql);
} else {
	$conteudo=$fonte;
}
//bloqueio por login
$l=isset($_COOKIE['l'])?$_COOKIE['l']:0;
$n=isset($_COOKIE['n'])?$_COOKIE['n']:0;
$edita=0;
@extract($mysql->query("SELECT edita FROM nivel WHERE id=$n")->fetch(PDO::FETCH_ASSOC));
//bloqueio por grupo
if ($l==0&&$privilegio==1||$l>0&&$edita==1&&!strstr(",$nivel", ",$n,")&&!strstr("$nivel", "1,")) $conteudo="<CENTER><BR><B>You need a username and password to view this page</B></CENTER>";
//bloqueio por usuário
@extract($mysql->query("SELECT arquivo FROM acesso WHERE arquivo=$id")->fetch(PDO::FETCH_ASSOC));
if (isset($arquivo)){
	@extract($mysql->query("SELECT arquivo autoriza FROM acesso WHERE arquivo=$id AND usuario=$l")->fetch(PDO::FETCH_ASSOC));
	if (!isset($autoriza)&&$l!=1) $conteudo="<CENTER><BR><B>Access is limited to some users</B></CENTER>";
}
//bloqueia copia da pagina
$copy=$copia?"nosave oncontextmenu='return false' ondragstart='return false' onselectstart='return false' onmousedown='bloqueia_copia(event)' onload='classe()'":"onload='classe()'";
//adiciona tag form e blinda postagem
$conteudo=strstr($conteudo, 'name=email')||strstr($conteudo,'name="email"') ? "<FORM method=post action=index.php?id=$id enctype=multipart/form-data>$conteudo</FORM>":$conteudo;
if (strstr($conteudo, 'name=email')||strstr($conteudo,'name="email"')) setcookie('postagem',md5($id));
/*______________________________________________________Menu_________________________________________________________________*/
$menus="";
$idioma=(int)addslashes(isset($_GET['idioma'])?$_GET['idioma']:(isset($_COOKIE['idioma'])?$_COOKIE['idioma']:1));
$idioma=$idioma==0?1:$idioma;
setcookie('idioma',$idioma);
foreach($mysql->query("SELECT id,nome,fonte FROM menu WHERE habilitado=1") as $linha) {
	$fonte=utf8_encode($linha['fonte']);
	$menus=function($id,$idioma,$mysql) use ($fonte) {
		return eval($fonte);
	};
	${$linha['nome']}=$menus($linha['id'],$idioma,$mysql)."<br>";
	$conteudo=str_replace("<!--$linha[nome]-->",${$linha['nome']},$conteudo);
}
/*______________________________________________________HEAD_________________________________________________________________*/
@extract($mysql->query("SELECT id rs FROM arquivos WHERE nome='RSS' AND habilitado=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC));
$head="<HEAD>
		<TITLE>$titulo - $descricao</TITLE>	
		<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>	
		<META name='Description' content='$chave'>
		<META name='Keywords' content='$palavra_chave'>
		<META name='Author' content='spider_poison@hotmail.com'>
		<LINK rel='shortcut icon' href='_gravar/ico.ico'>
		<LINK rel='stylesheet' href='_gravar/css.css'>
		".(isset($rs)?"<LINK rel='alternate' type='application/rss+xml' title='$titulo' href='http://$_SERVER[HTTP_HOST]/index.php?id=$rs[0]'>":'')."
		<SCRIPT src='_gravar/js.js' charset='UTF-8'></SCRIPT>
	</HEAD>
";
/*________________________________________Contador e estatísticas____________________________________________________________*/
//contador views
$mysql->exec("UPDATE dados SET view=view+1");
//contador visitas
if (isset($_COOKIE['visitas'])) {
	$cookie=1;
	$hora=$_COOKIE['visitas'];
} else {
	$cookie=0;
	$hora=date("Hi");
	setcookie('visitas',$hora);
	extract($mysql->query("SELECT TO_DAYS(NOW())-TO_DAYS(semana_data) daa FROM estatistica WHERE id=1")->fetch(PDO::FETCH_ASSOC));
	$daa>=7?$mysql->exec("INSERT INTO estatistica SELECT null,dia,dia_temp,dia_data,semana,semana_temp,semana_data,mes,mes_temp,mes_data,ano,ano_temp,ano_data,segunda,segunda_madrugada,segunda_manha,segunda_tarde,segunda_noite,terca,terca_madrugada,terca_manha,terca_tarde,terca_noite,quarta,quarta_madrugada,quarta_manha,quarta_tarde,quarta_noite,quinta,quinta_madrugada,quinta_manha,quinta_tarde,quinta_noite,sexta,sexta_madrugada,sexta_manha,sexta_tarde,sexta_noite,sabado,sabado_madrugada,sabado_manha,sabado_tarde,sabado_noite,domingo,domingo_madrugada,domingo_manha,domingo_tarde,domingo_noite,temp,periodo FROM estatistica WHERE id=1"):null;
	$mysql->exec("UPDATE estatistica SET temp=temp+1 WHERE id=1;UPDATE dados SET visitas=visitas+1;UPDATE estatistica SET dia_temp=dia_temp+1, semana_temp=semana_temp+1, mes_temp=mes_temp+1, ano_temp=ano_temp+1 WHERE id=1;UPDATE estatistica SET dia=dia_temp, dia_data=NOW(), dia_temp=0 WHERE TO_DAYS(NOW())-TO_DAYS(dia_data) >=1 AND id=1;UPDATE estatistica SET semana=semana_temp, semana_data=NOW(), semana_temp=0 WHERE TO_DAYS(NOW())-TO_DAYS(semana_data) >=7 AND id=1;UPDATE estatistica SET mes=mes_temp, mes_data=NOW(), mes_temp=0 WHERE TO_DAYS(NOW()) - TO_DAYS(mes_data) >=30 AND id=1;UPDATE estatistica SET ano=ano_temp, ano_data=NOW(), ano_temp=0 WHERE TO_DAYS(NOW()) - TO_DAYS(ano_data) >=365 AND id=1");
	//estatisticas periodo
	$semana=explode(",","domingo,segunda,terca,quarta,quinta,sexta,sabado");
	$ontem=date('w')-1;
	$ontem<0?$ontem=6:null;
	$semana_ontem=$semana[$ontem];
	$semana=$semana[date('w')];
	extract($mysql->query("SELECT periodo FROM estatistica WHERE id=1")->fetch(PDO::FETCH_ASSOC));
	$periodo_visita=$periodo[0]==0&&$hora>600?"1,{$semana}_madrugada":($periodo[0]==1&&$hora>1200?"2,{$semana}_manha":($periodo[0]==2&&$hora>1800?"3,{$semana}_tarde":($periodo[0]==3&&$hora>0&&$hora<600?"0,{$semana_ontem}_noite":'x')));
	$periodo_visita!='x'?$mysql->exec("UPDATE estatistica SET periodo=$periodo_visita=temp,temp=0 WHERE id=1"):null;
	if ($periodo[0]==3&&$hora>0&&$hora<600) $mysql->exec("UPDATE estatistica SET $semana_ontem={$semana_ontem}_madrugada+{$semana_ontem}_manha+{$semana_ontem}_tarde+{$semana_ontem}_noite WHERE id=1");
	//atualiza_período
	$periodo_atual=$periodo[0]!=0&&$hora>0&&$hora<600?'0':($periodo[0]!=1&&$hora>600&&$hora<1200?'1':($periodo[0]!=2&&$hora>1200&&$hora<1800?2:($periodo[0]!=3&&$hora>1800&&$hora<2400?'3':'x')));
	$periodo_atual!='x'?$mysql->exec("UPDATE estatistica SET periodo=$periodo_atual WHERE id=1"):'';
}
if ($cookie=1&&(date("Hi")-$hora)>10) {
	$mysql->exec("UPDATE dados SET visitas=visitas+1;UPDATE estatistica SET dia_temp=dia_temp+1, semana_temp=semana_temp+1, mes_temp=mes_temp+1, ano_temp=ano_temp+1 WHERE id=1;UPDATE estatistica SET temp=temp+1 WHERE id=1");
	setcookie('visitas',date('Hi'));
}
//paginas views
$mysql->exec("UPDATE arquivos SET view=view+1 WHERE id='$id'");
//visitantes
extract($mysql->query("select visitas views from dados")->fetch(PDO::FETCH_ASSOC));
/*_________________________________________________________Enquete___________________________________________________________*/
if ($c=$mysql->query("SELECT * FROM enquete WHERE pai='$id'")->fetch(PDO::FETCH_NUM)){
	$enquete_acao=preg_replace('/[^A-z]/','',addslashes(isset($_GET['enquete'])?$_GET['enquete']:'inicio'));
	$enquete_texto='';
	if ($enquete_acao=='vota'||$enquete_acao=='resposta'||isset($_COOKIE["enquete$id"])==1) {
		$v=explode(",","$c[8],$c[9],$c[10]");
		if (isset($_COOKIE["enquete$id"])==0&&$enquete_acao=='vota'){
			setcookie ("enquete$id",'1',0);
			$re=$_POST['resposta'];
			$res=explode(',',$c[6]);
			$res[$re]=$res[$re]+1;
			$c[6]=implode(',',$res);
			$mysql->exec("UPDATE enquete SET valor='$c[6]' WHERE pai='$id'");
			$enquete_vota='';
		} else {
			$enquete_vota=isset($_COOKIE["enquete$id"])==0?"<A href=index.php?id=$id style='text-decoration:none;color:$c[8]'>Back</A> | ":'';
		}		
		$res=explode('|',substr($c[3],0,-1));
		$val=explode(',',substr($c[6],0,-1));
		$inc='';
		$cni='';
		$total=0;
		foreach($res as $ser => $der){
			if ($c[10]==1){
				$inc.="<TR bgcolor=$c[5]><TD align=center width=20>$val[$ser]</TD><TD width=100% style='font:bold 10px verdana;color:$c[9]'>$der</TD></TR>";
			} else {
				$inc.="<TD align=center>$val[$ser]</TD>";
				$cni.="<TD style='font:bold 10px verdana;color:$c[9]'>$der</TD>";
			}
			$total+=$val[$ser];
		}
		if($c[10]==0) $inc="<TR bgcolor=$c[5] align=center>$cni</TR><TR bgcolor=$c[5] align=center>$inc</TR>";
		$enquete_texto="<TR align=center><TD bgcolor=$c[4] style='font:bold 10px verdana;color:$c[8]' colspan=".($c[10]==0?count($res):2).">$c[2]</TD></TR>$inc<TR align=center><TD bgcolor=$c[4] style='font:bold 10px verdana;color:$c[8]' colspan=".($c[10]==0?count($res):2).">$enquete_vota Total : $total</TD></TR>";
	} else {
		$res=explode('|',substr($c[3],0,-1));
		$inc='';
		$cni='';
		foreach($res as $ser => $der){
			if ($c[10]==1){
				$inc.="<TR bgcolor=$c[5]><TD align=center width=20><INPUT type=radio name=r value=$ser class=radio></TD><TD width=100% style='font:bold 10px verdana;color:$c[9]'>$der</TD></TR>";
			} else {
				$inc.="<TD align=center><INPUT type=radio name=r value=$ser class=radio></TD>";
				$cni.="<TD style='font:bold 10px verdana;color:$c[9]'>$der</TD>";
			}
		}
		if($c[10]==0) $inc="<TR bgcolor=$c[5] td align=center>$cni</TR><TR bgcolor=$c[5] td align=center>$inc</TR>";
		$enquete_texto="<FORM method=post action=index.php?id=$id&enquete=vota name=enquete_post><INPUT type=hidden name=resposta><TR bgcolor=$c[4]><TD align=center colspan=".($c[10]==0?count($res):2)." style='font:bold 10px verdana;color:$c[8]'>$c[2]</TD></TR>$inc<TR bgcolor=$c[4] align=center><TD colspan=".($c[10]==0?count($res):2)."><A href=javascript:enquete_posta($id) style='text-decoration:none;color:$c[8]'><FONT size=1><B>Votar</B></FONT></A> <FONT size=1 color=$c[8]>|</FONT> <A href=index.php?id=$id&enquete=resposta style='text-decoration:none;color:$c[8]'><FONT size=1><B>Results</B></FONT></A></TD></TR></FORM>";
	}
	$conteudo=str_replace('$enquete',$enquete_texto,$conteudo);	 
}			
/*________________________________________________Posta Mensagen_____________________________________________________________*/
if (isset($_POST['email'])&&isset($_COOKIE['postagem'])){
	if ($_COOKIE['postagem']==md5($id)) {
		setcookie('postagem',0,time()-60);
		$email=$_POST['email'];
		$dmail="XYZ-".date("dmYis")."-ZYX";
		extract($mysql->query("SELECT mail_de,mail_se,mail_sm,mail_en FROM dados")->fetch(PDO::FETCH_ASSOC));
		$webmaster=empty($mail_de)&&$mail_en!=1?$webmaster:$mail_de;
		$responder=isset($_POST['responder'])?$_POST['responder']:$webmaster;
		$cabecalho="Reply-To:$responder{$quebra}MIME-Version: 1.0{$quebra}Content-type: multipart/mixed; boundary=\"$dmail\"{$quebra}From: $nome<$webmaster>{$quebra}$dmail{$quebra}";	
		$mensagem="--$dmail{$quebra}Content-Transfer-Encoding: 8bits{$quebra}Content-Type: text/html; charset=\"UTF-8\"{$quebra}{$quebra}<STYLE>TD {font:12px verdana}</STYLE>{$quebra}<TABLE border=0 cellpadding=0 cellspacing=0><TR valign=top><TD colspan=3 height=30><B>$nome</B></TD></TR>{$quebra}";
		//adiciona campos
		foreach ($_POST as $campo => $dados) $mensagem.='email'!=$campo?"<TR><TD align=right><B>$campo</B></TD><TD width=30 align=center> --> </TD><TD>".htmlentities(addslashes(strip_tags($dados)))."</TD></TR>{$quebra}":'';
		$mensagem.="</table>{$quebra}{$quebra}";
		//adiciona anexos		
		if (isset($_FILES)){
			foreach ($_FILES as $indice => $arquivo){
				if($arquivo["size"]>0){				
					$mensagem.= "--$dmail{$quebra}Content-Type: $arquivo[type]{$quebra}Content-Disposition: attachment; filename=\"$arquivo[name]\"{$quebra}Content-Transfer-Encoding: base64{$quebra}{$quebra}".chunk_split(base64_encode(file_get_contents($arquivo["tmp_name"])))."{$quebra}";  
				}
			}		
		}
		$mensagem.="--$dmail--{$quebra}";
		if (!empty($mail_de)&&!empty($mail_se)&&!empty($mail_sm)&&$mail_en==1) { //envio autenticado
			include('admin/xmail.php');
			xmail($email , $nome , $mensagem, $cabecalho);
		} else {//envio normal
			if(!@mail($email , $nome , $mensagem, $cabecalho))$extra='It was located a server to perform the sending of e-mails, check the configuration of your datacenter.';
		}	
		$conteudo="<CENTER><BR>$extra</CENTER>";
	}
}
/*________________________________________________________Habilita___________________________________________________________*/
if ($habilitado==0) $conteudo="<CENTER><B><BR>Page temporarily unavailable</B></CENTER>";
/*_________________________________________________________Busca_____________________________________________________________*/
$search="<table cellSpacing=0 border=0><FORM name=busca action=index.php method=post><TR><TD align=center class=buscador>Find: <INPUT size=8 maxlength=30 name=palavra class=text> <A href=# onclick=busca.submit() class=buscador>Ok</A></TD></TR></FORM></table>";
if(isset($_REQUEST['palavra'])){
	$e=20;
	$nome='Find';
	$conteudo='';
	$busca=preg_replace('/[^A-z]/','',$_REQUEST['palavra']);
	$i=addslashes(isset($_GET['i'])?(int)$_GET['i']:0);	
	$pv=$l==0?"AND privilegio=0":'';
	$pv.=$n==1?'':" and (concat(',',nivel,',') like '%,1,%' or  concat(',',nivel,',') like '%,1,%')";
	foreach($mysql->query("select id,nome,chave from arquivos where (nome like '$busca%' or chave like '%$busca%') and habilitado=1 $pv order by nome limit $i,$e") as $m) $conteudo.="<A href=index.php?id=$m[0] style='text-decoration:none'><B class=busca_titulo>$m[1]</B><BR><span class=busca_texto>$m[2]</span></A><hr class=busca_linha>";
	extract($mysql->query("select count(id) total from arquivos where (nome like '$busca%' or chave like '%$busca%') and habilitado=1 $pv")->fetch(PDO::FETCH_ASSOC));
	$conteudo=empty($conteudo)?"No results were found for your search<BR>":"Your search returned <B>$total</B> results<BR><BR>".substr($conteudo,0,-22)."<div align=center class=controle>".($total>$e ? ($i==0 ? 'x':"<A href=?i=".($i-$e)."&id=$id&palavra=$busca class=controle>Back</A>")."|".(($i+$e)>=$total?'x':"<A href=?i=".($i+$e)."&id=$id&palavra=$busca class=controle>Next</A>"):' ')."</div>";
}
/*_________________________________________________________Banner____________________________________________________________*/
function banner($categoria,$largura,$altura,$id,$pop){
	$mysql=$GLOBALS['mysql'];
	if(@extract($mysql->query("SELECT id,link,imagem,visualizar,target FROM banner WHERE nome='$categoria' AND visualizar>0 ORDER BY RAND() LIMIT 1")->fetch(PDO::FETCH_ASSOC))){
		if (strstr($visualizar,'-')){
			(str_replace('-','',$visualizar)-date('Ymd'))<0 ? $mysql->exec("UPDATE banner SET visualizar='0' WHERE id=$id"):null;
		} else if ($visualizar>0){
			$mysql->exec("UPDATE banner SET visualizar=".($visualizar-1)." WHERE id=$id");
		}
		$banner='';	
		if ($pop==0||$pop==1||$pop>=3){
			$banner=strstr($imagem,'.swf')?"<script>banner('_gravar/banner.swf?imagem=_gravar/$imagem&url=$link&target=".($target==0?'_top':'_blank')."',$largura,$altura)</script>":"<A href='$link' target=".($target==0?'_top':'_blank')."><img src=_gravar/$imagem border=0 width=$largura height=$altura></A>";
			if ($pop==1) {
				$banner="<script>
				x=window.open('','popup','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=$largura,height=$altura');
				x.document.open();
				x.document.write(\"<html><Body marginheight=0 marginwidth=0 topmargin=0 leftmargin=0>$banner</Body></html>\");
				x.document.close();
				</script>";
			} elseif ($pop>=3) {
				$banner="<span id=flutuante style=position:absolute;top:0;left:0;width:100%;text-align:center;padding-top:60>$banner</span><script>function fecarme(){flutuante.style.display='none'};setTimeout('fecarme()',1200*$pop)</script>";
			}
		} elseif ($pop==2) {
			$banner="_gravar/$imagem";
		}		
		return $banner;
	}
}
/*__________________________________________________Alias____________________________________________________________________*/
$name=$nome;
$domain=$dominio;
$content=$conteudo;
/*_________________________________________________Retorna conteudo__________________________________________________________*/
if (strstr($conteudo,'<!--ajax-->')||$ajax==1){
	print(str_replace('<!--nocode-->','',str_replace('<!--ajax-->','',(strstr($conteudo,'<!--nocode-->')?"$conteudo":(strstr($conteudo,'<!--ajax-->')?$conteudo:"<link rel=stylesheet href=_gravar/css.css><body topmargin=0 leftmargin=0>$conteudo")))));
	return false;
} else {
	extract($mysql->query("SELECT idioma idsel FROM idioma WHERE id=$idioma")->fetch(PDO::FETCH_ASSOC));
	$conteudo="<span id=conteudo_da_tela>$conteudo</span>";
	require("_gravar/layout/$idsel.htm");
}
$script==1?$mysql->exec("INSERT INTO time VALUES('$_SERVER[REMOTE_ADDR]',now(),'$id',".(getmicrotime()-$tempo_no_inicio).")"):null;
?>