<?PHP
include("dados.php");
error_reporting(E_ALL);
if ($l>0&&$per[17]==1) {
	//arquivo de bkp
	$base=$dominio=='dominio'?'db.sql':substr(md5($dominio),0,10).".jpg";
	if (!file_exists("../_gravar/$base")&&file_exists("../_gravar/db.sql")) rename("../_gravar/db.sql", "../_gravar/$base") ;
	function getmicrotime(){ 
		list($usec, $sec)=explode(" ",microtime()); 
		return((float)$usec+(float)$sec); 
	} 
	$micro_time_start=getmicrotime();
	if (isset($_GET['start'])){
		$linhas=explode('|º|',base64_decode(file_get_contents(realpath("../_gravar/$base"))));
		foreach($linhas as $sql) if(!empty($sql))$mysql->exec($sql);
		if($_GET['start']==1)die("<script>window.location='master.php?start=0'</script>");
	}
	if (isset($_GET['atualiza'])) {//atualiza informações
		if(!empty($_FILES['logo']['tmp_name']))move_uploaded_file ($_FILES['logo']['tmp_name'],realpath("../_gravar/logo.png"));
		if(!empty($_FILES['icone']['tmp_name']))move_uploaded_file($_FILES['icone']['tmp_name'],realpath("../_gravar/ico.ico"));
		if(!empty($_FILES['imagem']['tmp_name']))move_uploaded_file($_FILES['imagem']['tmp_name'],realpath("../_gravar/layout.jpg"));
		$mysql->exec("update dados set titulo='$_POST[titulo]',descricao='$_POST[descricao]',palavra_chave='$_POST[palavra_chave]',editor_cor='$_POST[editor_cor]' ,nome_cor='$_POST[nome_cor]', editor_barra='$_POST[editor_barra]', editor_x='".(empty($_POST['editor_x'])?0:$_POST['editor_x'])."', editor_y='".(empty($_POST['editor_y'])?0:$_POST['editor_y'])."', editor_w='".(empty($_POST['editor_w'])?0:$_POST['editor_w'])."', editor_h='".(empty($_POST['editor_h'])?0:$_POST['editor_h'])."', nome_x='".(empty($_POST['nome_x'])?0:$_POST['nome_x'])."', nome_y='".(empty($_POST['nome_y'])?0:$_POST['nome_y'])."', fonte_cor='$_POST[fonte_cor]', erro='".(isset($_POST['erro'])?1:0)."' , zerar='".(isset($_POST['zerar'])?1:0)."' , login='".(isset($_POST['login'])?1:0)."' ,copia='".(isset($_POST['copia'])?1:0)."'");
		header("location:?");
	}
	if (isset($_GET['exporta'])) {//exporta dados
		//Apaga dados estatísticos
		if($_GET['zerar']==1){
			$mysql->query('update arquivos set view=0;update dados set visitas=0,view=0;truncate log;truncate time;truncate estatistica;insert into estatistica (id, dia, dia_temp, dia_data, semana, semana_temp, semana_data, mes, mes_temp, mes_data, ano, ano_temp, ano_data, segunda, segunda_madrugada, segunda_manha, segunda_tarde, segunda_noite, terca, terca_madrugada, terca_manha, terca_tarde, terca_noite, quarta, quarta_madrugada, quarta_manha, quarta_tarde, quarta_noite, quinta, quinta_madrugada, quinta_manha, quinta_tarde, quinta_noite, sexta, sexta_madrugada, sexta_manha, sexta_tarde, sexta_noite, sabado, sabado_madrugada, sabado_manha, sabado_tarde, sabado_noite, domingo, domingo_madrugada, domingo_manha, domingo_tarde, domingo_noite, temp, periodo) values (1,0, 0, now(), 0, 0, now(), 0, 0, now(), 0, 0, now(), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)');
		}
		$sql='';
		foreach($mysql->query("SHOW TABLES",PDO::FETCH_NUM) as $tabelas){
			$tabela=$mysql->query("SHOW CREATE TABLE $tabelas[0]")->fetch(PDO::FETCH_NUM);
			$sql.="DROP TABLE IF EXISTS $tabelas[0];|º|$tabela[1];|º|";
			foreach($pesqui=$mysql->query("SHOW FIELDS FROM $tabela[0]",PDO::FETCH_NUM) as $dados)$indices[$dados[0]]=$dados;
			foreach($consulta=$mysql->query("SELECT * FROM $tabela[0]",PDO::FETCH_ASSOC) as $registro){
				$values='';
				foreach ($registro as $indice => $campo){
					if($indices[$indice][2]=='NO'&&empty($campo)&&!is_numeric($campo)){
						if(!empty($indices[$indice][4])){
							$campo=$indices[$indice][4];
						} else {
							$tc=$indices[$indice][1];
							if(strstr($tc,'int')||strstr($tc,'decimal')||strstr($tc,'double')) {
								$campo="'0'";
							}elseif($tc=='date'){
								$campo="'0000-00-00'";
							}elseif($tc=='datetime'||$tc=='timestamp'){
								$campo="'0000-00-00 00:00:00'";
							}elseif($tc=='time'){
								$campo="'00:00:00'";
							}elseif($tc=='year'){
								$campo="'0000'";
							}else{
								$campo=" ";
							}
						}							
					}elseif(empty($campo)&&!is_numeric($campo)){
						$campo='NULL';
					}else{
						$campo="'".addslashes($campo)."'";
					}					
					$values.="$campo,";
				}
				$sql.="INSERT INTO $tabela[0] VALUES(".substr($values,0,-1).");|º|";
			}
		}
		$fi=fopen(realpath("../_gravar/$base"),"w");		
		fwrite($fi,base64_encode($sql));
		fclose($fi);	
	}
	if(isset($_GET['checar'])){
		$plus='';
		$checar=$_GET['checar'];	
		if($checar==1){
			$acao='CHECK';
			$plus='EXTENDED';
		}elseif($checar==2){
			$acao='ANALYZE';			
		}elseif($checar==3){
			$acao='OPTIMIZE';
		}elseif($checar==4||$checar==5){
			$acao='REPAIR';
			$plus=$checar==4?'EXTENDED':'use_frm';
		}
		$tabs='';
		foreach($mysql->query("SHOW TABLES",PDO::FETCH_NUM) as $d)$tabs.="$d[0],";		
		foreach($mysql->query("$acao TABLE ".substr($tabs,0,-1)." $plus",PDO::FETCH_NUM) as $b){
			$m.="<tr class=".($k=$k=='cor1'?'cor2':'cor1').">
				<td>$b[0]</td>
				<td>$b[1]</td>
				<td>$b[2]</td>
				<td>$b[3]</td>
			</tr>";
		}
		die("<link rel=stylesheet href=../_gravar/css.css>
		<table border=0 cellspacing=0 cellpadding=2>
			<tr>
				<td colspan=4 align=center class=cor3><b>Tables status</b></td>
			</tr>$m
		</table>");
	}
	$micro_time_end=getmicrotime();
	$time=$micro_time_end-$micro_time_start;
	extract($mysql->query('select now() tempo_mysql')->fetch((PDO::FETCH_ASSOC)));
	$backup_a = realpath("../_gravar/$base");
	if (file_exists($backup_a))$backup=stat($backup_a);	
	$llist='';	
	foreach($mysql->query("select idioma from idioma",PDO::FETCH_NUM) as $llis)$llist.="<option value='$llis[0]'>$llis[0]";	
	echo "$inicio_pagina<input type=hidden id=lcp value='white'>
<table border=0 class='camada cor3' cellspacing=0 id=pmc width=112>
<tr id=barra name=barra>
	<td><b>Colors</b></td>		
	<td align=right><img src=i/clo.gif style=cursor:hand onclick=\"ver_n('pmc')\"></td>
</tr><tr>
	<td colspan=2><script src=paleta.php></script></td>
</tr>
</table>
<table border=0 bordercolor=red cellspacing=0 cellpadding=1 width=100%>
<form method=post action=?atualiza=1 enctype=multipart/form-data name=f>
<tr class=cor4>
	<td colspan=2>&nbsp;<img src=i/prod.gif border=0 align=absmiddle>&nbsp;<b>System settings</b></td>
</tr><tr class=cor3>
	<td colspan=2><b>General settings</b></td>
</tr><tr class='cor2'>
	<td align=right><b>Domain:</b></td>
	<td> $dominio</td>
</tr><tr class='cor2'>
	<td align=right><b>Passkey:</b></td>
	<td> ".md5($dominio)."</td>
</tr><tr class='cor2'>
	<td align=right><b>MySQL server hour:</b></td>
	<td> $tempo_mysql</td>
</tr><tr class='cor2'>
	<td align=right><b>PHP server hour:</b></td>
	<td> ".date('Y-m-d H:i:s')."</td>
</tr><tr class='cor2'>
	<td align=right><b>Last Backup:</b></td>
	<td>".date("Y-m-d H:i:s", $backup[9])."</td>
</tr><tr class='cor2'>
	<td align=right><b>Charging time:</b></td>
	<td> ".number_format($time,2,".",'')." seconds</td>
</tr><tr class='cor2'>
	<td align=right><b>Size of the backup file:</b></td>
	<td>$backup[7] bytes</td>
</tr><tr class='cor1'>
	<td align=right><b>Title:</b></td>
	<td> <input type=text name=titulo class=text value='$titulo' size=50 maxlength=250></td>
</tr><tr class='cor1'>
	<td align=right><b>Description:</b></td>
	<td> <input type=text name=descricao class=text value='$descricao' size=50 maxlength=250></td>
</tr><tr class='cor1'>
	<td align=right><b>Keywords:</b></td>
	<td> <input type=text name=palavra_chave class=text value='$palavra_chave' size=50 maxlength=250></td>
</tr><tr class='cor1'>
	<td align=right><b>Logo:</b></td>
	<td><input type=file name=logo class=text size=1></td>
</tr><tr class='cor1'>
	<td align=right><b>Icon:</b></td>
	<td><input type=file name=icone class=text size=1></td>
</tr><tr class='cor2'>
	<td align=right><input type=checkbox name=erro ".($erro==1?'Checked':'')." class=radio></td>
	<td><b>Error reporting</b></td>
</tr><tr class=cor3>
	<td colspan=2><b>Database</b></td>
</tr><tr class='cor2'>
	<td colspan=2><b>Backup</b></td>
</tr><tr>
	<td colspan=2><table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr align=center>
			<td><img align=absmiddle style=cursor:hand onclick=\"ver('diagn_m',event)\" src=i/bd.gif  title='MySQL diagnostic tools'><b>Diagnostic</b></td>
			<td><a href=?exporta=1&zerar=$zerar><img src=i/save.gif border=0 align=absmiddle title='Export encrypted data.' align=absmiddle></a><b>Export</b> <input type=checkbox name=zerar ".($zerar==1?'Checked':'')." class=radio> Clear statistics</td>
			<td><img style=cursor:hand onclick=\"avisar('To retrieve the backup?<BR>The current data will be erased.<BR><BR><INPUT type=button class=button value=Sim onclick=window.location=\'master.php?start=1\'> <INPUT type=button class=button value=Não onclick=fechaviso()>')\" src=i/undo.gif border=0 title='Restore data backup' align=absmiddle> <b>Restore</b></td>
		</table></td>
</tr><tr class=cor3>
	<td colspan=2><b>Configuration files</b></td>
</tr><tr class='cor2'>
	<td colspan=2><table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr align=center>
			<td width=33%><img src=i/perso.gif style=cursor:hand align=absmiddle title='Edit layout' onclick=\"window.open('editor.php?tepo=4&arquivoId='+$('layout').value+'.htm&campo=../_gravar/layout/','_blank')\"> 
			<select id=layout>$llist</select></td>
			<td width=34%><img onclick=\"window.open('editor.php?tepo=4&arquivoId=css.css&campo=../_gravar/','_blank')\" src=i/ban.gif style=cursor:hand align=absmiddle title='Edit CSS'> <b>CSS</b></td>
			<td width=33%><img onclick=\"window.open('editor.php?tepo=4&arquivoId=js.js&campo=../_gravar/','_blank')\" src=i/alt.gif style=cursor:hand align=absmiddle title='Edit JavaScript'> <b>JavaScript</b></td>
		</tr>
	</table></td>
</tr><tr class=cor3>
	<td colspan=2><b>Visual editor</b></td>
</tr><tr class='cor1'>
	<td align=right><b>Name the edited page: </b></td>
	<td>Axis X <input type=text name=nome_x value='$nome_x' class=text size=5 maxlength=4 onclick=this.select()> Axis Y <input type=text name=nome_y value='$nome_y' class=text size=5 maxlength=4 onclick=this.select()></td>
</tr><tr class='cor1'>
	<td align=right><b>Custom tables: </b></td>
	<td> Text <input type=text class=text size=7 name=nome_cor id=nome_cor value='$nome_cor' onblur=\"$('cnome_cor').style.backgroundColor=this.value\")><img id=cnome_cor style='background-color:$nome_cor' onclick=corp('nome_cor',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14>  Background <input type=text class=text size=7 name=editor_barra id=editor_barra value='$editor_barra' onblur=\"$('ceditor_barra').style.backgroundColor=this.value\"><img id=ceditor_barra style='background-color:$editor_barra' onclick=corp('editor_barra',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14></td>
</tr><tr valign=top class='cor1'>
	<td align=right><img src=i/nada.gif height=3 border=0><br><b>Edit window: </b></td>
	<td>Text <input type=text class=text size=7 name=fonte_cor id=fonte_cor value='$fonte_cor' onblur=\"$('cfonte_cor').style.backgroundColor=this.value\"><img id=cfonte_cor style='background-color:$fonte_cor' onclick=corp('fonte_cor',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14>
	Background <input type=text class=text size=7 name=editor_cor id=editor_cor value='$editor_cor' onblur=\"$('ceditor_cor').style.backgroundColor=this.value\"><img id=ceditor_cor style='background-color:$editor_cor' onclick=corp('editor_cor',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14>
	Image <input type=file name=Imagem class=text size=1><br>Axis X <input type=text name=editor_x value='$editor_x' class=text size=5 maxlength=4 onclick=this.select()> Axis Y <input type=text name=editor_y value='$editor_y' class=text size=5 maxlength=4 onclick=this.select()> Width <input type=text name=editor_w value='$editor_w' class=text size=5 maxlength=4 onclick=this.select()> Height <input type=text name=editor_h value='$editor_h' class=text size=5 maxlength=4 onclick=this.select()></td>
</tr><tr class=cor3>
	<td colspan=2><b>Security</b></td>
</tr><tr class='cor1'>
	<td>
		<input type=checkbox name=login ".($login==1?'Checked':'')." class=radio>Allow multiple logins with a password.<BR>
		<input type=checkbox name=copia ".($copia==1?'Checked':'')." class=radio>Prohibit to copy content.
	</td>
	
</tr><tr class=cor3>
	<td colspan=2 align=center><input type=submit value=Save class=button></td>
</tr><tr>
	<td colspan=2 align=right class=cor4><br></td>
</tr>
</form>
</table>
<table border=0 class='camada cor3' cellspacing=0 id=diagn_m>
	<tr id=barra name=barra>
		<td><b>MySQL diagnostic Tools</b></td>		
		<td align=right><img src=i/clo.gif style=cursor:hand onclick=\"ver_n('diagn_m')\"></td>
	</tr><tr>
		<td colspan=2><table border=0 cellspacing=0 cellpadding=0 width=100% style=cursor:hand>
		<tr class=cor1 onclick=\"ver('diagn_m',event);ver('diagn',event);carrega('?checar=1','diag')\">
			<td align=center><img align=absmiddle src=i/hab.gif style=cursor:hand  title='Check table data'></td><td><b>Check</b></td>
		</tr><tr class=cor2 onclick=\"ver('diagn_m',event);ver('diagn',event);carrega('?checar=2','diag')\">
			<td align=center><img align=absmiddle src=i/est.gif style=cursor:hand  title='Analyze table structure'></td><td><b>Analyze</b></td>
		</tr><tr class=cor1 onclick=\"ver('diagn_m',event);ver('diagn',event);carrega('?checar=3','diag')\">
			<td align=center><img align=absmiddle src=i/men.gif style=cursor:hand title='Optimizes table data'></td><td><b>Optimizes</b></td>
		</tr><tr class=cor2 onclick=\"ver('diagn_m',event);ver('diagn',event);carrega('?checar=4','diag')\">
			<td align=center><img align=absmiddle src=i/justify.gif style=cursor:hand title='Repair table data'></td><td><b>Repair data</b></td>
		</tr><tr class=cor1 onclick=\"ver('diagn_m',event);ver('diagn',event);carrega('?checar=5','diag')\">
			<td align=center><img align=absmiddle src=i/med.gif style=cursor:hand title='Optimizes table structure'></td><td><b>Optimizes structure</b></td>
		</tr>
	</table></td>
	</tr>
</table>
<table border=0 class='camada cor3' cellspacing=0 id=diagn>
	<tr name=barra id=barra>
		<td><b>MySQL diagnostic Tools</b></td>		
		<td align=right><img src=i/clo.gif style=cursor:hand onclick=\"diag.innerHTML='';ver_n('diagn')\"></td>
	</tr><tr>
		<td colspan=2 id=diag></td>
	</tr>
</table>
$final_pagina";
}
?>