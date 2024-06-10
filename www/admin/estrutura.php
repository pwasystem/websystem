<?PHP
include 'dados.php';
error_reporting(E_ALL);
if ($l>0){
//@extract($mysql->query()->fetch(PDO::FETCH_ASSOC));

//Gera arrays javascript
function ls($qr){
	$mysql=$GLOBALS['mysql'];
	$r0='';
	$r1='';
	$r2='';
	foreach($mysql->query($qr) as $s){
		$r0.="$s[0],";
		$r1.="$s[1],";
		$r2.="<option value=$s[0]>$s[1]";
	}
	return substr($r0,0,-1)."|".substr($r1,0,-1)."|".$r2;
}

//Lista de acesso pagina/usuarios
if (isset($_GET['listar'])){
	if (isset($_GET['adiciona'])) if (!@extract($mysql->query("SELECT arquivo FROM acesso WHERE arquivo=$_GET[listar] AND usuario=$_GET[adiciona]")->fetch(PDO::FETCH_ASSOC))) $mysql->exec("INSERT INTO acesso VALUES($_GET[listar],$_GET[adiciona])");
	if (isset($_GET['remove'])) $mysql->exec("DELETE FROM acesso WHERE arquivo=$_GET[listar] AND usuario=$_GET[remove]");
	foreach($mysql->query("SELECT u.id,u.nome FROM acesso a INNER JOIN usuarios u ON u.id=a.usuario WHERE arquivo=$_GET[listar]") as $a) $m.="<OPTION value=$a[0]>$a[1]";
	die(rawurlencode("<SELECT name=usuarios id=usuario style=width:275 size=10>$m</SELECT>"));
}

//Busca usuarios/ajax
if (isset($_GET['usuarios'])){
	$g=isset($_GET['g'])?$_GET['g']:(isset($_COOKIE['g']) ? $_COOKIE['g']:'');
	setcookie("z",$z,0);
	$ze=$g;
	$g=!empty($g)?"AND nome LIKE '%$g%' OR rg='$g'":'';
	$g=isset($_GET['g'])?(!empty($_GET['g'])?"AND nome LIKE '%$_GET[g]%' OR rg='$_GET[g]%'":''):'';
	extract($mysql->query("SELECT COUNT(id) total FROM usuarios WHERE 1=1 $g")->fetch(PDO::FETCH_ASSOC));
	if (!empty($g)) foreach($mysql->query("SELECT id,nome FROM usuarios WHERE 1=1 $g ORDER BY nome LIMIT $i,$e") as $a) $m.="<OPTION value='$a[0]'>$a[1]";
	$m=(empty($g) ?"<INPUT type=text id=b class=text style=width:235 value='Find User' onclick=this.select()><INPUT type=button class=button onclick=\"window.location='?usuarios&g='+document.getElementById('b').value\" value=ok>":(empty($m)?"<INPUT type=button onclick=\"window.location='?usuarios'\" value=' New search ' class=button> No results":"<SPAN class=controle>".($total>$e?($i==0?'<INPUT type=button value=" x " disabled class=button>':"<INPUT type=button value=' < ' onclick=\"window.location='?i=".($i-$e)."&g=".$ze."&usuarios'\" class=button>")."".(($i+$e)>=$total?'<INPUT type=button value=" x " disabled class=button>':"<INPUT type=button value=' > ' onclick=\"window.location='?i=".($i+$e)."&g=".$ze."&usuarios'\" class=button>"):'')."</SPAN> <INPUT type=button class=button style='text-decoration:none' onclick=\"parent.carrega('?listar='+parent.document.getElementById('idpu').value+'&adiciona='+document.getElementById('usoba').value,'usuariol')\" value='ok'><INPUT type=button class=button onclick=\"window.location='?usuarios'\" value=' x '> <SELECT id=usoba align=absmiddle style=width:200>$m</SELECT>"));
	die("<LINK rel=stylesheet href=../_gravar/css.css><BODY class=cor3>$m</BODY>");
}	

//função para remover imagens, flashs, diretorios e arquivar enquetes
function clean($id,$tudo){
	$mysql=$GLOBALS['mysql'];
	//apaga imagens
	foreach($mysql->query("SELECT nome FROM imagens WHERE pai=$id") as $a){
		$arquivo="../_gravar/$a[0]";
		file_exists($arquivo) ? unlink($arquivo):null;
	}
	foreach($mysql->query("SELECT nome FROM flash WHERE pai=$id") as $a){
		$arquivo="../_gravar/$a[0]";
		$teste=file_exists($arquivo) ? unlink($arquivo):null;
	}
	//arquiva e limpa dados
	$mysql->exec("UPDATE enquete SET pai=0 WHERE pai=$id;DELETE FROM acesso WHERE arquivo=$id;DELETE FROM imagens WHERE pai=$id;DELETE FROM flash WHERE pai=$id");
	//executa pesquisa para manupulação de arquivos
	if ($tudo!=0) {
		extract($mysql->query("SELECT nome pasta FROM arquivos WHERE id=$id")->fetch(PDO::FETCH_ASSOC));
		$pasta_del=nome_pasta($pasta);
		if (file_exists("../$pasta_del")&&!empty($pasta_del)) {
			if ($dir = opendir("../$pasta_del")) {
  				while ($file = readdir($dir)) $file!='.'&&$file!='..' ? unlink("../$pasta_del/$file"):null;
  				closedir($dir);
  				rmdir("../$pasta_del");
			}
		}
	}
}
//funcao para geracao de nomes de pastas
function nome_pasta($str){
$str=strtolower($str);
$com= array("/[À-Å]/","/Æ/","/Ç/","/[È-Ë]/","/[Ì-Ï]/","/Ð/","/Ñ/","/[Ò-ÖØ]/","/×/","/[Ù-Ü]/","/[Ý-ß]/","/[à-å]/","/æ/","/ç/","/[è-ë]/","/[ì-ï]/","/ð/","/ñ/","/[ò-öø]/","/÷/","/[ù-ü]/","/[ý-ÿ]/","/[!-\-]|[:-@]|[[-`]|[{-]|[€-Ÿ]|[¡-¿]/","/  /","/\//");
	$sem=array("A","AE","C","E","I","D","N","O","X","U","Y","a","ae","c","e","i","d","n","o","x","u","y",""," ","");
return preg_replace($com,$sem,$str);
}
//________________________________________Funções de atualização________________________________________
//apaga idioma
if (isset($_GET['idioma_a'])){
	if($_GET['idioma_a']!=1) {
		extract($mysql->query("SELECT idioma idioma_a FROM idioma WHERE id=$_GET[idioma_a]")->fetch(PDO::FECH_ASSOC));
		if (file_exists("../_gravar/layout/$idioma_a.htm")) unlink("../_gravar/layout/$idioma_a.htm");
		$mysql->exec("DELETE FROM idioma WHERE id=$_GET[idioma_a]");
		header("location:?id=$id&i=$i&o=1");
	}
}
//cria idioma
if (isset($_GET['n_idioma'])){
	if (!empty($_GET['n_idioma'])) {
		extract($mysql->query("SELECT idioma iNew FROM idioma WHERE id=1")->fetch(PDO::FETCH_ASSOC));
		$nlay=strtolower(nome_pasta($_GET['n_idioma']));
		copy("../_gravar/layout/$iNew.htm", "../_gravar/layout/$nlay.htm");
		$mysql->exec("INSERT INTO idioma(idioma) VALUES('$nlay')");
	}
}
//nova pagina
isset($_POST['pai'])?$mysql->exec("INSERT INTO arquivos(pai,data,nome,fonte,habilitado,menu,tipo,privilegio,chave,nivel,pasta,ordem,idioma) values('$_POST[pai]',now(),'$_POST[nova]','&nbsp;',1,0,1,0,'$_POST[nova]','$n,',0,0,$o)"):null;
//apaga página
if (isset($_GET['deleta'])&&$id!=1){
	$query="DELETE FROM arquivos WHERE id=$id";
	clean($id,1);
}
//Renomear
if (isset($_GET['nome'])){
	$nome_post=str_replace("'",'`',$_GET['nome']);
	if (!empty($nome_post)) {
		$query="UPDATE arquivos SET nome='$nome_post' WHERE id=$id";
		extract($mysql->query("SELECT nome pasta_nome,pasta pasta_exist FROM arquivos WHERE id=$id")->fetch(PDO::FETCH_ASSOC));
		if ($pasta_exist==1){
			$nome_antigo=nome_pasta($pasta_nome);
			$nome_novo=nome_pasta($nome_post);
			rename ("..//$nome_antigo" ,"..//$nome_novo"); 
		}
	}
}
//tipo
if (isset($_GET['tipo'])){
	$query="UPDATE arquivos SET tipo='$_GET[tipo]',fonte='".($_GET['tipo']==0?1:'conteudo')."' WHERE id=$id";
	clean($id,0);
}
//sistema
if (isset($_GET['sistema'])){
	$sistema_post=$_GET['sistema'];
	if ($sistema_post==0){
		$query="UPDATE arquivos SET tipo=1,fonte='&nbsp;' WHERE id=$id";
		clean($id,0);
	} else {
		$query="UPDATE arquivos SET tipo=0,fonte='$sistema_post' WHERE id=$id";
		extract($mysql->query("SELECT nome nome_sistema FROM sistemas WHERE id=$sistema_post ORDER BY nome")->fetch(PDO::FETCH_ASSOC));
		clean($id,0);
	}
}
//Redireciona
if (isset($_GET['pasta'])){
	extract($mysql->query("SELECT nome pasta_nome FROM arquivos WHERE id=$id")->fetch(PDO::FETCH_ASSOC));
	$pasta_valida=nome_pasta($pasta_nome);
	$pasta_post=$_GET['pasta'];
	if ($pasta_post=='true'){
		 mkdir ("../$pasta_valida", 0777);
		 $arquivo = fopen ("../$pasta_valida/default.htm" , "w");
		 fwrite ($arquivo, "<HTML><HEAD><META http-equiv=Refresh content='1;url=../index.php?id=$id'><SCRIPT>window.location='../index.php?id=$id'</SCRIPT></HEAD></HTML>");
	} else {
		 if(file_exists("../$pasta_valida/default.htm")){
			 unlink("../$pasta_valida/default.htm");
			 rmdir("../$pasta_valida");
		} 
	 }
	$query="UPDATE arquivos SET pasta=$pasta_post WHERE id=$id";
}
isset($_GET['menu'])?$query="UPDATE arquivos SET menu=$_GET[menu] WHERE id=$id":null;//menu
isset($_GET['idioma']) ? $query="UPDATE arquivos SET idioma=$_GET[idioma] WHERE id=$id":null;//idioma
isset($_GET['mover'])?$query="UPDATE arquivos SET pai=$_GET[mover] WHERE id=$id":null;//mover
isset($_GET['habilitado']) ? $query="UPDATE arquivos SET habilitado=".($_GET['habilitado']=='true'?1:0)." WHERE id=$id":null;//habilitado
isset($_GET['privilegio']) ?  $query="UPDATE arquivos SET privilegio=".($_GET['privilegio']=='true'?1:0)." WHERE id=$id":null;//privilegio
isset($_GET['ajax']) ?  $query="UPDATE arquivos SET ajax=".($_GET['ajax']=='true'?1:0)." WHERE id=$id":null;//ajax
isset($_GET['grupo']) ? ($query="UPDATE arquivos SET nivel='$_GET[grupo]' WHERE id=$id"):null;//seguranca
isset($_GET['ordem']) ? $query="UPDATE arquivos SET ordem=$_GET[ordem] WHERE id=$id":null; //ordem
isset($_GET['chave']) ? $query="UPDATE arquivos SET chave='".str_replace("'",'´',str_replace('"','',str_replace('\\','',$_GET['chave'])))."' WHERE id=$id":null;//"palavra-chave
//Update
$id>0&&!empty($query)?$mysql->exec($query):null;
//retorna nada
isset($_GET['habilitado'])||isset($_GET['privilegio'])||isset($_GET['pasta'])||isset($_GET['chave'])||isset($_GET['menu'])?die():'';

//________________________________________Cria variáveis________________________________________
//Navegação da estrutura
$nivel_label="";
if ($pai==0) {
	$acima=0;
	$nivel_atual="Main level";
} else {	
	extract($mysql->query("SELECT pai acima,nome nivel_atual FROM arquivos WHERE id=$pai")->fetch(PDO::FETCH_ASSOC));
}
$pasta_atual=$pai;
while ($pasta_atual!=0) {
	extract($mysql->query("SELECT id id_arquivo,pai pasta_atual,nome nome_arquivo FROM arquivos WHERE id=$pasta_atual")->fetch(PDO::FETCH_ASSOC));
	$nivel_label=($pai==$id_arquivo?"<A href='javascript:w_usuario($pai,0)'><B>$nome_arquivo</B></A>":"<A href=?pai=$id_arquivo><B>$nome_arquivo</B></A>/").$nivel_label;
}
$nivel_label=$pai==0?"<A href='javascript:w_usuario($pai,0)'><B>Main level</B></A>":"<A href=?pai=0><B>Main level</B></A>/$nivel_label";

//segurança dos sistemas
$sis_n=$n==1?'':"AND (concat(',',nivel) LIKE '%,$n,%' OR concat(',',nivel) LIKE '%,1,%')";

//gera arrays
$me=explode('|',ls("SELECT id,replace(nome,'menu_','') FROM menu WHERE habilitado=1 AND nome like '%menu%' ORDER BY nome"));
$me[0]='0,'.$me[0];
$me[1]='No,'.$me[1];
$pa=explode('|',ls("SELECT arquivos.id,arquivos.nome FROM arquivos INNER JOIN sistemas ON arquivos.fonte=sistemas.id WHERE pai='$pai' AND tipo=0 AND sistemas.nome REGEXP'[Ff]older' AND idioma=$o ORDER BY arquivos.nome"));
$pa[0]=($pai==0?"$pai,":"$acima,$pai,").$pa[0];
$pa[1]=($pai==0?"$nivel_atual,":"Above,$nivel_atual,").$pa[1];
$ss=explode('|',ls("SELECT id,nome FROM sistemas WHERE nome NOT REGEXP'[Ff]older' $sis_n ORDER BY nome"));
$ss[0]='0,'.$ss[0];
$ss[1]='Editor,'.$ss[1];
$sp=explode('|',ls("SELECT id,nome FROM sistemas WHERE nome REGEXP'[Ff]older' $sis_n ORDER BY nome"));
$nl=explode('|',ls("SELECT id,nome FROM nivel ORDER BY nome"));
$io=explode('|',ls("SELECT id,idioma FROM idioma ORDER BY idioma"));
//_____________________________________Consulta Principal_______________________________________
$ct='';
//segurança
$q='WHERE '.($per[0]==1?"pai=$pai AND CONCAT(',',nivel) LIKE '%,$n,%' ":"pai=$pai ")." AND idioma=$o ".(empty($w)?'':" AND nome LIKE '%".$w."%' ");
/*____________________________________________leitura dos arquivos_________________________________________________________*/
extract($mysql->query("SELECT COUNT(id) total FROM arquivos $q")->fetch(PDO::FETCH_ASSOC));
foreach($mysql->query("SELECT id,nome,fonte,habilitado,menu,tipo,privilegio,chave,nivel,pasta,ordem,idioma,ajax,view,data FROM arquivos $q ORDER BY ordem desc,nome limit $i,$e") as $a){
	$si=0;
	$se=0;
	if ($a[5]==0) {
		extract($mysql->query("SELECT count(id) si FROM arquivos WHERE pai=$a[0]")->fetch(PDO::FETCH_ASSOC));
		extract($mysql->query("SELECT count(id) se FROM sistemas WHERE nome regexp'[Ff]older' AND id='$a[2]'")->fetch(PDO::FETCH_ASSOC));
		extract($mysql->query("SELECT anexo FROM sistemas WHERE id=$a[2]")->fetch(PDO::FETCH_ASSOC));
		$editar=isset($anexo)?"<A href='anexo.php?sistema=$a[2]&id=$a[0]&w'><IMG src=i/edit.gif border=0 title='Edit page'></A>":"<BR>";
	} else {
		$editar="<a href='editorvisual.php?id=$a[0]&i=$i'><img src=i/edit.gif border=0 title='Edit page'></a>";
	}
	$papai=$se>0&&$si>0 ? true:false;
	$ct.="<tr align=center id=flip name=flip class=".($k=$k=='cor2'?'cor1':'cor2')."><td id=tipo$a[0] align=left>&nbsp;<img src=i/".($se?'cat':($a[5]==0?'alt':'pag')).".gif border=0 align=absmiddle title='".($se?'Folder':($a[5]==0?'System':'Page'))."\nView: $a[13]\nCreated: $a[14]'>&nbsp;<span id=nome$a[0]>".($papai?"<a href='?pai=$a[0]&w'>$a[1]</a>":$a[1])."</span></td>";
	$per[10]==1?$ct.="<td>$editar</td>":null;
	$per[33]==1?$ct.="<td><a href='editor.php?id=$a[0]&i=$i&tepo=1&campo=Extra'><img src=i/dat.gif title='Edit extra field' border=0></a></td>":null;
	$per[2]==1 ?$ct.="<td><img title='Change page name' src=i/cad.gif style=cursor:hand onclick=\"c($a[0],'nome','".str_replace("'",'`',$a[1])."',30,100,0);bbkp?document.all[bbkp].style.visibility='hidden':null\"></td>" :null;
	$per[9]==1 ?$ct.="<td><input type=hidden id=chave$a[0] value='$a[7]'><img title='Keyword Edit' src=i/find.gif style=cursor:hand onclick=\"pot($a[0],'chave',200,0,'Keywords',event);\"></td>":null;
	$per[5]==1 ?$ct.="<td><input type=hidden id=menu$a[0] value=$a[4]><img title='Change the menu display' src=i/men.gif style=cursor:hand onclick=\"men($a[0],$('menu$a[0]').value,m1,'menu',0,0,0,m0,0,'Menu',event)\"></td>":null;
	$per[4]==1 ?$ct.="<td><img id=mover$a[0] title='Move file to folder' src=i/cat.gif style=cursor:hand onclick=\"men($a[0],$pai,p1,'mover',$a[0],1,0,p0,1,'Move',event)\"></td>":null;
	$per[12]==1?$ct.="<td><img id=sistema$a[0] title='Changes the system used on page' src=i/alt.gif style=cursor:hand onclick=\"men($a[0],".($a[5]==0?$a[2]:0).",t1,'sistema',0,0,0,t0,1,'System',event)".($papai?null:";men($a[0],".($a[5]==0?$a[2]:0).",s1,'sistema',0,0,1,s0,1,'System',event)")."\"></td>":null;
	$per[16]==1?$ct.="<td><img id=idioma$a[0] title='Change the language of the page' src=i/idi.gif border=0 style=cursor:hand onclick=\"men($a[0],$a[11],i1,'idioma',0,0,0,i0,1,'Layout',event)\"></td>":null;
	$per[13]==1?$ct.="<td><img title='Edit access levels' src=i/niv.gif border=0 style=cursor:hand onclick=\"niv('$a[8]',$a[0],event)\"></td>":null;
	$per[32]==1?$ct.="<td><img title='Edit user access' src=i/usu.gif border=0 style=cursor:hand onclick=\"usun($a[0],event)\"></td>":null;
	$per[15]==1?$ct.="<td><input type=hidden id=ordem$a[0] value=$a[10]><a href=# onclick=\"pot($a[0],'ordem',50,1,'Order',event)\">$a[10]</a></td>":null;
	$per[6]==1 ?$ct.="<td><input type=checkbox class=radio onclick=\"carrega('?id=$a[0]&habilitado='+this.checked,'jax')\" ".($a[3]==0?'':'checked')."></td>":null;
	$per[7]==1 ?$ct.="<td><input type=checkbox class=radio onclick=\"carrega('?id=$a[0]&privilegio='+this.checked,'jax')\" ".($a[6]==0?'':'checked')."></td>":null;
	$per[8]==1 ?$ct.="<td><input type=checkbox class=radio onclick=\"carrega('?id=$a[0]&pasta='+this.checked,'jax')\" ".($a[9]==0?'':'checked')."></td>":null;
	$per[31]==1 ?$ct.="<td><input type=checkbox class=radio onclick=\"carrega('?id=$a[0]&ajax='+this.checked,'jax')\" ".($a[12]==0?'':'checked')."></td>":null;
	$per[3]==1 ?$ct.="<td>".($papai||$a[0]==1||($a[8]==1&&$n!=1)?'<br>':"<img src=i/del.gif title='Delete page' onclick=\"javascript:posta($a[0],$a[0],'apaga')\" style=cursor:hand>")."</td>":null;
	$ct.="</tr>";
}
/*____________________________________________fim da leitura dos arquivos_________________________________________________________*/
//define menu
$mn=$per[1]==1 ? "<tr class=cor3 align=center><td align=left><form name=novapagina method=post action=estrutura.php?w onsubmit='return testa_novo()'><input type=hidden name=pai value=$pai><input type=hidden name=nivel_pagina value=".($per[0]==1?$l:0)."><a href=# onclick=\"nov.style.visibility='visible'\"><img src=i/new.gif border=0 align=absmiddle title='Create new page'><b>Pages</b></a>&nbsp;<span id=nov style=visibility:hidden><input type=text name=nova class=text maxlength=100 size=20><input type=submit value=OK class=button><input type=button value=' X ' onclick=\"ver_n('nov')\" class=button></span></form></td>" :"<tr class=cor3 align=center><td align=left><b>Páginas</b></td>";
$per[2]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[33]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[10]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[9]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[5]==1 ? $mn.="<td width=22>&nbsp;</td>":null;
$per[4]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[12]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[16]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[13]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[32]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
$per[15]==1 ? $mn.="<td width=30><img src=i/ord.gif border=0 title='Click the numbers below to change the order of the menu item, the highest values ​​will be above' align=absmiddle></td>":null;
$per[6]==1 ? $mn.="<td width=25><img src=i/hab.gif border=0 title='Click the checkboxes below to determine if the pages can be viewed'></td>":null;
$per[7]==1 ? $mn.="<td width=25><img src=i/pri.gif border=0 title='Click the boxes below to block pages with password'></td>":null;
$per[8]==1 ? $mn.="<td width=25><img src=i/red.gif border=0 title='Click the boxes below to create redirects for pages'></td>":null;
$per[31]==1 ? $mn.="<td width=25><img src=i/carea.gif border=0 title='Click the checkboxes below to display only the content of the page without the layout'></td>":null;
$per[3]==1 ? $mn.="<td width=20>&nbsp;</td>":null;
//define idioma exibido
$imo=0;
extract($mysql->query("SELECT count(id) imo FROM arquivos WHERE idioma=$o")->fetch(PDO::FETCH_ASSOC));
$idioma=$per[16]==1 ? "<span id=dio><a href=javascript:n_idioma(1)><img src=i/idi.gif border=0 title='Click here to create a new layout' align=absmiddle border=0><b>Layout: </b></a><SELECT name=idioma id=idioma onchange=\"posta(0,this.value,'o')\">".(str_replace("value=$o>","value=$o selected>",$io[2]))."</select> ".($imo<0? "<a href=\"javascript:window.location='?idioma_a='+$('idioma').value\" ><img src=i/del.gif border=0></a>":'')."</span>":'';
//escreve pagina
echo "$inicio_pagina
<script>
m0='$me[0]'.split(',')
m1='$me[1]'.split(',')
i0='$io[0]'.split(',')
i1='$io[1]'.split(',')
n0='$nl[0]'.split(',')
n1='$nl[1]'.split(',')
p0='$pa[0]'.split(',')
p1='$pa[1]'.split(',')
s0='$ss[0]'.split(',')
s1='$ss[1]'.split(',')
t0='$sp[0]'.split(',')
t1='$sp[1]'.split(',')
//valida postagem
function testa_novo(){
	if(document.novapagina.nova.value==''){
		avisar('The name field must be filled',1)
		return false
	} else {
		return true
	}
}
//novo idioma
function n_idioma(p){
	if (p==1){
		bkp_i=dio.innerHTML
		dio.innerHTML='<b>New layout:</b> <input type=text id=noi maxlenght=30 class=text><input type=button onclick=\'window.location=\"?n_idioma=\"+$(\"noi\").value\' class=button value=Ok><input type=button onclick=n_idioma(0) class=button value=X>'
	} else {
		dio.innerHTML=bkp_i
	}
}
mu=''
acao=0
//Gera caixa suspensa
function pot(a,b,c,d,f,e){//id,valor,label,tamanho
	mu=a
	ir=b	
	acao=d
	text=$('texto')
	text.style.width=c
	$('labl').innerHTML=f
	text.value=$(b+''+a).value
	ver('barra3',e)
}
//gerencia add usuarios
function usun(idp,event) {
	$('subar').src='?usuarios'
	$('usuariol').innerHTML=''
	ver('barra4',event)
	$('idpu').value=idp
	carrega('?listar='+idp,'usuariol')
}
</script>
<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=cor1 bordercolor=red>
	<tr class=cor3>
		<td colspan=17 height=23> <b>&nbsp; Location:</b> $nivel_label <span id=wdor></span></td>
	</tr>$mn$ct<tr class=cor3>
		<td align=center colspan=17 class=controle>".($total>$e?($i==0?'x':"<a href=?i=".($i-$e)."&id=$id class=controle>Back</a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)."&id=$id class=controle>Next</a>"):'<BR>')."</td>
	</tr><tr class=cor4>
		<td colspan=17 align=right>$idioma&nbsp;</td>
	</tr>
</table>
<!--BARRA 1 - Níveis-->
<TABLE border=0 cellpadding=0 cellspacing=0 id=barra1 class='camada cor3'>
<tr name=barra id=barra width=300>
	<td colspan=2><b>Level</b></td>
	<td align=right><img src=i/clo.gif name=fecha></td>
</tr><tr class=cor3 align=center>
	<td><b>Denied</b></td>
	<td><br></td>
	<td><b>Allowed</b></td>
</tr><tr class=cor3>
	<td><SELECT name=permite id=per style=width:140 size=15></select></td>
	<td valign=top><img src=i/go.gif onclick=\"mn($('per').selectedIndex,0)\"><br><img src=i/back.gif onclick=\"mn($('neg').selectedIndex,1)\"><br><img src=i/save.gif onclick=\"pe='';for (ii=0;ii<$('neg').length;ii++)pe=pe+''+$('neg')[ii].value+',';posta(seguranca,pe,'grupo')\"></td>
	<td><SELECT name=nega id=neg style=width:140 size=15></select></td>
</tr>
</table>
<!--BARRA 2 - Seleção -->
<TABLE border=0 cellpadding=0 cellspacing=0 id=barra2 class='camada cor3'>
<tr class=cor4 width=300>
	<td colspan=2 name=barra id=barra>&nbsp;<b><span id=labe></span>:</b></td>
	<td><SELECT name=menux id=meu onchange=\"$(ir+''+mu).value=this.value;acao==0?carrega('?id='+mu+'&'+ir+'='+this.value,'jax'):posta(mu,this.value,ir);ver_n('barra2')\" style=width:150></select></td>
	<td><input type=button value=' x ' class=button name=fecha></td>
</tr>
</table>
<!--BARRA 3 - Textos -->
<TABLE border=0 cellpadding=0 cellspacing=0 id=barra3 class='camada cor3'>
	<tr class=cor4 width=300>
		<td colspan=2 name=barra id=barra>&nbsp;<b><span id=labl></span>:</b></td>
		<td><input type=text class=text id=texto size=50></td>
		<td><input type=button class=button value=ok onclick=\"$(ir+''+mu).value=texto.value;acao==0?carrega('?id='+mu+'&'+ir+'='+texto.value,'jax'):posta(mu,texto.value,ir);ver_n('barra3')\"><input type=button value=' x ' class=button name=fecha></td>
	</tr>
</table>
<!--BARRA 4 - Usuários-->
<TABLE border=0 cellpadding=0 cellspacing=0 id=barra4 class='camada cor3'>
	<tr name=barra id=barra>
		<td><b>Users</b></td>
		<td align=right><img src=i/clo.gif name=fecha></td>
	</tr><tr class=cor3>
		<td colspan=2><TABLE border=0 cellspacing=0>
			<tr>
				<td height=20 colspan=2><iframe src=?usuarios scrolling=no height=100% width=300 frameborder=0 marginheight=0 marginwidth=0 id=subar></iframe><input type=hidden id=idpu></td>
			</tr><tr>
				<td id=usuariol></td>
				<td align=right valign=top><img src=i/can.gif title='Select a user and click here to remove permission' style=cursor:hand onclick=\"if(!$('usuario').value){avisar('You need to select a user to remove permission',1)}else{carrega('?listar='+$('idpu').value+'&remove='+$('usuario').value,'usuariol')}\"></td>
			</tr>
		</table></td>
	</tr>
</table>
$final_pagina";
} else {
	echo "<script>window.location='index.php'</script>"; 
}?>