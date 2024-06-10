<?PHP
include "dados.php";
if ($l>0&&$per[22]==1){	
	//gera arrays javascript
	function ls($qr){
		$r0='';
		$r1='';
		$r2='';		
		foreach($GLOBALS['mysql']->query($qr) as $s){
			$r0.="$s[0],";
			$r1.="$s[1],";
			$r2.="<OPTION value=$s[0]>$s[1]";
		}
		return substr($r0,0,-1)."|".substr($r1,0,-1)."|".$r2;
	}
	//Atualizar
	isset($_GET['novo'])?(empty($_GET['novo'])?null:$mysql->exec("INSERT INTO sistemas(nome,nivel) VALUES('$_GET[novo]','$n,')")):null;
	isset($_GET['nome'])?(empty($_GET['nome'])?null:$mysql->exec("UPDATE sistemas SET nome='$_GET[nome]' WHERE id=$id")):null;
	isset($_GET['nome'])?die():'';
	isset($_GET['deleta'])&&$id!=1?$mysql->exec("DELETE FROM sistemas WHERE id=$id"):null;
	isset($_GET['grupo'])?$mysql->exec("UPDATE sistemas SET nivel='".(empty($_GET['grupo'])?'0,':$_GET['grupo'])."' WHERE id=$id"):null;
	//Listar			
		$nl=explode('|',ls("SELECT id,nome FROM nivel ORDER BY nome"));		
		$w=empty($w)?'':"AND nome LIKE '%$w%'";
		$s=$n==1?'':"AND CONCAT(',',nivel) LIKE '%,$n,%'";
		extract($mysql->query("SELECT COUNT(id) total FROM sistemas WHERE 1=1 $w $s")->fetch(PDO::FETCH_ASSOC));		
		foreach($mysql->query("SELECT id,nome,nivel FROM sistemas WHERE 1=1 $w $s ORDER BY nome LIMIT $i,$e",PDO::FETCH_NUM) as $a){
			$usado='';
			@extract($mysql->query("SELECT id usado FROM arquivos WHERE tipo=0 AND fonte='$a[0]'")->fetch(PDO::FETCH_ASSOC));
			$m.="<TR align=center id=flip name=flip class=".($k=$k=='cor1'?'cor2':'cor1').">
	<TD align=left><span id=nome$a[0]><A href=\"javascript:c($a[0],'nome','$a[1]',20,30,1)\">$a[1]</a></span></TD>
	".($per[29]==1?"<TD><IMG title='Edit levels of users' src=i/niv.gif border=0 style=cursor:hand onclick=\"niv('$a[2]',$a[0],event)\"></TD>":"")."
	<TD><A href=editor.php?i=$i&id=$a[0]&campo=fonte&tepo=2><IMG src=i/edit.gif title='Edit source code of the system' border=0></A></TD>
	<TD><A href=editor.php?i=$i&id=$a[0]&campo=anexo&tepo=2><IMG src=i/edit.gif title='Edit system administration' border=0></A></TD>
	".($per[30]==1?"<TD width=20>".(empty($usado)?"<A href=\"javascript:posta($a[0],$a[0],'apaga')\" ><IMG src=i/del.gif border=0></A>":'<BR>')."</TD>":'')."</TR>";
		}
	echo "$inicio_pagina
		<SCRIPT>
			n1='$nl[1]'.split(',')
			n0='$nl[0]'.split(',')
		</SCRIPT>
		<TABLE border=0 cellspacing=0 cellpadding=0 width=100%>
			<TR class=cor4>
				<TD colspan=6 height=25><A href=# onclick=\"ver('nite',event)\">&nbsp;<IMG src=i/alt.gif border=0 align=absmiddle title='Click here to create a new system'>&nbsp;</A><A href=\"javascript:w_usuario(0,0)\"><B>Systems</B></A>&nbsp;<SPAN id=wdor></SPAN></TD>
			</TR><TR>
				<TD><TABLE border=0 cellpadding=0 cellspacing=0 width=100%>
					<TR class=cor3 align=center>
						<TD align=left>&nbsp;<B>Name</b></TD>
						".($per[29]==1?"<TD><BR></TD>":"")."
						<TD width=50><IMG src=i/perso.gif title='Edit client source'></TD>
						<TD width=50><IMG src=i/prod.gif title='Edit administrator source'></TD>
						".($per[30]==1?"<TD><BR></TD>":"")."
					</TR>$m<TR>
						<TD align=center class='cor3 controle' colspan=5>".($total>$e?($i==0?'x':"<a href=?i=".($i-$e)."&id=$id class=controle>Back</a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)."&id=$id class=controle>Next</a>"):'<BR>')."</TD>
					</TR>
				</TABLE></TD>
			</TR><TR class=cor4>
				<TD align=center><BR></TD>
			</TR>
		</TABLE>
		<!--Novo-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=nite>
			<TR name=barra id=barra>
				<TD><B>New System</b></TD>
				<TD align=right><img src=i/clo.gif id=fecha name=fecha></TD>
			</TR><TR>
				<TD colspan=2><B>Name: </b><input type=text id=cnome class=text size=17><img align=absmiddle src=i/save.gif onclick=posta(0,$('cnome').value,'novo')></TD>
			</TR>
		</TABLE>
		<!--niveis-->
		<TABLE border=0 cellpadding=0 cellspacing=0 id=barra1 class='camada cor3'>
			<TR name=barra id=barra width=300>
				<TD colspan=2><B>Level</b></TD>
				<TD align=right><IMG src=i/clo.gif name=fecha></TD>
			</TR><TR class=cor3 align=center>
				<TD><B>Denied</b></TD>
				<TD><BB></TD>
				<TD><B>Allowed</b></TD>
			</TR><TR class=cor3>
				<TD><SELECT name=permite id=per style=width:140 size=15></SELECT></TD>
				<TD valign=top><IMG src=i/go.gif onclick=mn($('per').selectedIndex,0)><BR><IMG src=i/back.gif onclick=mn($('neg').selectedIndex,1)><BR><IMG src=i/save.gif onclick=\"pe='';for (ii=0;ii<$('neg').length;ii++)pe=pe+''+$('neg')[ii].value+',';posta(seguranca,pe,'grupo')\"></TD>
				<TD><SELECT name=nega id=neg style=width:140 size=15></SELECT></TD>
			</TR>
		</TABLE>		
		$final_pagina";
}else{
	header("Location : index.php");
}
?>