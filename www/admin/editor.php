<?PHP
include "dados.php";
if($l>0&&($per[33]==1||$per[22]==1||$per[23]==1||$per[18]==1||$per[26]==1)){
	if (isset($_GET['tepo'])){
		extract($_GET);
		if($z==1){
			$extra=addslashes(utf8_encode(clui(rawurldecode($_POST['extra']))));		
			if($tepo==1&&$per[33]==1){ //edita campo extra
				$mysql->exec("UPDATE arquivos SET extra='$extra' WHERE id=$arquivoId");
			}elseif($tepo==2&&$per[22]==1){ //edita sistema
				$mysql->exec("update sistemas set $campo='$extra' where id=$arquivoId");	
			}elseif($tepo==3&&$per[23]==1){//edita menu
				$mysql->exec("UPDATE menu SET fonte='$extra' WHERE id=$arquivoId");
			}elseif($tepo==4&&$per[18]==1){//arquivo
				$fi=fopen(realpath($campo.$arquivoId),"w");
				fwrite($fi,stripslashes($extra));
				fclose($fi);
			}elseif($tepo==5&&$per[26]==1){//registro			
				$mysql->exec("UPDATE $nome SET $campo='$extra' WHERE id=$arquivoId");
			}		
			die(stripslashes($extra));
		}else{
			if($tepo==1&&$per[33]==1){ //edita campo extra
				extract($mysql->query("select id arquivoId,nome,extra from arquivos where id=$id")->fetch(PDO::FETCH_ASSOC));
			}elseif($tepo==2&&$per[22]==1){ //edita sistema
				extract($mysql->query("select id arquivoId,nome,$campo extra from sistemas where id=$id")->fetch(PDO::FETCH_ASSOC));
			}elseif($tepo==3&&$per[23]==1){//edita menu
				extract($mysql->query("SELECT id arquivoId,nome,fonte extra FROM menu WHERE id=$id")->fetch(PDO::FETCH_ASSOC));
			}elseif($tepo==4&&$per[18]==1){//arquivo
				$nome=$arquivoId;
				$ao=fopen(realpath($campo.$arquivoId),"r");
				$extra=filesize($campo.$arquivoId)>0?fread($ao,filesize($campo.$arquivoId)):'';
				fclose($ao);
			}elseif($tepo==5&&$per[26]==1){//registro
				extract($mysql->query("SELECT id arquivoId,$campo extra FROM $nome WHERE id=$id")->fetch(PDO::FETCH_ASSOC));
			}
			echo "$inicio_pagina
			<TABLE border=0 cellspacing=0 cellpadding=0 width=100%>
				<TR class=cor4>
					<TD colspan=2 height=25>&nbsp;<IMG src=i/".($tepo==1?'pag':($tepo==2?'alt':($tepo==3?'men':($tepo==4?'new':'bd')))).".gif border=0 align=absmiddle title='Edit'>&nbsp;<b>".($tepo==1?'Extra field':($tepo==2?'System':($tepo==3?'Menu':($tepo==4?'File':'Field'))))."</b></TD>
				</TR><TR class=cor3><FORM name=formulario action='editor.php?z=1&tepo=$tepo&arquivoId=$arquivoId&campo=$campo&nome=$nome'>
					<TD><IMG align=absmiddle src=i/".($campo=='fonte'?'perso':($campo==''?'new':'prod')).".gif title='Editing'>&nbsp;<B>$nome</B> <span id=salvo style=color:red;font-weight:bold></span></TD>
					<TD align=right><IMG src=i/save.gif align=absmiddle onclick=\"$('extra').value=escape(clui($('extra').value));carrega('','extra',formulario);$('salvo').innerHTML='Changed data successfully'\" title='Save'><IMG align=absmiddle src=i/can.gif title='Cancel edit' onclick=\"window.location='".($tepo==1?'estrutura.php?i=$i':($tepo==2?'sistemas.php?i=$i':($tepo==3?'menu.php?i=$i':($tepo==4?"ftp.php?i=$i&diretorio=$campo":'sql.php'))))."'\"></TD>
				</TR><TR>
					<TD colspan=2><TEXTAREA style=width:100%;height:500 rows=20 id=extra name=extra onkeydown=teclasEspeciais(event)>".htmlentities($extra)."</TEXTAREA></TD>
				</TR></FORM>
			</TABLE>		
			<SCRIPT>			
				function teclasEspeciais(event){
					if (event.keyCode==9||event.which==9) {
						if ($('extra').selectionStart) {
							inicio=$('extra').selectionStart
							fim=$('extra').selectionEnd;
							$('extra').value = $('extra').value.substring(0,inicio)+'\t'+$('extra').value.substr(fim);
							$('extra').setSelectionRange(inicio+1,inicio+1);
							$('extra').focus();
							event.preventDefault()
						} else {
							document.selection.createRange().text='\t';
							event.returnValue=false
						}	
					}
				}
				function apagaAviso(){
					$('salvo').innerHTML=''
					setTimeout('apagaAviso()',7000)
				}
				apagaAviso();
			</SCRIPT>
			$final_pagina";
		}
	}
}else{
	header("Location : index.php");
}