<?PHP
include "dados.php";
if ($l>0&&$per[20]==1){
	//Apaga e executa updates.
	isset($_GET['deleta'])?$mysql->exec("DELETE FROM enquete WHERE id=$id"):null;
	isset($_GET['res'])?$mysql->exec("update enquete set resposta='$_GET[res]',valor='$_GET[val]' where id=$id"):null;
	isset($_GET['pergunta'])?$mysql->exec("update enquete set pergunta='$_GET[pergunta]' where id=$id"):null;
	//retorna nada
	isset($_GET['res'])||isset($_GET['pergunta'])?die():'';
	//Executa pesquisa
	$w=isset($_GET['w'])?"where pergunta like '%$_GET[w]%'":'';
	extract($mysql->query("select count(id) total from enquete $w")->fetch(PDO::FETCH_ASSOC));	
	foreach($mysql->query("select id,pai,pergunta,resposta,valor from enquete $w order by data desc limit $i,$e") as $a){
		$m.="<tr name=flip id=flip class=".($k=$k=='cor2'?'cor1':'cor2').">
			<td><span id=pergunta$a[0]><a href=\"javascript:c($a[0],'pergunta','".rawurlencode(addslashes($a[2]))."',30,150,1)\">$a[2]</a></span></td>
			<td align=right><img src=i/nlist.gif name=ct$a[0] align=absmiddle style=cursor:hand onclick=\"respos($('va$a[0]').value,event)\"> <input id=va$a[0] type=hidden value='$a[0]&".substr($a[3],0,-1)."&".substr($a[4],0,-1)."'></td>
			 <td align=right>".($a[1]==0 ? "<a href=\"javascript:posta($a[0],0,'apaga')\" ><img src=i/del.gif border=0></a>":'&nbsp;')."</td>
		</tr>";
	}
	//Escreve página
	echo "$inicio_pagina	
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr class=cor4>
		<td colspan=5><b><a href=\"javascript:w_usuario(0,0)\">&nbsp;<img src=i/enq.gif border=0 align=absmiddle title='Manager of Polls'>&nbsp;<b>Polls</b></a> <span id=wdor></span></b></td>
	</tr><tr class=cor3>
		<td width=400><b>Question</b></td>
		<td width=20>&nbsp;</td>
		<td width=20>&nbsp;</td>
	</tr>$m<tr class=cor3>
		<td colspan=5 align=center class=controle>".($total>$e?($i==0?'x':"<a href=?i=".($i-$e)."&id=$id class=controle>Back</a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)."&id=$id class=controle>Next</a>"):'<BR>')."</td>
	</tr><tr>
		<td colspan=5 class=cor4 align=center><br></td>
	</tr>
</table>
<script>
function respos(dta,event){
	ver('barra1',event)
	f=document.formulario
	va=dta.split('&')	
	f.ddi.value=va[0]
	vb=va[1].split('|')
	vc=va[2].split(',')
	ret=''
	for (ii=0;ii<vb.length;ii++){
		ret=ret+'<tr><td align=right><input type=text class=text name=resposta[] value=\"'+vb[ii]+'\" size=50></td><td width=57><input type=text class=text name=valor[] value=\"'+vc[ii]+'\" size=5></td></tr>'
	}
	respo.innerHTML='<table border=0 width=100% cellspacing=0>'+ret+'</table>'	
}
function posr(){
	rot=''
	vot=''	
	f=document.formulario
	for (ii=0;ii<document.getElementsByName('resposta[]').length;ii++){		
		rot=rot+document.getElementsByName('resposta[]')[ii].value+'|'
		vot=vot+document.getElementsByName('valor[]')[ii].value+','
	}	
	di=f.ddi.value
	carrega('?id='+di+'&res='+rot+'&val='+vot)
	$('va'+di).value=di+'&'+rot.substring(0,rot.length-1)+'&'+vot.substring(0,vot.length-1)
	ver_n('barra1')
	return false
}
</script>
<table border=0 cellspacing=0 id=barra1 class='camada cor3'width=350>
<tr id=barra name=barra width=300>
	<td><b>Answers</b></td>
	<td align=right><img src=i/clo.gif style=cursor:hand name=fecha></td>
</tr><form name=formulario onsubmit='return posr()'><input type=hidden name=ddi><tr align=center>
	<td colspan=2 align=right> <img src=i/save.gif onclick=posr() title='Save' style=cursor:hand></td>
</tr><tr align=center>
	<td colspan=2 id=respo></td>
</tr></form>
</table>
$final_pagina";		
}else{
	header("Location : index.php");
}