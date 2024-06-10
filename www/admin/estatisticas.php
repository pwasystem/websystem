<?PHP
include("dados.php");
error_reporting(E_ALL);
if ($l>0&&$per[19]==1){
	//desenha gráfico de visitas semanais
	function grafico($titulo,$cor,$v0,$v1,$v2,$v3,$v4,$v5,$v6) {		
		$valor=explode(',',"$v0,$v1,$v2,$v3,$v4,$v5,$v6");
		$max=max($valor);	
		$max==0 ? $max=1 : null;
		header("Content-type: image/png");
		$im = imagecreate(100, 80);
		imagecolorallocate($im, 254, 254, 254);
		$cores[0]=imagecolorallocate($im, 254, 0, 0);   
		$cores[1]=imagecolorallocate($im, 0, 0, 254);
		$cores[2]=imagecolorallocate($im, 0, 128, 0);
		$cores[3]=imagecolorallocate($im, 138, 43, 226);
		$cores[4]=imagecolorallocate($im, 254, 140, 0);
		$preto=imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle ($im, 8, (65-intval(50*($valor[0]/$max))), 15, 65, $cores[$cor]);
		imagefilledrectangle ($im, 20, (65-intval(50*($valor[1]/$max))), 27, 65, $cores[$cor]);
		imagefilledrectangle ($im, 32, (65-intval(50*($valor[2]/$max))), 39, 65, $cores[$cor]);
		imagefilledrectangle ($im, 44, (65-intval(50*($valor[3]/$max))), 51, 65, $cores[$cor]);
		imagefilledrectangle ($im, 56, (65-intval(50*($valor[4]/$max))), 63, 65, $cores[$cor]);
		imagefilledrectangle ($im, 68, (65-intval(50*($valor[5]/$max))), 75, 65, $cores[$cor]);
		imagefilledrectangle ($im, 80, (65-intval(50*($valor[6]/$max))), 87, 65, $cores[$cor]);
		imagestring($im, 3, 10, 0, $titulo, $preto);
		imagestring($im, 2, 10, 65, 'S M T W T F S', $preto);
		imagepng($im);
		imagedestroy($im);
		die();
	}
	isset($_GET['grafico'])?grafico($_GET['grafico'],$_GET['cor'],$_GET['v0'],$_GET['v1'],$_GET['v2'],$_GET['v3'],$_GET['v4'],$_GET['v5'],$_GET['v6']):null;
	//desenha gráfico por periodo
	function grafico_p($titulo,$cor,$v0,$v1,$v2,$v3) {
		$valor=explode(',',"$v0,$v1,$v2,$v3");
		$max=max($valor);	
		$max==0?$max=1:null;
		header("Content-type: image/png");
		$im=imagecreate(60,80);
		imagecolorallocate($im, 255, 255, 255);
		$cores[0]=imagecolorallocate($im, 139, 0, 139);   
		$cores[1]=imagecolorallocate($im, 106, 90, 205);
		$cores[2]=imagecolorallocate($im, 95, 158, 160);
		$cores[3]=imagecolorallocate($im, 0, 139, 139);
		$cores[4]=imagecolorallocate($im, 46, 139, 87);
		$cores[5]=imagecolorallocate($im, 128, 128, 0);
		$cores[6]=imagecolorallocate($im, 205, 133, 63);
		$preto=imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle ($im, 8, (65-intval(50*($valor[0]/$max))), 15, 65, $cores[$cor]);
		imagefilledrectangle ($im, 20, (65-intval(50*($valor[1]/$max))), 27, 65, $cores[$cor]);
		imagefilledrectangle ($im, 32, (65-intval(50*($valor[2]/$max))), 39, 65, $cores[$cor]);
		imagefilledrectangle ($im, 44, (65-intval(50*($valor[3]/$max))), 51, 65, $cores[$cor]);
		imagestring($im, 3, 5, 0, $titulo, $preto);
		imagestring($im, 2, 10, 65, 'D R A N', $preto);
		imagepng($im);
		imagedestroy($im);
		die();
	}
	isset($_GET['grafico_p'])?grafico_p($_GET['grafico_p'],$_GET['cor'],$_GET['v0'],$_GET['v1'],$_GET['v2'],$_GET['v3']):null;
	//desenha grafico de linha
	function linhas($titulo,$valores) {
		error_reporting(0);
		$largura=800;	
		header("Content-type: image/png");
		$im=imagecreate(800,200);
		imagecolorallocate($im, 255, 255, 255);
		$verme=imagecolorallocate($im, 255, 0, 0);
		$preto=imagecolorallocate($im, 100, 100, 100);
		$cinza=imagecolorallocate($im, 200, 200, 200);
		$azul=imagecolorallocate($im, 0, 150, 100);
		$dados=explode('|',$valores);
		$max=0;
		$const=intval($largura/13);
		foreach($dados as $chave => $dado){
			$dad=explode(':',$dado);
			if ($dad[1]>$max) $max=$dad[1];
			$valor[$chave]['valor']=$dad[1];
			$valor[$chave]['x']=$const*($chave+1);
			imagestring($im, 1, $const*($chave+1), 185 , $dad[0], $preto);
		}
		$fm='';
		for($ii=0;$ii<strlen($max)-1;$ii++)$fm.='0';
		$max=(substr($max,0,1)+1).$fm;
		for ($ii=30;$ii<=180;$ii=$ii+15){
			imagestring($im, 2, 5, $ii-7, ((180-$ii)*$max)/150, $preto);
			imageline($im, 40, $ii, $largura, $ii, $cinza);
		}
		foreach ($valor as $chavo => $rolav){
			imagestring($im, 1, $rolav['x'], 170-($rolav['valor']*150/$max) , $rolav['valor'] , $azul);
			if($chavo<11){
				imageline($im, $rolav['x']+12, 180-($rolav['valor']*150/$max) , $valor[$chavo+1]['x']+12, 180-($valor[$chavo+1]['valor']*150/$max), $verme);
			}
		}	
		imagestring($im, 3, 5, 0, $titulo, $preto);
		imagepng($im);
		imagedestroy($im);	
		die();	
	}
	isset($_GET['linhas'])?linhas($_GET['linhas'],$_GET['valores']):null;
	
	$colspan="";
	if ($z==1) {//Visualizações em categorias
		$categorias='';		
		foreach($mysql->query("select view,nome,id from arquivos where habilitado=1 order by view desc limit 0,100",PDO::FETCH_NUM) as $a)$categorias.="<tr class=".($k=$k=='cor2'?'cor1':'cor2')." id=flip name=flip ><td><a href=../index.php?id=$a[2] target=_blank>$a[1]</a></td><td align=right>$a[0]</td></tr>";
		$colspan="colspan=2";
		$meio_pagina="<tr class=cor3><td><b>Page</b></td><td width=100 align=right><b>Views</b></td></tr>$categorias<tr><td $colspan align=center class=cor3><a href=?z=0><b>&nbsp;</b></a></td></tr>";
	} else if ($z==2) {//grafico semana
		$semanal='';
		foreach($mysql->query("select date_format(semana_data,'%d/%m/%y'),semana from estatistica where id!=1 order by id desc limit 12",PDO::FETCH_NUM) as $a) $semanal="$a[0]:$a[1]|".$semanal;		
		$mensal='';
		foreach($mysql->query("select sum(semana),date_format(dia_data,'%m/%y') from estatistica group by date_format(dia_data,'%m/%y')  order by id desc limit 12",PDO::FETCH_NUM) as $a) $mensal="$a[1]:$a[0]|".$mensal;	
		$meio_pagina="<tr>
			<td><img title='Data not available' src='estatisticas.php?linhas=Weekly visits - last quarter&valores=".substr($semanal,0,-1)."' border=0></td>
		</tr><tr>
			<td><br><img title='Data not available' src='estatisticas.php?linhas=Monthly visits - last 12 months&valores=".substr($mensal,0,-1)."' border=0><br><br></td>
		</tr>";	
	}else {
		$ds=isset($_GET['ds'])?$_GET['ds']:1;
		extract($mysql->query("select visitas,view from dados")->fetch(PDO::FETCH_ASSOC)); //Visitas e views
		@extract($mysql->query("select view view_pagina,nome from arquivos where view>0 order by view desc limit 1")->fetch(PDO::FETCH_ASSOC)); //Página		
		$a=$mysql->query("select dia, semana, mes, ano, domingo_madrugada, domingo_manha, domingo_tarde, domingo_noite , segunda_madrugada, segunda_manha, segunda_tarde, segunda_noite, terca_madrugada, terca_manha, terca_tarde, terca_noite, quarta_madrugada, quarta_manha, quarta_tarde, quarta_noite, quinta_madrugada, quinta_manha, quinta_tarde, quinta_noite, sexta_madrugada, sexta_manha, sexta_tarde, sexta_noite, sabado_madrugada, sabado_manha, sabado_tarde, sabado_noite, dia_temp, semana_temp, mes_temp, ano_temp, domingo, segunda, terca, quarta, quinta, sexta, sabado, date_format(semana_data,'%d/%m/%Y'),date_format(dia_data,'%d/%m/%Y') from estatistica where id=$ds")->fetch(PDO::FETCH_NUM); //Visitas Diarias, Semanais, Mensais e Anuais		
		if ($a[36]==0) {
			$a[36]=$a[4]+$a[5]+$a[6]+$a[7];
			$mysql->exec("update estatistica set domingo=$a[36] where id=1");
		}
		if ($a[37]==0) {
			$a[37]=$a[8]+$a[9]+$a[10]+$a[11];
			$mysql->exec("update estatistica set domingo=$a[37] where id=1");
		}
		if ($a[38]==0) {
			$a[38]=$a[12]+$a[13]+$a[14]+$a[15];
			$mysql->exec("update estatistica set domingo=$a[38] where id=1");
		}
		if ($a[39]==0) {
			$a[39]=$a[16]+$a[17]+$a[18]+$a[19];
			$mysql->exec("update estatistica set domingo=$a[39] where id=1");
		}
		if ($a[40]==0) {
			$a[40]=$a[20]+$a[21]+$a[22]+$a[23];
			$mysql->exec("update estatistica set domingo=$a[40] where id=1");
		}
		if ($a[41]==0) {
			$a[41]=$a[24]+$a[25]+$a[26]+$a[27];
			$mysql->exec("update estatistica set domingo=$a[41] where id=1");
		}
		if ($a[42]==0) {
			$a[42]=$a[28]+$a[29]+$a[30]+$a[31];
			$mysql->exec("update estatistica set domingo=$a[42] where id=1");
		}
		extract($mysql->query("select max(id) maximo from estatistica")->fetch(PDO::FETCH_ASSOC));
		$eq=$ds==1?"select id from estatistica order by id desc":"select id from estatistica where id<$ds order by id desc";
		$e=$mysql->query($eq)->fetch(PDO::FETCH_NUM);
		$meio_pagina="<tr><td>
<table border=0 cellspacing=0 cellpadding=2 align=center width=100%>
	<tr>
		<td colspan=2 class=cor3 align=center><b>".($ds==1?'Current week':"Data collected from $a[43] to $a[44]")."</b></td>
	</tr><tr>	
		<td>The current number of visits is is <b>$visitas</b>.
		<br>The current number of views is <b>$view</b>.
		<br>The <a href=?z=1>page</a> <b>".(isset($nome)?$nome:'')."</b> have <b>".(isset($view_pagina)?$view_pagina:'')."</b> views.</td>
		<td align=right>".(empty($e[0])||$ds==2?'':"<img src=i/back.gif align=absmiddle style=cursor:hand title='View previous week' onclick=\"window.location='?ds=$e[0]'\">").($ds==1?'':"<img src=i/go.gif align=absmiddle style=cursor:hand title='View next week'  onclick=\"window.location='?ds=".($maximo<($e[0]+2)?1:($e[0]+2))."'\">")."</td>
	</tr>
</table>
<br><br>
<table border=1 bordercolor=silver cellpadding=0 cellspacing=0 align=center>
	<tr align=center class=cor4>
		<td class=cor4 width=150>&nbsp;</td>
		<td width=100><b>Last</b></td>
		<td width=100><b>Current</b></td>
	</tr><tr align=center>
		<td class=cor4><b>Day</b></td>
		<td>$a[0]</td>
		<td>$a[32]</td>
	</tr><tr align=center>
		<td class=cor4><b>Week</b></td>
		<td>$a[1]</td>
		<td>$a[33]</td>
	</tr><tr align=center>
		<td class=cor4><b>Month</b></td>
		<td>$a[2]</td>
		<td>$a[34]</td>
	</tr><tr align=center>
		<td class=cor4><b>Year</b></td>
		<td>$a[3]</td>
		<td>$a[35]</td>
	</tr>
</table><br><br>
<table border=1 bordercolor=silver cellpadding=0 cellspacing=0 align=center>
	<tr class=cor4 align=center>
		<td width=80 class=cor4>&nbsp;</td>
		<td width=100><b>Sunday</b></td>
		<td width=100><b>Monday</b></td>
		<td width=100><b>Tuesday</b></td>
		<td width=100><b>Wednesday</b></td>
		<td width=100><b>Thursday</b></td>
		<td width=100><b>Friday</b></td>
		<td width=100><b>Saturday</b></td>
	</tr><tr align=center>
		<td class=cor4><b>Daybreak</b></td>
		<td>$a[4]</td>
		<td>$a[8]</td>
		<td>$a[12]</td>
		<td>$a[16]</td>
		<td>$a[20]</td>
		<td>$a[24]</td>
		<td>$a[28]</td>
	</tr><tr align=center>
		<td class=cor4><b>Morning</b></td>
		<td>$a[5]</td>
		<td>$a[9]</td>
		<td>$a[13]</td>
		<td>$a[17]</td>
		<td>$a[21]</td>
		<td>$a[25]</td>
		<td>$a[29]</td>
	</tr><tr align=center>
		<td class=cor4><b>Afternoon</b></td>
		<td>$a[6]</td>
		<td>$a[10]</td>
		<td>$a[14]</td>
		<td>$a[18]</td>
		<td>$a[22]</td>
		<td>$a[26]</td>
		<td>$a[30]</td>
	</tr><tr align=center>
		<td class=cor4><b>Night</b></td>
		<td>$a[7]</td>
		<td>$a[11]</td>
		<td>$a[15]</td>
		<td>$a[19]</td>
		<td>$a[23]</td>
		<td>$a[27]</td>
		<td>$a[31]</td>
	</tr><tr align=center>
		<td class=cor4><b>Total</b></td>
		<td>$a[36]</td>
		<td>$a[37]</td>
		<td>$a[38]</td>
		<td>$a[39]</td>
		<td>$a[40]</td>
		<td>$a[41]</td>
		<td>$a[42]</td>
	</tr>
</table><br>
<table border=0 align=center>
	<tr>
		<td colspan=5 align=center><b>Graphics weekly visitations</b></td>
	</tr><tr>
		<td><img src='estatisticas.php?grafico=Total&cor=0&v0=$a[36]&v1=$a[37]&v2=$a[38]&v3=$a[39]&v4=$a[40]&v5=$a[41]&v6=$a[42]'></td>
		<td><img src='estatisticas.php?grafico=Daybreak&cor=1&v0=$a[4]&v1=$a[8]&v2=$a[12]&v3=$a[16]&v4=$a[20]&v5=$a[24]&v6=$a[28]'></td>
		<td><img src='estatisticas.php?grafico=Morning&cor=2&v0=$a[5]&v1=$a[9]&v2=$a[13]&v3=$a[17]&v4=$a[21]&v5=$a[25]&v6=$a[28]'></td>
		<td><img src='estatisticas.php?grafico=Afternoon&cor=3&v0=$a[6]&v1=$a[10]&v2=$a[14]&v3=$a[18]&v4=$a[22]&v5=$a[26]&v6=$a[30]'></td>
		<td><img src='estatisticas.php?grafico=Night&cor=4&v0=$a[7]&v1=$a[11]&v2=$a[15]&v3=$a[19]&v4=$a[23]&v5=$a[27]&v6=$a[31]'></td>
	</tr>
</table><table border=0 align=center>
	<tr>
		<td colspan=7 align=center><b>Visitations Day - Daybreak/Morning/Afternoon/Night</b></td>
	</tr><tr>
		<td><img src='estatisticas.php?grafico_p=Sunday&cor=0&v0=$a[4]&v1=$a[5]&v2=$a[6]&v3=$a[7]'></td>
		<td><img src='estatisticas.php?grafico_p=Monday&cor=1&v0=$a[8]&v1=$a[9]&v2=$a[10]&v3=$a[11]'></td>
		<td><img src='estatisticas.php?grafico_p=Tuesday&cor=2&v0=$a[12]&v1=$a[13]&v2=$a[14]&v3=$a[15]'></td>
		<td><img src='estatisticas.php?grafico_p=Wednesday&cor=3&v0=$a[16]&v1=$a[17]&v2=$a[18]&v3=$a[19]'></td>
		<td><img src='estatisticas.php?grafico_p=Thursday&cor=4&v0=$a[20]&v1=$a[21]&v2=$a[22]&v3=$a[23]'></td>
		<td><img src='estatisticas.php?grafico_p=Friday&cor=5&v0=$a[24]&v1=$a[25]&v2=$a[26]&v3=$a[27]'></td>
		<td><img src='estatisticas.php?grafico_p=Saturday&cor=6&v0=$a[28]&v1=$a[29]&v2=$a[30]&v3=$a[31]'></td>
	</tr>
</table><br>
</td></tr>";
	}
	echo "$inicio_pagina
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr class=cor4>
			<td $colspan>&nbsp;<img src=i/est.gif border=0 align=absmiddle title='Visit history' onclick=\"window.location='?z=".($z==2?0:2)."'\" style=cursor:hand>&nbsp;<b>Statistics</b></td>
		</tr>$meio_pagina<tr class=cor4>
			<td align=center $colspan>".($z==0?'<br>':'<a href=?z=0><b>Back</b></a>')."</td>
		</tr></table>
	$final_pagina";
}else{
	header("Location : index.php");
}

?> 