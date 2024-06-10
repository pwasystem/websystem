<?PHP
include 'dados.php';
if ($l>0&&$per[18]==1){
	$raiz=$per[25];
	$alerta='';	
	$diretorio=$raiz.(isset($_GET['diretorio'])?str_replace('..','',str_replace('./','',str_replace('../','',str_replace($raiz,'',$_GET['diretorio'])))):'');	
	$diretorio.= $raiz==$diretorio ? '':(substr($diretorio,-1,1)=='/'?'':'/');
	//funcão de geracao de nome de pastas
	function nome_pasta($str){
	$str=strtolower($str);
	$com= 	array("/[À-Å]/","/Æ/","/Ç/","/[È-Ë]/","/[Ì-Ï]/","/Ð/","/Ñ/","/[Ò-ÖØ]/","/×/","/[Ù-Ü]/","/[Ý-ß]/","/[à-å]/","/æ/","/ç/","/[è-ë]/","/[ì-ï]/","/ð/","/ñ/","/[ò-öø]/","/÷/","/[ù-ü]/","/[ý-ÿ]/","/[!-\-]|[:-@]|[[-`]|[{-]|[€-Ÿ]|[¡-¿]/","/  /","/\//");
	$sem=array("A","AE","C","E","I","D","N","O","X","U","Y","a","ae","c","e","i","d","n","o","x","u","y",""," ","");
return preg_replace($com,$sem,$str);
	}
	/*___________________________________________Executa acoes nos arquivos______________________________________________*/
	//envia arquivos
	if (isset($_GET['upload'])){
		$arquivos="";
		for ($x=0;$x<9;$x++){
			if (isset($_FILES["upload$x"])){
				$nome=$_FILES["upload$x"]['name'];
				$tamanho=$_FILES["upload$x"]['size'];
				$erro=$_FILES["upload$x"]['error'];
				if (file_exists($diretorio.$nome)&&!empty($nome)){
					$alerta.="<tr><td align=center colspan=2>The file <b>$nome</b> already exists and can not be resubmitted.</td></tr>";
				} else if ($erro>0){
					$alerta.=($erro==2||$erro==1? "<tr><td align=center colspan=2>The file <b>$nome</b> is too large to be sent.</td></tr>":($erro==3?"<tr><td align=center colspan=2>The file <b>$nome</b> was partially uploaded, delete it and resubmit.</td></tr>":""));
				} else {
					move_uploaded_file($_FILES["upload$x"]['tmp_name'], $diretorio.$nome);
					$alerta.="<tr><td align=center colspan=2>File <b>$nome</b> was sent.</td></tr>";
				}
			}
		}
	}
	//renomeia arquivo
	if (isset($_GET['nome'])){
		$nome=nome_pasta($_GET['nome']);
		$antigo=$_GET['antigo'];
		if (!file_exists($diretorio.$nome)&&file_exists($diretorio.$antigo)){
			rename ($diretorio.$antigo,$diretorio.$nome);
			$alerta="<tr><td align=center>The directory $antigo was renamed to success $nome.</td></tr>";
		} else {		
			$alerta="<tr><td align=center>The directory $antigo can not be renamed.</td></tr>";
		}
	}
	//cria diretorio
	if (isset($_GET['novo'])){
		$novo=nome_pasta($_GET['novo']);
		if (file_exists($diretorio.$novo)){
			$alerta="<tr><td align=center>The directory already exists and can not be created</td></tr>";
		} else {
			mkdir ($diretorio.$novo,0750);
			$alerta="<tr><td align=center>The directory <B>$diretorio$novo</b> was created with success.</td></tr>";
		}
	}
	//executa download
	if (isset($_GET['download'])){
		$download=realpath($diretorio.$_GET['download']);
		header("Content-type: unknown/unknown"); 
		header("Content-disposition: attachment; filename=".$_GET['download']);
		readfile($download);
		die();
	}
	//Define permissão da pasta
	if (isset($_GET['gravar']))	chmod(($diretorio.$_GET['arquivo']),($_GET['gravar']==1?"0777":"0600"));
	//apaga arquivo ou diretório
	if (isset($_GET['apaga'])) {
		$apaga=$diretorio.$_GET['apaga'];
		$tipo=$_GET['tipo']==0?'directory':'file';
		if (file_exists("$apaga")) {
			if ($tipo=='file') {//acao para arquivo
				unlink("$apaga");
				$alerta="<tr><td align=center>The file <b>$apaga</b> was successfully deleted.</td></tr>";
			} else {//acao para diretorio
				$lera = opendir("$apaga");
				$erro=0;
				while (false !== ($ap=readdir($lera))) {
					if ($ap!='.'&&$ap!='..'&&!strstr($ap,'.')) { 
						$alerta="<tr><td align=center>You must delete the subdirectories <b>$apaga</b> before it deletes.</td></tr>";
						$erro=1;
					} else {
						$ap!='.'&&$ap!='..'?unlink("$apaga/$ap"):'';
					}
				}
				closedir($lera);
				if ($erro==0) {
					rmdir("$apaga");
					$alerta="<tr><td align=center>The directory <b>$apaga</b> was successfully deleted.</td></tr>";
				}
			}
		} else {//se não existe arquivo
			$alerta="<tr><td align=center>The $tipo <b>$apaga</b> can not be deleted.</td></tr>";
		}
	}	
	/*___________________________________________le direotorios e escreve botoes______________________________________________*/
	//le diretorio
	$ler = opendir($diretorio);
	$arquivos="";
	$pastas="";
	$da=substr($diretorio,0,-1);
	$da=substr($da,0,strrpos($da,'/'));
	$diretorio_acima=$diretorio==$raiz?'':"<tr><td colspan=2><a href=\"?diretorio=$da\">&nbsp;<img src=i/cat.gif border=0 align=absmiddle><b> ..</b></a></td></tr>";
	$n=0;
	$kp='cor2';
	while (false !== ($arquivo=readdir($ler))) {
		if ($arquivo != "." && $arquivo != "..") {
			$perm=fileperms("$diretorio$arquivo");
			$size=ceil(sprintf("%u", filesize("$diretorio$arquivo"))/1000).'KB';
			$gravar="<input type=checkbox name=gravar ".($perm==0777||$perm==33206||$perm==16895?'':'checked')." onclick=\"window.location='?gravar=".($perm==0777||$perm==33206||$perm==16895?0:1)."&arquivo=$arquivo&diretorio=$diretorio'\">";
			$diretorio=$diretorio==$raiz?$raiz:$diretorio;
		if (is_dir("$diretorio$arquivo")){
			$pastas.="
			<tr id=flip nam=flip class=".($kp=$kp=='cor2'?'cor1':'cor2')." align=right>
				<td align=left><a href=\"?diretorio=$diretorio$arquivo\"><img src=i/open.gif border=0 align=absmiddle><span id=nome$n>$arquivo</span></a></td>
				<td>$gravar<a href=\"javascript:renomear($n,'".rawurlencode($arquivo)."')\"><img src=i/cad.gif border=0 align=absmiddle title='Rename file'></a> <a href=# onclick=\"apaga('$arquivo',0)\"><img src=i/del.gif border=0></a></td>
			</tr>";
		} else {
			$arquivos.="
				<tr id=flip name=flip class=".($k=$k=='cor2'?'cor1':'cor2')." align=right>
					<td align=left><img src=i/new.gif align=absmiddle style=cursor:hand onclick=\"window.location='?download=$arquivo&diretorio=$diretorio'\"><span id=nome$n>$arquivo</span></td>
					<td>$size <img src=i/edit.gif style=cursor:hand align=absmiddle title='Edit file' onclick=\"location.href='editor.php?tepo=4&arquivoId=$arquivo&campo=$diretorio'\"> $gravar<img src=i/cad.gif style=cursor:hand align=absmiddle title='Rename file' onclick=\"renomear($n,'".rawurlencode($arquivo)."')\"> <img src=i/del.gif style=cursor:hand onclick=\"apaga('$arquivo',1)\"></td>
				</tr>";
			}
		}
		$n++;
	}
	closedir($ler);
	if (empty($pastas)&&empty($arquivos)) $arquivos="";
	echo $inicio_pagina."
<script>
function apaga(arquivo,tipo){
	avisar(tipo==0?'The directory':'The file'+': '+arquivo+'<BR>Will be deleted and can not be recovered, to continue.<BR><BR><INPUT type=button class=button value=Yes onclick=\"window.location=\'?apaga='+arquivo+'&tipo='+tipo+'&diretorio=$diretorio\'\"> <INPUT type=button class=button value=No onclick=fechaviso()>')
}
function novo_dir(nome){
	window.location='?novo='+nome+'&diretorio=$diretorio'
}
function arquivo_nome(antigo){	window.location='?antigo='+antigo+'&nome='+document.getElementById('noxe').value+'&diretorio=$diretorio'
}
tmp=true
function renomear(id,valor){
	if (tmp){
		l=document.all['nome'+id]
		bkp=l.innerHTML
		l.innerHTML='<input type=text name=nome id=noxe value=\\\"'+valor+'\\\" class=text size=50 maxlength=50 onclick=this.select()><input type=submit value=ok class=button onclick=\\\"arquivo_nome(\''+valor+'\')\\\"><input type=button value=\" X \" class=button onclick=desrenomear('+id+')>'
	} else {
		l.innerHTML=bkp
		tmp=true
		renomear(id,valor)
	} 
	tmp=false
}
function desrenomear(id){
	!tmp ? l.innerHTML=bkp:null;
	tmp=true
}
</script>
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr class=cor3>
		<td colspan=2>&nbsp;<a href=# onclick=\"ver('nite',event)\"><img src=i/ftp.gif border=0 align=absmiddle title='Create a new directory'><a href=# onclick=\"ver('barra1',event)\"><img src=i/new.gif border=0 align=absmiddle title='Send files'></a></a><b>Directory:</b> $diretorio <span id=wdor></span></td>
	</tr>
	$alerta$diretorio_acima$pastas$arquivos
	<tr>
		<td class=cor4 align=center colspan=2><br></td>
	</tr>
</table>
<table border=0 cellpadding=0 cellspacing=0 id=barra1 class='camada cor3'>
	<tr id=barra name=barra>
		<td><b>Send files</b></td>
		<td align=right><img src=i/clo.gif name=fecha></td>
	</tr><tr>
		<td colspan=2><table border=0 cellpadding=0 cellspacing=0 width=100%><form enctype=multipart/form-data method=post name=upload action=?upload=1&diretorio=$diretorio><input type=hidden name=MAX_FILE_SIZE value=30000000>		
			<tr>
			<td align=right><input type=image src=i/save.gif></td>
		</tr><tr>
			<td><input type=file name=upload0 class=text></td>
		</tr><tr>
			<td><input type=file name=upload1 class=text></td>
		</tr><tr>
			<td><input type=file name=upload2 class=text></td>
		</tr><tr>
			<td><input type=file name=upload3 class=text></td>
		</tr><tr>
			<td><input type=file name=upload4 class=text></td>
		</tr><tr>
			<td><input type=file name=upload5 class=text></td>
		</tr><tr>
			<td><input type=file name=upload6 class=text></td>
		</tr><tr>
			<td><input type=file name=upload7 class=text></td>
		</tr><tr>
			<td><input type=file name=upload8 class=text></td>
		</tr><tr>
			<td><input type=file name=upload9 class=text></td>
		</tr></form>
	</table></td>
	</tr>
</table>
<!--Novo-->
<table cellspacing=0 border=0 class='camada cor3' id=nite>
	<tr name=barra id=barra>
		<td><b>New directory</b></td>
		<td align=right><img src=i/clo.gif id=fecha name=fecha></td>
	</tr><tr>
		<td colspan=2><b>Name: </b><input type=text id=cnome class=text size=17><img align=absmiddle src=i/save.gif onclick=novo_dir(document.getElementById('cnome').value)></td>
	</tr>
</table>	
	$final_pagina";
} else {
	header("Location : index.php");
}
?>
