<?PHP
include "dados.php";
if ($l>0&&$per[23]==1&&$per[5]==1){
	//Updates de sistema
	isset($_GET['deleta'])?$mysql->exec("DELETE FROM menu WHERE id=$id"):null;
	isset($_GET['habilitado'])?$mysql->exec("UPDATE menu SET habilitado=$_GET[habilitado] WHERE id=$id"):null;
	isset($_GET['nome'])? (!empty($_GET['nome'])?$mysql->exec("UPDATE menu SET nome='$_GET[nome]' WHERE id=$id"):null):null;
	isset($_GET['novo'])? (!empty($_GET['novo'])?$mysql->exec("INSERT INTO menu(nome,fonte) VALUES('$_GET[novo]','return \"ok\";')"):null):null;
	$w=empty($w)?'':"WHERE nome LIKE '%$_GET[w]%'";
	extract($mysql->query("SELECT count(id) total FROM menu $w")->fetch(PDO::FETCH_ASSOC));		
	foreach($mysql->query("SELECT id,nome,habilitado FROM menu $w LIMIT $i,$e",PDO::FETCH_NUM) as $a){
		$usado='';
		@extract($mysql->query("SELECT id usado FROM arquivos WHERE menu=$a[0] LIMIT 1")->fetch(PDO::FETCH_ASSOC));
		$m.="<TR id=flip name=flip class=".($k=$k=='cor2'?'cor1':'cor2').">
				<TD><span id=nome$a[0]><a href=\"javascript:c($a[0],'nome','".rawurlencode(addslashes($a[1]))."',30,30,1)\">$a[1]</a></span></TD>
				<TD><a href=editor.php?tepo=3&i=$i&id=$a[0]&campo=Menu><img src=i/edit.gif border=0></a></TD>
				<TD><input type=checkbox name=habilitado onclick=posta($a[0],".($a[2]==0?1:0).",'habilitado') ".($a[2]==0?'':'checked')."></TD>
				<TD align=center>".(!empty($usado)||$a[0]==1||$a[0]==2 ?'&nbsp;':"<a href=\"javascript:posta($a[0],0,'apaga')\" ><img src=i/del.gif border=0></a>")." </TD>
			</TR>";
	}
	//escreve página
	echo "$inicio_pagina
		<TABLE border=0 cellspacing=0 cellpadding=0 width=100%>
			<TR class=cor4>		
				<TD>&nbsp;<img onclick=\"ver('nite',event)\" src=i/men.gif border=0 align=absmiddle title='Create new menu'><B> <a href=\"javascript:w_usuario(0,0)\"><B>Menus</B></a> <span id=wdor></span></B></TD>
			</TR><TR>
				<TD><TABLE border=0 cellpadding=0 cellspacing=0 width=100%>
					<TR class=cor3>
						<TD><B>Name</B></TD>
						<TD width=20>&nbsp;</TD>
						<TD width=20><img src=i/hab.gif></TD>
						<TD width=20>&nbsp;</TD>	
					</TR>$m<TR>
						<TD align=center colspan=4 class='cor3 controle'>".($total>$e?($i==0 ? 'x':"<a href=?i=".($i-$e)."&id=$id class=controle><B>Back</B></a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)."&id=$id class=controle><B>Next</B></a>"):'<BR>')."</TD>
					</TR>
				</TABLE></TD>
			</TR><TR>
				<TD class=cor4 align=center><BR></TD>
			</TR>
		</TABLE>	
		<!--Novo-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=nite>
			<TR name=barra id=barra>
				<TD><B>New menu</B></TD>
				<TD align=right><img src=i/clo.gif id=fecha name=fecha></TD>
			</TR><TR>
				<TD colspan=2><B>Name: </B><input type=text id=cnome class=text size=17><img align=absmiddle src=i/save.gif onclick=posta(0,$('cnome').value,'novo')></TD>
			</TR>
		</TABLE>		
		$final_pagina";
}else{
	header("Location : index.php");
}