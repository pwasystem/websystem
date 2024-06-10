<?PHP
include "dados.php";
error_reporting(E_ALL);
if ($l>0&&$per[34]==1){
	if(isset($_GET['maitu'])){
		extract($_POST);
		$mail_en=isset($_POST['mail_en'])?1:0;
		$mail_lo=isset($_POST['mail_lo'])?1:0;
		$mysql->exec("update dados set mail_de='$mail_de', mail_us='$mail_us', mail_se='$mail_se', mail_sm='$mail_sm', mail_po='$mail_po', mail_en='$mail_en', mail_lo='$mail_lo'");
	}
	if(isset($_GET['zer']))$mysql->exec('truncate email');
	extract($mysql->query("select count(id) total from email")->fetch(PDO::FETCH_ASSOC));	
	foreach($mysql->query("select id,server,date_format(data,'%Y%m%d%H%i'),ip,reverso,log from email order by id desc limit $i,$e") as $a){
		$c='';
		foreach($a as $g) $c.="$g|";
		$m.="<tr id=flip name=flip class=".($k=$k=='cor2'?'cor1':'cor2')." >			
			<td width=80%>$a[1] - $a[2] - $a[3] - $a[4]</td>
			<td align=right><input type=hidden id=info$a[0] value='".($a[5])."'><img src=i/pag.gif border=0 style=cursor:hand title='Detalhes do envio' onclick=\"logm(document.getElementById('info$a[0]').value,event)\"></td>
		</tr>";
	}
	$m="<table border=0 width=100% cellpadding=0 cellspacing=0><tr class=cor3>
		</tr>$m<tr>
	<td class=cor3 colspan=2 align=center class=controle><b>".($total>$e?($i==0?'x':"<a href=?i=".($i-$e)." class=controle>Back</a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)." class=controle>Next</a>"):'<BR>')."</b></td>
</tr></table>";	
	//Escreve página
	echo "$inicio_pagina
<script>
function logm(dados,event){
	document.getElementById('detalhes').innerHTML=dados
	ver('barra1',event)
}
</script>
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr class=cor4>
		<td>&nbsp;<img src=i/mai.gif border=0 align=absmiddle title='Log of emails sent' style=cursor:hand onclick=\"ver('barra2',event)\">&nbsp;<b>Sending e-mail authenticated</b></td>
		<td align=right><img src=i/del.gif style=cursor:hand onclick=\"window.location='?zer'\"></td>
	</tr><tr>
		<td colspan=2>$m</td>
	</tr><tr>
		<td class=cor4 align=center colspan=2><br></td>
	</tr>
</table>$final_pagina

<!--BARRA 1 - Detalhes do envio-->
<table border=0 cellpadding=0 cellspacing=0 id=barra1 class='camada cor3' width=700>
	<tr name=barra id=barra>
		<td><b>Details</b></td>
		<td align=right><img src=i/clo.gif name=fecha></td>
	</tr></tr><tr class=cor3>
		<td colspan=2 id=detalhes>here</td>
	</tr>
</table>

<!--BARRA 1 - Configurações-->
<table border=0 cellpadding=0 cellspacing=0 id=barra2 class='camada cor3' width=400>
	<tr name=barra id=barra>
		<td><b>Settings</b></td>
		<td align=right><img src=i/clo.gif name=fecha></td>
	</tr></tr><tr class=cor3><form method=post action=?maitu>
		<td colspan=2><b>Server: <input type=text name=mail_sm class=text value='$mail_sm' maxlength=50> Port: <input type=text name=mail_po class=text value='$mail_po' maxlength=5 size=2><BR>
		E-mail: <input type=text name=mail_de class=text value='$mail_de' maxlength=50 size=37><BR>
		User: <input type=text name=mail_us class=text value='$mail_us' maxlength=50 size=11> <B>Password:</b> <input type=password name=mail_se class=text value='$mail_se' size=11 maxlength=50><BR><input type=checkbox name=mail_en ".($mail_en==1?'Checked':'')." class=radio><b>Enable sending<input type=checkbox name=mail_lo ".($mail_lo==1?'Checked':'')." class=radio>Enable log <input type=image src=i/save.gif align=absmiddle title=Change></td>
	</tr></form>
</table>
";		
}else{
	header("Location : index.php");
}
?>