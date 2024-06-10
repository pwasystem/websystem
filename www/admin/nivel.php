<?PHP
include "dados.php";
if ($per[27]==1){
	//apaga e executa updates.
	isset($_GET['novo'])?(empty($_GET['novo'])?null:$mysql->query("insert into nivel(nome) values('$_GET[novo]')")):null;
	isset($_GET['deleta'])?$mysql->query("delete from nivel where id=$id"):null;
	isset($_GET['raiz'])?$mysql->query("update nivel set raiz='".(empty($_GET['raiz'])?'../':$_GET['raiz'])."' where id=$id"):null;
	$cp=array('nome','restrito','pagina_a','pagina_c','conteudo','pagina_r','sistema_a','sistemas','sistema_e','usuarios_e','usuarios_n','menu_e','menu','habilitado','privilegio','redirecionamento','chave','estrutura','ordem','idioma','master','ftp','estatistica','enquete','banner','log','xql','nivel','edita','sistema_n','sistema_d','ajax','pagina_p','pagina_e','xmaix');
	foreach($cp as $dv) {
		isset($_GET[$dv])?$mysql->query("update nivel set $dv='".($dv=='nome'||$dv=='raiz'?$_GET[$dv]:($_GET[$dv]=='true'?1:0))."' where id=$id"):'';
		isset($_GET['raiz'])||isset($_GET[$dv])?die():'';
	}	
	//paginacao
	$u="";
	foreach($mysql->query("select distinct nivel from usuarios") as $a) $u.=",$a[0]";
	$u=explode(',',$u);
	$w=isset($_GET['w'])?"where nome like '%$_GET[w]%'":'';
	extract($mysql->query("select count(id) total from nivel $w")->fetch(PDO::FETCH_ASSOC));	
	foreach($mysql->query("select id,nome,edita,restrito,sistema_e,pagina_c,conteudo,pagina_e,pagina_r,chave,habilitado,privilegio,redirecionamento,ajax,ordem,usuarios_e,pagina_p,estrutura,idioma,pagina_a,menu,menu_e,sistema_a,sistemas,sistema_n,sistema_d,ftp,raiz,master,usuarios_n,nivel,estatistica,enquete,banner,log,xql,xmaix from nivel $w order by nome limit $i,$e", PDO::FETCH_ASSOC) as $a){
		$apaga="<a href=\"javascript:posta($a[id],$a[id],'apaga')\"><img src=i/del.gif align=absmiddle border=0 title='Deletes the user level'";
		for ($i=0;$i<count($u);$i++) $u[$i]==$a['id'] ? $apaga="&nbsp;":null;
		$m.="<tr align=center name=flip id=flip class=".($k=$k=='cor2'?'cor1':'cor2').">";
		foreach($a as $nc => $vc){
			if($nc=='nome'||$nc=='raiz'){
				$m.="<td align=left><span id=$nc$a[id]><a ".($a['id']==1?'style=cursor:default':"href=\"javascript:c($a[id],'$nc','$a[$nc]',20,30,1)\"").">$a[$nc]</a></span></td>";
			} elseif ($nc!='id') {
				$m.="<td><input type=checkbox ".($a['id']==1?'disabled':"onclick=carrega('?id=$a[id]&$nc='+this.checked,'jax')")." ".($a[$nc]==0?'':'checked')."></td>";		
			}
		}
		$m.="<td width=20>$apaga</td></tr>";
	}
	//escreve códido fonte da página
	echo "$inicio_pagina
<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr class=cor4>
	<td colspan=37 height=20><b>&nbsp;
	<a href=# onclick=\"ver('nite',event)\">
	<img src=i/niv.gif border=0 align=absmiddle title='Click here to create a new level of user'>&nbsp;</a><a href=\"javascript:w_usuario(0,0)\"><b>Level</b></a> <span id=wdor></span></b><span id=novo_ni></span></td></tr><tr class=cor3 align=center>
	<td colspan=4>&nbsp;</td>
	<td colspan=15 align=left>&nbsp;<img src=i/new.gif border=0 title='Sets whether the user can create a new page' align=absmiddle><b>Pages</b></td>
	<td colspan=2 align=left width=54><img src=i/men.gif border=0 title='Sets whether the user can change where the menu page is displayed' align=absmiddle><b>Menu</b></td>
	<td colspan=4 align=left width=80>&nbsp;<img src=i/alt.gif border=0 title='Sets whether the user can change the systems used on the pages' align=absmiddle><b>&nbsp;Systems</b></td>
	<td colspan=2 align=left width=80>&nbsp;<img src=i/ftp.gif border=0 title='Displays FTP virtual button' align=absmiddle><b>&nbsp;FTP</b></td>
	<td colspan=87><b>Extras</b></td>
</tr><tr class=cor3 align=center>
	<td align=left><b>Name</b></td>
	<td width=20><img src=i/por.gif border=0 title='Sets the user will be allowed to view only their pages'></td>
	<td width=20><img src=i/lap.gif border=0 title='Sets the user is allowed to edit their pages only'></td>
	<td width=20><img src=i/bos.gif border=0 title='Blocks administration interface to user'></td>
	<td width=20>&nbsp;</td>
	<td width=20><img src=i/edit.gif border=0 title='Sets whether users can edit pages'></td>
	<td width=20><img src=i/pag.gif border=0 title='Sets whether users can edit the field of extra pages'></td>
	<td width=20><img src=i/cad.gif border=0 title='Sets whether users can renomar pages'></td>
	<td width=20><img src=i/find.gif border=0 title='Sets whether users can edit keyword pages'></td>
	<td width=20><img src=i/hab.gif border=0 title='Sets whether the pages can be viewed'></td>
	<td width=20><img src=i/pri.gif border=0 title='Defines access to pages'></td>
	<td width=20><img src=i/red.gif border=0 title='Sets whether users can create redirects for pages'></td>
	<td width=20><img src=i/carea.gif border=0 title='Sets whether users can change the view of the page layout'></td>
	<td width=20><img src=i/ord.gif border=0 title='Sets whether users can change the order of pages'></td>
	<td width=20><img src=i/niv.gif border=0 title='Sets whether users can change the page owner'></td>
	<td width=20><img src=i/usu.gif border=0 title='Sets whether users can change the users who have access to a page'></td>
	<td width=20><img src=i/cat.gif border=0 title='Sets whether users can change the file folder'></td>
	<td width=20><img src=i/idi.gif border=0 title='Define which users can change the languages ​​of pages'></td>
	<td width=20><img src=i/del.gif border=0 title='Define which users can delete pages in the system'></td>
	<td width=20>&nbsp;</td>
	<td width=26><img src=i/edit.gif border=0 title='Sets whether users can edit the Properties menu'></td>
	<td width=20>&nbsp;</td>
	<td width=20><img src=i/edit.gif border=0 title='Sets whether users can edit the systems used pages'></td>
	<td width=20><img src=i/niv.gif border=0 title='Sets whether users can change the owners of systems'></td>
	<td width=20><img src=i/del.gif border=0 title='Define which users can delete systems'></td>
	<td width=20>&nbsp;</td>
	<td align=left width=160><b>Root</b></td>
	<td width=20><img src=i/prod.gif border=0 align=absmiddle title='Displays the System Setup button'></td>
	<td width=20><img src=i/usu.gif border=0 title='Displays configuration button users'></td>
	<td width=20><img src=i/cae.gif border=0 title='Displays configuration levels button'></td>
	<td width=20><img src=i/est.gif border=0 align=absmiddle title='Displays visitation statistics button'></td>
	<td width=20><img src=i/enq.gif border=0 align=absmiddle title='Displays control button polls'></td>
	<td width=20><img src=i/ban.gif border=0 align=absmiddle title='Displays the banner manager button'></td>
	<td width=20><img src=i/log.gif border=0 align=absmiddle title='Displays the log button users'></td>
	<td width=20><img src=i/tab.gif border=0 align=absmiddle title='Displays the MySQL button'></td>
	<td width=20><img src=i/mai.gif border=0 align=absmiddle title='Displays the Log button E-mail'></td>
	<td width=20><b>&nbsp;</b></td>
</tr>
$m
<tr><td colspan=37 class=cor3 align=center class=controle>".($total>$e?($i==0?'x':"<a href=?i=".($i-$e)."&id=$id class=controle><b>Back</b></a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)."&id=$id class=controle><b>Next</b></a>"):'<BR>')."	</td></tr><tr><td colspan=37 class=cor4 align=center><br></td></tr></table>


<!--Novo-->
<table cellspacing=0 border=0 class='camada cor3' id=nite>
	<tr name=barra id=barra>
		<td><b>New level</b></td>
		<td align=right><img src=i/clo.gif id=fecha name=fecha></td>
	</tr><tr>
		<td colspan=2><b>Name: </b><input type=text id=cnome class=text size=17><img align=absmiddle src=i/save.gif onclick=posta(0,$('cnome').value,'novo')></td>
	</tr>
</table>

$final_pagina";		
}else{
	header("Location : index.php");
}
?>
