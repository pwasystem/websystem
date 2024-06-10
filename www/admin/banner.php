<?PHP
include "dados.php";
if ($l>0&&$per[19]==1){
//novo
$aviso='';
if (isset($_GET['novo'])){
	$nome='b'.date("YmdGis").strstr($_FILES['imagem']['name'],'.');
	move_uploaded_file($_FILES['imagem']['tmp_name'], "../_gravar/$nome");
	$mysql->exec("INSERT INTO banner(nome,link,imagem,visualizar) VALUES('$_POST[fategoria]','".rawurldecode($_POST['link'])."','$nome','$_POST[visualizar]')");
	$aviso="<SCRIPT>avisar('The banner was registered successfully',1)</SCRIPT>";
}
//apaga
if (isset($_GET['deleta'])){
	@extract($mysql->query("SELECT imagem FROM banner WHERE id=$id")->fetch(PDO::FETCH_ASSOC));
	if(isset($imagem)) file_exists("../_gravar/$imagem")?unlink("../_gravar/$imagem"):null;
	$mysql->exec("DELETE FROM banner WHERE id=$id");
}
//update
isset($_GET['link'])?$mysql->exec("update banner set link='".rawurldecode($_GET['link'])."' where id=$id"):null;
isset($_GET['target'])?$mysql->exec("update banner set target='".($_GET['target']=='true'?1:0)."' where id=$id"):null;
isset($_GET['categoria'])? (empty($_GET['categoria'])?'':$mysql->exec("update banner set nome='$_GET[categoria]' where id=$id")):null;
isset($_GET['visualizar'])?$mysql->exec("update banner set visualizar='$_GET[visualizar]' where id=$id"):null;
//retorna nada
isset($_GET['link'])||isset($_GET['target'])||isset($_GET['visualizar'])?die():'';
//lista categorias de banner
$categor='';
$categori='';
$w=isset($_GET['w']) ? "where link like '%".$_GET['w']."%'" : '';
foreach($mysql->query("select distinct(nome) from banner order by nome") as $a)$categor.="<option value='$a[0]'>$a[0]";
$categoria = $categor!='' ? "<span id=categorial0><Select id=categoria name=categoria>$categor</select><a href=javascript:n_categ(0,0,'categoria')> - <b>New</b></a></span>" : "<input name=categoria id=categoria class=text maxlenght=50 size=50>";
$ce=isset($_GET['c_exibir'])?$_GET['c_exibir']:'';
$w=!empty($ce) ? "where nome='$ce'":$w;
$c_exibir = "<span id=c_exi style=visibility:hidden><select name=c_exibir onchange=posta(0,this.value,'c_exibir')><option value=''>All".str_replace("value='$ce'>", "value='$ce' selected>", $categor)."</select></span>";
//lista banners
foreach($mysql->query("select id,nome,link,visualizar,target from banner $w order by nome limit $i,$e") as $a){
	$m.="<tr name=flip id=flip class=".($k=$k=='cor2'?'cor1':'cor2').">
	<td><span id=link$a[0]><a href=\"javascript:c($a[0],'link','".rawurlencode(addslashes($a[2]))."',30,250,1)\">$a[2]</a></span></td>
	<td align=center><span id=visualizar$a[0]><a href=\"javascript:c($a[0],'visualizar','".rawurlencode(addslashes($a[3]))."',15,10,1)\">".(empty($a[3])?0:$a[3])."</a></span></td>
	<td><span id=categorial$a[0]><a href=javascript:n_categ(0,$a[0],'categoria')><img src=i/new.gif border=0 align=absmiddle></a><select name=categoria$a[0] onchange=posta($a[0],this.value,'categoria')>".str_replace("value='$a[1]'>", "value='$a[1]' selected>", $categor)."</select></span></td>
	<td><input type=checkbox class=radio onclick=\"carrega('?id=$a[0]&target='+this.checked,'jax')\" ".($a[4]==0?'':'checked')."></td>
	<td><a href=".($a[1]<1 ? "\"javascript:posta($a[0],0,'apaga')\"":'&nbsp;')." ><img src=i/del.gif border=0></a></td>
</tr>";
}
extract($mysql->query("select count(id) total from banner $w")->fetch(PDO::FETCH_ASSOC));
echo"$inicio_pagina
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr class=cor4>
		<td colspan=5>&nbsp;<img src=i/ban.gif border=0 title='Create new banner' onclick=\"ver('novo',event)\" style=cursor:hand align=absmiddle>&nbsp;<b><a href=javascript:w_usuario(0,0)><b>Banner</b></a> <span id=wdor></span></b></td>
	</tr><tr class=cor3>
		<td><b>URL</b></td>
		<td width=200 align=center><b>Show up</b></td>
		<td width=200><a onclick=\"c_exi.style.visibility=c_exi.style.visibility=='hidden'?'visible':'hidden'\"><b>Category</b></a> $c_exibir</td>
		<td width=20 align=center><img src=i/fonte.gif title='Select this option to display a result of the link on another page'></td>
		<td width=20>&nbsp;</td></tr>$m<tr><td colspan=5 class=cor3 align=center class=controle>".($total>$e?($i==0 ? 'x':"<a href=?i=".($i-$e)." class=controle>Back</a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)." class=controle>Next</a>"):'<BR>')."</td>
	</tr><tr>
		<td colspan=5 class=cor4 align=center><br></td>
	</tr>
</table>
<!--novo-->
<table border=0 cellpadding=1 cellspacing=0 id=novo class='camada cor3'>
	<tr id=barra name=barra>
		<td><b>Create new banner</b></td>
		<td align=right><img src=i/clo.gif name=fecha></td>
	</tr><tr><form method=post action=banner.php?novo=1 enctype=multipart/form-data name=forh onsubmit='return t_banner()'>
		<td colspan=2 align=right><input type=image src=i/save.gif border=0></td>
	</tr><tr>
		<td colspan=2 align=center><table border=0 cellpadding=1 cellspacing=0>
			<td align=right><b>Category: </b></td>
			<td>$categoria <input type=hidden id=fategoria name=fategoria></td>
		</tr><tr>
			<td align=right><b>URL: </b></td>
			<td><input type=text name=link id=link size=50 maxlenght=250 class=text></td>
		</tr><tr>
			<td align=right><b>Show up: </b></td>
			<td><input type=text class=text maxlenght=10 name=visualizar id=visualizar size=15> Views or yyyy-mm-dd</td>
		</tr><tr>
			<td align=right><b>File: </b></td>
			<td><span style='border:outset 1px gray;background:silver'><input type=file name=imagem id=imagem class=text style='width:100;border:none'></span> (jpg,gif,png or swf)</td>
		</tr>
	</table></td>
	</tr>
</form></table>
<SCRIPT>
	function t_banner(){
		if (!$('categoria').value) {
			avisar('Filling the Category field is required',1)
			return false
		} else {
			$('fategoria').value=$('categoria').value
		}
		if (!$('link').value) {
			avisar('Filling the URL field is required',1)
			return false
		} else {
			$('link').value=escape($('link').value)
		}
		if (!$('visualizar').value||numero($('visualizar').value)&&!data($('visualizar').value)) {avisar('The field view is populated incorrectly',1);return false};
		imagen=$('imagem').value.toLowerCase()
		if (!imagen.match('.jpg')&&!imagen.match('.jpeg')&&!imagen.match('.gif')&&!imagen.match('.png')&&!imagen.match('.swf')) {avisar('You must send files jpg, gif or swf',1);return false};return true}

	b=0
	bkp=''
	function n_categ(cx,i,camada){
		if (cx==0) {
			categ=camada+'l'+i
			if (b!=0) $(camada+'l'+b).innerHTML=bkp;
			b=i
			bkp=$(categ).innerHTML;
			$(categ).innerHTML=i>0?'<input name='+camada+i+' id='+camada+i+' class=text maxlenght=50 size=15><input type=button value=\\' ok \\' class=button onclick=posta('+i+','+camada+i+'.value,\\''+camada+'\\')><input type=button value=\\' x \\' class=button onclick=n_categ(1,'+i+')>':'<input name='+camada+' id='+camada+' class=text maxlenght=50 size=30> - <a href=javascript:n_categ(1,'+i+')><b>Existing</a></b>';
			n_bkp=b
		} else {
			$(categ).innerHTML=bkp;
		}
	}
</SCRIPT>
$aviso
$final_pagina";

}else{
	header("Location : index.php");
}
?>