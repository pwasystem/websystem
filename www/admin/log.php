<?PHP
include "dados.php";
if ($l>0&&$per[24]==1){
	if ($z==1){
		//apaga dados
		if(isset($_GET['tru']))$mysql->exec('TRUNCATE time');
		//altera captura
		isset($_GET['timex'])?$mysql->exec("update dados set script=".($_GET['timex']=='true'?1:0)):null;
		//le dados
		extract($mysql->query("select count(data) total from time")->fetch(PDO::FETCH_ASSOC));
		foreach($mysql->query("select time.ip,time.data,time.pagina,time.tempo,arquivos.nome from time inner join arquivos on time.pagina=arquivos.id  order by time.data desc limit $i,$e",PDO::FETCH_NUM) as $a){
			$m.="<tr id=flip name=flip class=".($k=$k=='cor2'?'cor1':'cor2')." >
				<td><a href=../index.php?id=$a[2] target=time>$a[4]</a></td>
				<td>$a[3]</td>
				<td>$a[1]</td>
				<td>$a[0]</td>
			</tr>";
		}		
		$m="<table border=0 width=100% cellpadding=0 cellspacing=0>
		<tr class=cor3>
			<td colspan=3 height=15><input type=checkbox onclick=\"carrega('?z=1&timex='+this.checked,'jax')\" ".($script==0?'':'checked')."> Check this option to enable the capture of the loading time of the pages.</td>
			<td align=right><img src=i/del.gif title='Clean data' style=cursor:hand onclick=\"avisar('Really delete the Log views.<BR><BR><INPUT type=button class=button value=Sim onclick=window.location=\'?z=1&tru\'> <INPUT type=button class=button value=Não onclick=fechaviso()>')\"></td>
		</tr><tr class=cor3>
			<td><b>Page</b></td>
			<td><b>Time</b> (s)</td>
			<td><b>Date</b></td>
			<td><b>IP</b></td>
		</tr>$m<tr>
		<td colspan=5 class=cor3 align=center class=controle>".($total>$e?($i==0?'x':"<a href=?i=".($i-$e)."&z=1 class=controle>Back</a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)."&z=1 class=controle>Next</a>"):'<BR>')."</td>
	</tr></table>";
	} else {
		if(isset($_GET['zer']))$mysql->exec('TRUNCATE log');
		extract($mysql->query("select count(data) total from log")->fetch(PDO::FETCH_ASSOC));		
		foreach($mysql->query("select * from log order by data desc limit $i,$e",PDO::FETCH_NUM) as $a){
			$m.="<tr id=flip name=flip class=".($k=$k=='cor2'?'cor1':'cor2')." >
				<td>$a[0]</td>
				<td>$a[1]</td>
				<td>$a[4]</td>
				<td>".htmlspecialchars($a[2])."</td>
				<td colspan=2>".htmlspecialchars($a[3])."</td>
			</tr>";
		}
		$m="<table border=0 width=100% cellpadding=0 cellspacing=0><tr class=cor3>
		<td><b>Data</b></td>
		<td><b>IP</b></td>
		<td><b>Reverse IP</b></td>
		<td><b>User</b></td>
		<td><b>Password</b></td>
		<td align=right><img src=i/del.gif style=cursor:hand  onclick=\"avisar('Really delete the access log.<BR><BR><INPUT type=button class=button value=Yes onclick=window.location=\'?z=0&zer\'> <INPUT type=button class=button value=No onclick=fechaviso()>')\"></td>
	</tr>$m<tr>
		<td colspan=6 class=cor3 align=center class=controle>".($total>$e?($i==0 ? 'x':"<a href=?i=".($i-$e)." class=controle><b>Back</b></a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)." class=controle><b>Next</b></a>"):'<BR>')."</td>
	</tr></table>";
	}	
	//Escreve página
	echo "$inicio_pagina
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr class=cor4>
		<td>&nbsp;<img src=i/log.gif border=0 align=absmiddle title='".($z==1?'Show login attempts':'Show the load time of pages')."' style=cursor:hand onclick=\"window.location='?z=".($z==1?0:1)."'\">&nbsp;<b>Log</b></td>
	</tr><tr>
		<td>$m</td>
	</tr><tr>
		<td class=cor4 align=center><br></td>
	</tr>
</table>$final_pagina";		
}else{
	header("Location : index.php");
}
?>