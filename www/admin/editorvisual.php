<?PHP
include 'dados.php';

if ($l>0){
	//trata textos
	function trata($texto){
		return addslashes(str_replace('T:AREA','TEXTAREA',str_replace('t:area','textarea',(get_magic_quotes_gpc()?stripslashes($texto):$texto))));
	}
	//pesquisa pagina
	if ($z==1){
		$links='';
		error_reporting(E_ALL);
		if (isset($_REQUEST['nome'])){
			foreach($mysql->query("SELECT id,nome,idioma FROM arquivos WHERE nome LIKE '%$_REQUEST[nome]%' ORDER BY nome,data desc LIMIT 100") as $b) $links.="<OPTION value=$b[0]&idioma=$b[2]>$b[1] ";
			if (empty($links)) {
				$links="<TR>
					<TD>&nbsp;No pages were found</TD>
					<TD>&nbsp;<INPUT type=button onclick=\"window.location='editorvisual.php?z=1'\" value=' ! ' class=button>&nbsp;<INPUT type=button onclick=\"parent.ferramentas(20,1,event)\" value=' X ' class=button></TD>
				</TR>";
			} else {
				$links="<TR>
					<TD>&nbsp;<SELECT name=links style=width:350><OPTION value=0>Select the page and click ok to create the link$links</SELECT></TD>
					<TD>&nbsp;<INPUT type=button onclick=\"parent.editar('createlink','index.php?id='+document.form.links.value)\" value='ok' class=button>&nbsp;<INPUT type=button onclick=\"window.location='editorvisual.php?z=1'\" value=' ! ' class=button>&nbsp;<INPUT type=button onclick=\"parent.ferramentas(20,1,event)\" value=' X ' class=button></TD>
				</TR>";
			}
			$m=$links;
		} else {		
			$m="$links<TR>
				<TD align=right>&nbsp;<B>Name:</B></TD>
				<TD>&nbsp;<INPUT type=text name=nome class=text size=47></TD>
				<TD>&nbsp;<INPUT type=submit value=Buscar class=button>&nbsp;<INPUT type=button onclick=\"parent.ferramentas(20,1,event)\" value=' X ' class=button></TD>
			</TR>"; 
		}
		echo "<HTML>
			<HEAD>
				<TITLE>Internal link</TITLE>
				<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<LINK rel=stylesheet href=../_gravar/css.css>
			</HEAD>
			<BODY topmargin=0 leftmargin=0 class=cor3>
				<TABLE border=0 cellspacing=0 cellpadding=0 class=cor3 height=100%>
					<FORM method=post action=editorvisual.php?z=1 name=form>$m</FORM>
				</TABLE>
			</BODY>
		</HTML>";
		return false;
	}elseif ($z==2){//envia imagens temporarias
		$arquivo=$_FILES['imag']['name'];
		$nome=$_POST['n_imagem'];
		$stu=move_uploaded_file($_FILES["imag"]['tmp_name'], "../_gravar/$nome");
		$stu==1?$mysql->exec("INSERT INTO temp(pai,nome) VALUES($id,'$nome')"):null;
		die("<SCRIPT>		
			parent.inserir(parent.f_imagem)		
			parent.ferramentas(22,1,1)
			parent.document.getElementById('jax').style.display='none'
		</SCRIPT>");
	}elseif ($z==3){//envia flash temporarios
		$arquivo=$_FILES['arquivo_f']['name'];
		$nome=$_POST['n_flash'];
		$stu=move_uploaded_file($_FILES["arquivo_f"]['tmp_name'], "../_gravar/$nome");
		$stu==1?$mysql->exec("INSERT INTO temp(pai,nome) VALUES($id,'$nome')"):null;
		die("<SCRIPT>	
			parent.inserir(parent.fonte_flash)
			parent.ferramentas(23,1,1)
			parent.document.getElementById('jax').style.display='none'
		</SCRIPT>");
	//salva alterações
	}elseif ($z==4){
		//le variaveis
		extract($_POST);
		$pai=$_POST['pai'];
		$nome=$_POST['nome'];
		$fonte=str_replace('../','',clui(urldecode($_POST['fonte'])));
		//sava dados
		$mysql->exec("UPDATE arquivos SET fonte='".trata(str_replace('../','',(empty($fonte)?'&nbsp;':utf8_encode($fonte))))."',enquete=$enquete_existe WHERE id=$id");
		//controla enquete
		if ($enquete_existe==0) {
			$mysql->exec("UPDATE enquete SET pai=0 WHERE pai=$id");
		} else {
			@extract($mysql->query("SELECT pergunta pergunta_existe FROM enquete WHERE pai=$id")->fetch(PDO::FETCH_ASSOC));
			$valor='';
			for ($ii=0;$ii<substr_count($resposta,'|');$ii++)$valor.='0,';
			if (isset($pergunta_existe)) {
				$apagar='';
				if ($pergunta!=$pergunta_existe) $mysql->exec("UPDATE enquete SET pai=0 WHERE pai=$id;INSERT INTO enquete(pai,pergunta,resposta,cor1,cor2,valor,data,per1,per2,dirc) VALUES($id,'".utf8_encode(strip_tags($pergunta))."','".utf8_encode(strip_tags($resposta))."','$enquete_cor_1','$enquete_cor_2','$valor',now(),'$enquete_per_1','$enquete_per_2','$direcao')");
			} else {
				$mysql->exec("INSERT INTO enquete(pai,pergunta,resposta,cor1,cor2,valor,data,per1,per2,dirc) VALUES($id,'".utf8_encode(strip_tags($pergunta))."','".utf8_encode(strip_tags($resposta))."','$enquete_cor_1','$enquete_cor_2','$valor',now(),'$enquete_per_1','$enquete_per_2','$direcao')");
			}
		}
		//trata imagens enviadas
		preg_match_all( '<(IMG|img)[^<>]+>', $fonte , $imagens);
		foreach($imagens[0] as $tag){
			$width=0;
			$height=0;
			$tug=str_replace(': ',':',str_replace("'",'',str_replace('"','',strtolower("$tag "))));
			if ($width=strstr($tug,'width:')){
				$width=substr($width,6,strpos($width,' ')-6);
			} elseif ($width=strstr($tug,'width=')) {			
				$width=substr($width,6,strpos($width,' ')-6);
			}
			if ($height=strstr($tug,'height:')){
				$height=substr($height,7,strpos($height,' ')-7);
			} elseif ($height=strstr($tug,'height=')) {			
				$height=substr($height,7,strpos($height,' ')-7);			
			}
			$width=preg_replace('/[^0-9]/','',$width);
			$height=preg_replace('/[^0-9]/','',$height);
			$imagem=strstr($tag,'gravar/');
			$imagem=substr($imagem,7,(strpos($imagem,'.')-(strstr($tag,'jpeg')?2:3)));
			if (file_exists("../_gravar/$imagem")){
				//trata imagens JPG redimensionadas
				if (stristr($imagem,'.jpg')||stristr($imagem,'.jpeg')||stristr($imagem,'.png')||stristr($imagem,'.gif')){
					//redimensiona
					$images = GetImageSize ("../_gravar/$imagem");
					if (($width>0&&$images[0]!=$width)||($height>0&&$images[1]!=$height)) thumbnail("../_gravar/$imagem",$width,$height,"../_gravar/$imagem",100);
				}
				$usadas[]=$imagem;
				//salva dados das imagens
				if (!@$mysql->query("SELECT id FROM imagens WHERE pai=$id AND nome='$imagem'")->fetch(PDO::FETCH_ASSOC))$mysql->exec("INSERT INTO imagens(pai,data,nome) VALUES($id,now(),'$imagem')");
			}
		}
		//apaga dados adicionais em temp e imagens não usadas
		$imagem_u=isset($usadas)?implode($usadas,','):0;
		$mysql->exec("DELETE FROM temp WHERE pai=$id and FIND_IN_SET(nome,'$imagem_u')");		
		foreach($mysql->query("SELECT nome FROM imagens WHERE pai=$id AND NOT FIND_IN_SET(nome,'$imagem_u')") as $par) if (file_exists("../_gravar/$par[0]")) unlink("../_gravar/$par[0]");
		$mysql->exec("DELETE FROM imagens WHERE pai=$id AND NOT FIND_IN_SET(nome,'$imagem_u')");		
		//cria array de flash
		preg_match_all('<(EMBED|embed)[^<>]+>',$fonte,$flashs);
		foreach($flashs[0] as $tag){
			if (stristr($tag,'.swf')){//se for flash
				$tag=strstr($tag,'gravar/');
				$fim=strpos($tag,'.')-3;
				$tag=substr($tag,7,$fim);
				$flash[]=$tag;
				if (file_exists("../_gravar/$tag")) if(!@$mysql->query("SELECT id FROM flash WHERE pai=$id AND nome='$tag'")->fetch(PDO::FETCH_ASSOC))$mysql->exec("INSERT INTO flash() VALUES(null,$id,now(),'$tag')");
			}
		}
		//apaga dados adicionais em temp e flash não usadas
		$flash_u=isset($flash)?implode($flash,','):0;
		$mysql->exec("DELETE FROM temp WHERE pai=$id AND FIND_IN_SET(nome,'$flash_u')");
		
		foreach($mysql->query("SELECT nome FROM flash WHERE pai=$id AND NOT FIND_IN_SET(nome,'$flash_u')") as $par) if (file_exists("../_gravar/$par[0]")) unlink("../_gravar/$par[0]");
		
		$mysql->exec("DELETE FROM flash WHERE pai=$id AND NOT FIND_IN_SET(nome,'$flash_u')");		
		//apaga imagens não utilizadas e limpa temp		
		foreach($mysql->query("SELECT nome FROM temp WHERE pai=$id")as $par) if (file_exists("../_gravar/$par[0]")) unlink("../_gravar/$par[0]");
		$mysql->exec("DELETE FROM temp WHERE pai=$id");		
		$sar=$_POST['sair']==1?"parent.location='estrutura.php';":'';
		$sar.="parent.avisar(\"Updating data\",1)";
		die("<SCRIPT>$sar</SCRIPT>");
	} elseif ($z==5) {
		extract($mysql->query("SELECT fonte FROM arquivos WHERE id=$_GET[mod]")->fetch(PDO::FETCH_ASSOC));
		die(rawurlencode("<INPUT type=text id=cahefru value='$fonte'><IMG src=i/nada.gif onload=inserir(document.getElementById('cahefru').value)>"));
	}
	//checa permissão de edição
	if ($per[0]==0){
		$query="SELECT * FROM arquivos WHERE id=$id";
	}else{
		$categoria=$id;
		while($categoria!=0) extract($mysql->query("SELECT pai categoria,nivel FROM arquivos WHERE id=$categoria")->fetch(PDO::FETCH_ASSOC));
		$query=$nivel==$n?"SELECT * FROM arquivos WHERE id=$id":"SELECT * FROM arquivos WHERE id=$id AND CONCAT(',',nivel) LIKE '%,$n,%'";
	}
	//seleciona conteudo
	if ($id!=0){
		$a=$mysql->query($query)->fetch(PDO::FETCH_NUM);
		$fonte=$a[4];
		if (!empty($a[0])){
			if ($a[12]!=0) {
				$b=$mysql->query("SELECT * FROM enquete WHERE pai=$id")->fetch(PDO::FETCH_NUM);
				$res=explode('|',substr($b[3],0,-1));
				$inc='';
				$cni='';
				foreach($res as $ser => $der){
					if($b[10]==1){
						$inc.="<TR bgcolor=$b[5] ".($ser==0?'id=cor_2':'').">
							<TD align=center width=20><INPUT type=radio name=resposta value=$ser class=radio></TD>
							<TD width=100% id=re$ser style='color:$b[9];font-weight:bold'>".$der."</TD>
						</TR>";
					} else {
						$inc.="<TD><INPUT type=radio name=resposta value=$ser class=radio></TD>";
						$cni.="<TD id=re$ser style='color:$b[9];font-weight:bold'>".$der."</TD>";
					}
				}
				if ($b[10]==0) {
					$inc="<TR bgcolor=$b[5] id=cor_2 align=center>
						$cni
					</TR><TR bgcolor=$b[5] align=center>
						$inc
					</TR>";
				}
				$fonte=str_replace('$enquete',"<TBODY>
					<TR bgcolor=$b[4] id=cor_1>
						<TD align=center colspan=".count($res)." id=per style='color:$b[8];font-weight:bold'>".$b[2]."</TD>
					</TR>$inc<TR bgcolor=$b[4] align=center>
						<TD colspan=".count($res)." style='color:$b[8];font-weight:bold'>Back | Results</TD>
					</TR>
				</TBODY>",$fonte);
				$pergunta=$b[2];
				$resposta=$b[3];
				$enquete_cor_1=$b[4];
				$enquete_cor_2=$b[5];
				$enquete_per_1=$b[8];
				$enquete_per_2=$b[9];
				$direcao=$b[10];
			}else{
				$pergunta='';
				$resposta='';
				$enquete_cor_1='';
				$enquete_cor_2='';
				$enquete_per_1='';
				$enquete_per_2='';
				$direcao=0;
			}
			$fonte=str_replace('_gravar','../_gravar',$fonte);
			$fonte=str_replace('border=0 cellPadding','border=1 borderColor=#dcdcdc cellPadding',$fonte);
			$fonte=urlencode(utf8_decode($fonte));
			$modelo='';			
			foreach($mysql->query("SELECT b.id,b.nome FROM arquivos a INNER JOIN arquivos b ON a.id=b.pai WHERE a.chave='Page templates'") as $dm)$modelo.="<OPTION value=$dm[0]>$dm[1]";
			$menu='';
			foreach($mysql->query("SELECT nome FROM menu") as $dm)	$menu.="<OPTION value='$dm[0]'>$dm[0]";			
			echo "
<HTML> 
	<HEAD>
		<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<LINK rel=stylesheet href=../_gravar/css.css>
		<SCRIPT src=../_gravar/js.js></SCRIPT>
		<SCRIPT>
			msie=navigator.userAgent.indexOf('Trident')!=-1
			//start do editor
			function load(){
				edit=document.getElementById('editor')
				fonte_dec=document.dados.fonte.value			
				while (fonte_dec.match(/\+/)) fonte_dec=fonte_dec.replace('+',' ')
				fonte_dec=unescape(fonte_dec)
				editor.document.open()
				editor.document.write(\"<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><link rel=stylesheet href=../_gravar/css.css><style>table{border:dashed 1px}</style><body bgcolor=$editor_cor text=$fonte_cor topmargin=0 leftmargin=0>\"+fonte_dec);
				editor.document.close()
				editor.document.designMode='On'			
				edit.contentWindow.focus();
				window.editor.document.oncontextmenu=function(){return false}
				//window.editor.document.onfocusout=function(){if (editor.document.selection) txr=editor.document.selection.createRange()}
			}		
			//abre editor de codigo fonte
			modo_grafico=1
			er=0
			function codigo_fonte(){
				if (er==0&&modo_grafico){
					b27.style.background='steelblue'
					fonte_pagina=editor.document.body.innerHTML
					while (fonte_pagina.match('</TEXTAREA>'))fonte_pagina=fonte_pagina.replace('</TEXTAREA>','&lt;/TEXTAREA&gt;')
					while (fonte_pagina.match('</textarea>'))fonte_pagina=fonte_pagina.replace('</textarea>','&lt;/textarea&gt;')
					editor_fonte=window.open('','fonte_editor','status=0,toolbar=0,location=0, directories=0,menuBar=0,scrollbars=0,resizable=1, width=700,height=300,left=0,top=0');
					editor_fonte.document.open()
					editor_fonte.document.write(\"<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><title>Source code editor</title></head><Body topmargin=0 leftmargin=0 style=text-align:center onunload=window.opener.b27.style.background='';window.opener.modo_grafico=true><TABLE border=0 cellspacing=0 cellpadding=0 height=100% width=100%><tr><td><textarea id=editor_txt style='font:12px courrier;width:100%;height:100%;border:nome'>\"+fonte_pagina+\"</textarea></td></tr><tr><td height=20 align=center><input type=button style='border:outset 1px gray;font:bold 9px verdana' value=Visualize onclick=\\\"window.opener.visualiza_editor(document.getElementById('editor_txt').value)\\\"> <input type=button style='border:outset 1px gray;font:bold 9px verdana' value=Update onclick=\\\"document.getElementById('editor_txt').value=window.opener.editor.document.body.innerHTML\\\"></td></tr></table>&lt;/body></html>\")
					editor_fonte.document.close()
					modo_grafico=false
					er=1
				} else {
					if(er==1){
						editor_fonte.close()
						b27.style.background=''
						modo_grafico=true
						er=0
					}
				}
			}
			function visualiza_editor(fonte_pagina){
				while (fonte_pagina.match('&lt;/textarea&gt;'))fonte_pagina=fonte_pagina.replace('&lt;/textarea&gt;','</textarea>')
				editor.document.body.innerHTML=fonte_pagina
			}
			//Exibe ferramenta selecionada
			camad=''
			function ferramentas(botao,coman,event){
				brot=document.getElementById('b'+botao)
				botao=='20'?busca_pagina.location='editorvisual.php?z=1':'';			
				if(modo_grafico||camad==botao){
					bgc=brot.style.background
					brot.style.background=bgc==''||bgc=='none'||bgc=='none repeat scroll 0% 0% transparent'?(coman==1?'lightcyan':'violet'):'none'
					ver('f'+botao,event)
					modo_grafico=camad==botao?(modo_grafico?0:1):coman
					if(modo_grafico==0)camad=botao
				}
			}
			//insere tabela
			function criatab(borda,celsp,celpa,corbor,corfun,coluna,linha,alinhar){
				re = new RegExp('[^0-9]','g');
				col = coluna.match(re); 
				lin = linha.match(re);
				if (col||coluna=='') coluna=1
				if (lin||linha=='') linha=1
				corfu=''
				corb=''
				if (corfun!='') corfu='bgcolor='+corfun
				if (corbor!='') corb='bordercolor='+corbor
				tabelai='<TABLE border='+borda+' cellpadding='+celpa+' cellspacing='+celsp+' '+corfu+' '+corb+' align='+alinhar+'><TBODY valign=top>'
				tabelatr=''
				tabelatd=''
				for (tr=1;tr<=linha;tr++){
					for (td=1;td<=coluna;td++) tabelatd=tabelatd+'<TD>&nbsp;</TD>'
					tabelatr=tabelatr+'<TR>'+tabelatd+'</TR>'
					tabelatd=''
				}
				tabelafim=tabelai+tabelatr+'</TBODY></TABLE>'
				inserir(tabelafim)
				document.getElementById('ccorfundo').style.background='FFFFFF'
				document.getElementById('ccorborda').style.background='000000'
				c14.reset()
				ferramentas(14,1,event)
			}
			//comandos simples
			function editar(comando,parametro){
				if(modo_grafico){
					editor.focus()
					editor.document.execCommand(comando,false,parametro)
					editor.focus()
				}
			}			
			//insere HTML
			function inserir(cont){
				editor.focus()
				if (window.getSelection){
					if(msie){
						var range = editor.document.getSelection().getRangeAt(0)
						var nnode = editor.document.createElement('spam')
						range.surroundContents(nnode)
						nnode.innerHTML=cont
					} else {
						editor.document.execCommand('insertHTML',false,cont)
					}
				}
			}
			function dlink(){
				editor.document.execCommand('createlink',false,$('turl').value)
				fechaviso()
			}
			//adiciona caixas no documento
			function textoi(bo,tipo,nome,valor){
				if (bo==53){
					var v=new Array()
					v=valor.split(',')
					valor=''
					for(i=0;i<v.length;i++)	valor=valor+'<OPTION value=\"'+v[i]+'\">'+v[i]
				}
				if (bo==54){
					var v=new Array()
					v=valor.split(',')
					valor=''
					for(i=0;i<v.length;i++)	valor=valor+'<INPUT type='+tipo+' value=\"'+v[i]+'\" name='+nome+' class=radio>'+v[i]+'<Br>'
				}
				var r=new Array()
				r[50]='<INPUT type='+tipo+' class=text name='+nome+' value=\"'+valor+'\">'
				r[51]='<textarea name='+nome+'>'+valor+'</textarea>'
				r[52]='<INPUT type='+tipo+' value='+nome+' class=button>'
				r[53]='<SELECT name='+nome+'>'+valor+'</SELECT>'
				r[54]=valor
				r[55]='<INPUT type=file name='+nome+' class=text>'
				r[21]='<TABLE border=0 cellspacing=1 cellpadding=1 align=center><INPUT type=hidden class=hidden name=email value='+nome+'><TR><TD align=right >Name: </TD><TD><INPUT type=text name=name size=44 class=text></TD></TR><TR><TD align=right >Address: </TD><TD><INPUT type=text name=address size=44 class=text></TD></TR><TR><TD align=right >Cit: </TD><TD><INPUT type=text name=cit size=26 class=text> State: <INPUT type=text name=state size=5 class=text></TD></TR><TR><TD align=right >Contact form: </TD><TD><INPUT type=radio name=contact_form value=Phone checked class=radio>Phone <INPUT type=radio name=contact_form value=E-mail class=radio>E-mail <INPUT type=radio name=contact_form value=Mobile class=radio>Mobile</TD></TR><TR><TD align=right>Phone: </TD><TD><INPUT type=text name=phone size=15 class=text> Mobile: <INPUT type=text name=mobile size=15 class=text></TD></TR><TR><TD align=right >E-mail: </TD><TD><INPUT type=text name=e_mail size=44 class=text></TD></TR><TR><TD align=right valign=top >Comment: </TD><TD><textarea name=comment cols=43 rows=5></textarea></TD></TR><TR><TD align=right >Annex: </TD><TD><INPUT type=file name=anexo size=29 class=text></TD></TR><TR><TD colspan=2 align=right><INPUT type=submit value=Send class=button>&nbsp;<INPUT type=reset value=Clear class=button>&nbsp;</TD></TR></TABLE>'
				if(nome&&!nome.match(' ')){
					inserir(r[bo])
					document.all['c'+bo].reset()
					ferramentas(bo,1,event)
				}else{
					avisar('The name field must be completed and can not contain blanks',1)
				}
			}		
			//insere imagem
			function imagemi(a_imagem,t_imagem,l_imagem,b_imagem,h_imagem,v_imagem){
				if(a_imagem){
					exten=nome_imagem=a_imagem.substring(a_imagem.lastIndexOf('.')+1).toLowerCase()
					if(exten=='jpg'||exten=='jpeg'||exten=='gif'||exten=='png'){				
						document.getElementById('jax').style.display='table-cell'
						y_imagem=document.dados.id.value+''+diferencial()+'.'+exten
						document.c22.n_imagem.value=y_imagem
						c22.submit()
						f_imagem='<IMG src=\"../_gravar/'+y_imagem+'\" '+(l_imagem=='none'?'':'align='+l_imagem)+' hspace='+h_imagem+' vspace='+v_imagem+' title=\"'+t_imagem+'\" border='+b_imagem+'>'
						c22.reset() 
					} else {
						avisar('The system only accepts GIF, JPG, JPEG or PNG',1)
					}
				}else{
					avisar('You need to send a picture a GIF, JPG, JPEG or PNG file',1)
				}
			}
			//insere flash
			function flashi(arquivo_flash,a_flash,w_flash,h_flash,cor_f){
				exten=nome_imagem=arquivo_flash.substring(arquivo_flash.lastIndexOf('.')+1)
				if(arquivo_flash&&exten=='swf'||exten=='SWF'){
					document.getElementById('jax').style.display='table-cell'
					y_flash=document.dados.id.value+''+diferencial()+'.swf'
					document.c23.n_flash.value=y_flash					
					c23.submit()
					!w_flash ? w_flash=100 : w_flash=w_flash
					!h_flash ? h_flash=100 : h_flash=h_flash				
					fonte_flash='<EMBED pluginspage=http://www.macromedia.com/go/getflashplayer align='+a_flash+' src=\"../_gravar/'+y_flash+'\" width='+w_flash+' height='+h_flash+' type=application/x-shockwave-flash bgcolor=\"'+cor_f+'\" quality=\"high\"></EMBED>'
					c23.reset()
				}else{
					avisar('You need to send a SWF file',1)					
				}
			}		
			function enquete_i(copf,copl,corf,corl,opc,ten){
				cpf=copf=='cores'?'gainsboro':copf;
				cpl=copl=='cores'?'black':copl;
				crf=corf=='cores'?'whitesmoke':corf;
				crl=corl=='cores'?'black':corl;
				inc=''
				cni=''
				for (ii=0;ii<opc;ii++){
					if (ten==1){
						inc=inc+'<TR bgcolor='+crf+' '+(ii==0?'id=cor_2':'')+'><TD align=center width=20><INPUT type=radio class=radio name=resposta value='+ii+'></TD><TD width=100% id=re'+ii+' style=color:'+crl+';font-weight:bold>Answer '+ii+'</TD></TR>'
					} else {
						inc=inc+'<TD><INPUT type=radio class=radio name=resposta value='+ii+'></TD>'
						cni=cni+'<TD id=re'+ii+' style=color:'+crl+';font-weight:bold>Answer '+ii+'</TD>'
					}
				}
				if(ten==0){
					inc='<TR bgcolor='+crf+' id=cor_2 align=center>'+cni+'</TR><TR bgcolor='+crf+' align=center>'+inc+'</TR>'				
				}
				document.dados.direcao.value=ten			
				i_enquete='<TABLE border=1 cellspacing=0 width='+(ten==0?'100%':'120')+' bordercolor=$editor_cor id=enkete><tbody><TR bgcolor='+cpf+' id=cor_1><TD align=center colspan='+(ten==1?2:opc+1)+' id=per style=color:'+cpl+';font-weight:bold>Question</TD></TR>'+inc+'<TR bgcolor='+cpf+' align=center><TD colspan='+(ten==1?2:opc+1)+'><font face=verdana size=1 color='+cpl+'><B>Back | Results</B></font></TD></TR></tbody></TABLE>'
				inserir(i_enquete)
				ferramentas(29,1,event)
			}		
			function i_tab(tq,cdf,cdt){
				cdf=cdf=='cores'?editor_barra.value:cdf
				cdt=cdt=='cores'? nome_cor.value:cdt
				tabela_p=new Array()
				tabela_p[1]='<TD style=color:'+cdt+';font-weight:bold>Title</TD></TR><TR valign=top><TD>News'
				tabela_p[2]='<TD style=color:'+cdt+';font-weight:bold width=50%>Title 1</TD><TD style=color:'+cdt+';font-weight:bold width=50%>Title 2</TD></TR><TR valign=top><TD>News 1</TD><TD>News 2'
				tabela_p[3]='<TD style=color:'+cdt+';font-weight:bold width=33%>Title 1</TD><TD style=color:'+cdt+';font-weight:bold width=34%>Title 2</TD><TD style=color:'+cdt+';font-weight:bold width=33%>Title 3</TD></TR><TR valign=top><TD>News 1</TD><TD>News 2</TD><TD>News 3'
				inserir('<TABLE border=0 cellspacing=1 cellpadding=1 width=100% align=center><TR bgcolor='+cdf+'>'+tabela_p[tq]+'</TD></TR></TABLE>')
				ferramentas(30,1,event)
			}
			//nome diferenciado
			function diferencial(){			
				d = new Date()
				v=Math.round(Math.random()*10)
				return(d.getYear()+''+d.getMonth()+''+d.getDay()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds()+''+d.getMilliseconds()+''+v)
			}		
			//retorna dados hexadecimais
			function hexa(vah){
				if(vah.match('rgb')){
					dah=vah.substring(4,vah.length-1).replace(' ','').replace(' ','').split(',')
					vah='#'+parseInt(dah[0]).toString(16)+''+parseInt(dah[1]).toString(16)+''+parseInt(dah[2]).toString(16)
				}
				return vah
			}		
			//Salva alterações no documento
			function salvar(){
				if(modo_grafico){
					dod=document.dados					
					//Variaveis iniciais
					editor.document.body.innerText=editor.document.body.innerHTML
					editor.document.body.innerHTML=editor.document.body.innerText
					fonte_bkp=editor.document.body.innerHTML
					fonte_atual=editor.document.body.innerHTML	
					//captura valores para alteração da enquete
					if (editor.document.all.enkete){
						dod.enquete_existe.value=1
						dod.enquete_cor_1.value=editor.document.all.cor_1.bgColor
						dod.enquete_cor_2.value=editor.document.all.cor_2.bgColor					
						dod.enquete_per_1.value=hexa(editor.document.all.per.style.color)
						dod.enquete_per_2.value=hexa(editor.document.all.re1.style.color)
						dod.pergunta.value=editor.document.all.per.innerHTML
						respostas=''
						for(ii=0;ii<editor.document.all.resposta.length;ii++) respostas=respostas+editor.document.all['re'+ii].innerHTML+'|'
						dod.resposta.value=respostas
						enquete_fonte=editor.document.all.enkete.innerHTML
						fonte_atual=fonte_atual.replace(enquete_fonte,'\$enquete')		
					} else {
						document.dados.enquete_existe.value=0
					}				
					//correcao final dos path's
					path=window.location.href
					path_1=path.substring(0,(path.lastIndexOf('/')+1))
					while (fonte_atual.match(path_1))fonte_atual=fonte_atual.replace(path_1,'') //alert('PATH 1\\n'+path_1)
					path_2=path.substring(0,path.lastIndexOf('admin'))
					while (fonte_atual.match(path_2))fonte_atual=fonte_atual.replace(path_2,'') //alert('PATH 2\\n'+path_2)
					editor.document.body.innerHTML=fonte_bkp
					dod.fonte.value=escape(clui(fonte_atual))
					//posta dados
					avisar(\"Want exit editor?<BR><BR><INPUT class=button type=button onclick='dod.sair.value=1;document.dados.submit()' value=' Yes '>  <INPUT class=button type=button onclick='dod.sair.value=0;document.dados.submit()' value=' No '>\")					
				}
			}
		</SCRIPT>		
	</HEAD>
	<BODY bgcolor=$editor_cor topmargin=0 leftmargin=0 nosave oncontextmenu='return false' ondragstart='return false' onselectstart='return false'>	
		<INPUT type=hidden id=lcp value='white'>
		<INPUT type=hidden name=editor_cor value=$editor_cor>
		<INPUT type=hidden name=nome_cor value=$nome_cor>
		<INPUT type=hidden name=fonte_cor value=$fonte_cor>
		<INPUT type=hidden name=editor_barra value=$editor_barra>

		<!--Nome da página-->
		<DIV style='position:absolute;top:$nome_y;left:$nome_x' class=titulo>$a[3]</DIV>
		
		<TABLE border=1 cellpadding=0 cellspacing=0 width=100% height=100% style=\"background-image:URL('../_gravar/layout.jpg');background-repeat:no-repeat\" align=left>
			<FORM method=post name=dados action=editorvisual.php?id=$id&z=4&i=$i&n=$n target=saveme accept-charset='UTF-8'>
				<INPUT type=hidden value='$a[0]' name=id>
				<INPUT type=hidden value='$a[1]' name=pai>
				<INPUT type=hidden value='$a[3]' name=nome>
				<INPUT type=hidden value='$fonte' name=fonte>
				<INPUT type=hidden value='$pergunta' name=pergunta>
				<INPUT type=hidden value='$resposta' name=resposta>
				<INPUT type=hidden value='$enquete_cor_1' name=enquete_cor_1>
				<INPUT type=hidden value='$enquete_cor_2' name=enquete_cor_2>
				<INPUT type=hidden value='$enquete_per_1' name=enquete_per_1>
				<INPUT type=hidden value='$enquete_per_2' name=enquete_per_2>
				<INPUT type=hidden value='$a[12]' name=enquete_existe>
				<INPUT type=hidden value='$direcao' name=direcao>	
				<INPUT type=hidden value=0 name=sair>
			</FORM>
			<TR valign=top>
				<TD><!--janela do editor--><IFRAME width=$editor_w height=$editor_h name=editor id=editor frameborder=0 style='position:absolute;top:$editor_y;left:$editor_x' contenteditable=true></IFRAME></TD>
			</TR>
		</TABLE>

		<!--pmc - Paleta de cores -->
		<TABLE border=0 class='camada cor3' cellspacing=0 id=pmc width=112>
			<TR id=barra name=barra>
				<TD><B>Color</B></TD>		
				<TD align=right><IMG src=i/clo.gif style=cursor:hand onclick=\"ver_n('pmc')\"></TD>
			</TR><TR>
				<TD colspan=2><script src=paleta.php></script></TD>
			</TR>
		</TABLE>

		<!--1 ferramentas-->
		<TABLE border=0 cellpadding=0 cellspacing=0 id=barra1 class='camada cor3' style=visibility:visible;top:0;left:0>
			<TR>
				<TD colspan=2 id=barra name=barra><Br></TD>
			</TR><TR>
				<TD><IMG src=i/save.gif title='Save' onclick=salvar() id=b0 class=botao></TD>
				<TD><IMG src=i/can.gif title='Cancel' id=b1 onclick=\"modo_grafico?window.location='estrutura.php?id='+document.dados.pai.value+'&w=':''\" class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/cut.gif title='Cut' id=b2 onclick=editar('cut') class=botao></TD>
				<TD><IMG src=i/copy.gif title='Copy' id=b3 onclick=editar('copy') class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/paste.gif title='Paste' id=b4 onclick=editar('paste') class=botao></TD>
				<TD><IMG src=i/undo.gif title='Undo' id=b28 onclick=editar('undo') class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/print.gif title='Print' id=b32 onclick=\"inserir('<div align=right><A href=javascript:imprime(conteudo_da_tela)><B>Print</B></A>&nbsp;&nbsp;</div>')\" class=botao></TD>
				<TD><IMG src=i/back.gif title='Back' id=b33 onclick=\"inserir('<div align=center><A href=javascript:history.back()><B>Back</B></A></div>')\" class=botao></TD>	
			</TR><TR>
				<TD><IMG src=i/box.gif title='Archive of templates' id=b61 onclick=\"ferramentas(61,1,event)\" ) class=botao></TD>
				<TD><IMG src=i/tab.gif title='Table' id=b14 onclick=ferramentas(14,0,event) class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/image.gif title='Image' id=b22 onclick=ferramentas(22,0,event) class=botao></TD>
				<TD><IMG src=i/flash.gif title='Flash' id=b23 onclick=ferramentas(23,0,event) class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/left.gif title='Align left' id=b5 onclick=editar('justifyleft') class=botao></TD>
				<TD><IMG src=i/center.gif title='Align center' id=b6 onclick=editar('justifycenter') class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/right.gif title='Align right' id=b7 onclick=editar('justifyright') class=botao></TD>
				<TD><IMG src=i/justify.gif title='Align justify' id=b8 onclick=editar('justifyfull') class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/ileft.gif title='Decrease indentation' id=b9 onclick=editar('outdent') class=botao></TD>
				<TD><IMG src=i/iright.gif title='Increasing indentation' id=b10 onclick=editar('indent') class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/nlist.gif title='Numeration' id=b11 onclick=editar('insertorderedlist') class=botao></TD>
				<TD><IMG src=i/blist.gif title='Bookmarks' id=b12 onclick=editar('insertunorderedlist') class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/line.gif title='Line' id=b13 onclick=editar('inserthorizontalrule') class=botao></TD>
				<TD><IMG src=i/font.gif title='Text tools' id=b31 onclick=\"ferramentas(31,1,event)\"  class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/wlink.gif title='Create link' id=b18 onclick=\"msie?editar('createlink'):avisar('Type a URL:<BR><BR><INPUT type=text class=text id=turl> <INPUT type=button class=button onclick=dlink() value=OK> ')\" class=botao></TD>
				<TD><IMG src=i/ulink.gif title='Drop link' id=b19 onclick=editar('unlink') class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/lista.gif title='Internal links' id=b20 onclick=\"ferramentas(20,1,event)\" class=botao></TD>
				<TD><IMG src=i/cmail.gif title='Message boxes' id=b60 onclick=\"ferramentas(60,1,event)\" class=botao></TD>
			</TR><TR>
				<TD><IMG src=i/poll.gif title='Poll' id=b29 onclick=\"editor.document.body.innerHTML.indexOf('enkete')<1?ferramentas(29,0,event):
				avisar('A poll per page is allowed only',1)\" class=botao></TD>
				<TD><IMG src=i/fonte.gif title='Source code' id=b27 onclick=codigo_fonte() class=botao></TD>	
			</TR>
		</TABLE>

		<!--14 Inserção de Tabelas -->
		<TABLE border=0 cellspacing=0 class='camada cor3' id=f14><form name=c14>
			<TR name=barra id=barra>
				<TD><B>Table</B></TD>
				<TD align=right colspan=2><IMG src=i/clo.gif onclick=ferramentas(14,1,event)></TD>
			</TR><TR>
				<TD align=right><B>Lines:</B> </TD>
				<TD><INPUT type=text class=text id=coluna size=2 maxlength=2> <B>Columns:</B> <INPUT type=text class=text id=linhas size=2 maxlength=2></TD>
				<TD align=right><IMG src=i/save.gif onclick=\"criatab(document.getElementById('borda').value,document.getElementById('celsp').value,document.getElementById('celpa').value,document.getElementById('corborda').value,document.getElementById('corfundo').value,document.getElementById('linhas').value,document.getElementById('coluna').value,document.getElementById('alinhar').value)\"></TD>
			</TR><TR>
				<TD align=right><B>Border:</B> </TD>
				<TD colspan=2><SELECT id=borda><OPTION value=0>0<OPTION value=1 selected>1<OPTION value=2>2<OPTION value=3>3<OPTION value=4>4<OPTION value=5>5<OPTION value=6>6<OPTION value=7>7<OPTION value=8>8<OPTION value=9>9</SELECT> <B>Align:</B> <SELECT id=alinhar><OPTION value=left>Left<OPTION value=center selected>Center<OPTION value=right>Right</SELECT></TD>
			</TR><TR>
				<TD align=right><B>Border:</B></TD>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0>
					<TR>
						<TD><INPUT type=text class=text size=7 value=000000 id=corborda onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'000000':this.value;document.getElementById('ccorborda').style.backgroundColor=elv;this.value=elv\" maxlength=6></TD>
						<TD><IMG src=i/nada.gif id=ccorborda style='background-color:black;border:solid 1 black' width=14 height=14 onclick=\"corp('corborda',event)\"></span></TD>
						<TD width=5></TD>
						<TD><B>Background:</B> <INPUT type=text value=FFFFFF class=text size=7 id=corfundo onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'FFFFFF':this.value;document.getElementById('ccorfundo').style.backgroundColor=elv;this.value=elv\" maxlength=6></TD>
						<TD><IMG src=i/nada.gif id=ccorfundo style='background-color:white;border:solid 1 black' width=14 height=14 onclick=corp('corfundo',event)></span></TD>
					</TR>
				</TABLE></TD>
			</TR><TR>
				<TD colspan=3 align=center><B>Cellspacing:</B> <SELECT id=celsp><OPTION value=0>0<OPTION value=1 selected>1<OPTION value=2>2<OPTION value=3>3<OPTION value=4>4<OPTION value=5>5<OPTION value=6>6<OPTION value=7>7<OPTION value=8>8<OPTION value=9>9</SELECT> <B>Cellpadding:</B> <SELECT id=celpa><OPTION value=0>0<OPTION value=1 selected>1<OPTION value=2>2<OPTION value=3>3<OPTION value=4>4<OPTION value=5>5<OPTION value=6>6<OPTION value=7>7<OPTION value=8>8<OPTION value=9>9</SELECT></TD>
			</TR></form>
		</TABLE>

		<!--20 Links Internos -->
		<TABLE border=0 cel lpadding=0 cellspacing=0 id=f20 class='camada cor3'>
			<TR>
				<TD width=10 name=barra id=barra><Br></TD>
				<TD><iframe src=editorvisual.php?z=1 style=border:none frameborder=0 width=480 height=24  scrolling=no name=busca_pagina></iframe></TD>
			</TR>
		</TABLE>

		<!--22 inserir imagens-->
		<TABLE border=0 cellspacing=0 class='camada cor3' id=f22>
			<TR  name=barra id=barra>
				<TD><B>Insert Image</B><iframe name=up22 frameborder=0 style=height:0;width:0></iframe></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(22,1,event)></TD>
			</TR><TR>
			<form name=c22 action=editorvisual.php?id=$id&z=2 method=post enctype=multipart/form-data target=up22><INPUT type=hidden name=n_imagem id=n_image>
			</TR><TR>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0>
						<TR>
					<TD align=right><B>Origin:</B> </TD>
					<TD><INPUT type=file size=17 class=text name=imag id=imag><IMG src=i/save.gif onclick=\"imagemi(document.getElementById('imag').value,document.getElementById('texto').value,document.getElementById('ilinhar').value,document.getElementById('iborda').value,document.getElementById('hori').value,document.getElementById('vert').value)\" align=absmiddle></TD>
				</TR><TR>
					<TD align=right><B>Text:</B> </TD>
					<TD><INPUT type=input size=36 class=text id=texto></TD>
				</TR><TR>
					<TD align=right><B>Align: </B> </TD>
					<TD><SELECT id=ilinhar><OPTION value='none'>None<OPTION value='right'>Right<OPTION value='left'>Left<OPTION value='texttop'>Start of text<OPTION value='absbiddle'>Absolute center<OPTION value='baseline'>Baseline<OPTION value='absbottom'>Absolute bottom<OPTION value='bottom'>Bottom<OPTION value='middle'>Middle<OPTION value='top'>Top</SELECT> <B>Border:</B><SELECT id=iborda><OPTION value=0 selected>0<OPTION value=1>1<OPTION value=2>2<OPTION value=3>3<OPTION value=4>4<OPTION value=5>5<OPTION value=6>6<OPTION value=7>7<OPTION value=8>8<OPTION value=9>9</SELECT></TD>
				</TR><TR>
					<TD align=right><B>Spacing:</B> </TD>
					<TD> <B>Horizontal</B> <SELECT id=hori><OPTION value=0 selected>0<OPTION value=1>1<OPTION value=2>2<OPTION value=3>3<OPTION value=4>4<OPTION value=5>5<OPTION value=6>6<OPTION value=7>7<OPTION value=8>8<OPTION value=9>9</SELECT> <B>Vertical</B> <SELECT id=vert><OPTION value=0 selected>0<OPTION value=1>1<OPTION value=2>2<OPTION value=3>3<OPTION value=4>4<OPTION value=5>5<OPTION value=6>6<OPTION value=7>7<OPTION value=8>8<OPTION value=9>9</SELECT></TD>
				</TR></form>	
				</TABLE></TD>
			</TR>
		</TABLE>

		<!--23 - Inserção de Flash -->
		<TABLE border=0 cellspacing=0 class='camada cor3' id=f23>	
			<TR id=barra name=barra>
				<TD><B>Insert Flash</B><iframe name=up23 frameborder=0 style=height:0;width:0></iframe></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(23,1,event)></TD>		
			</TR><TR><form name=c23 action=editorvisual.php?id=$id&z=3 method=post enctype=multipart/form-data target=up23><INPUT type=hidden name=n_flash>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0>
					<TR>
						<TD align=right width=80><B>Origin: </B></TD>
						<TD><INPUT type=file name=arquivo_f id=arquivo_ff class=text size=15> <IMG src=i/save.gif align=absmiddle onclick=flashi(document.getElementById('arquivo_ff').value,document.getElementById('alinhamento_f').value,document.getElementById('largura_f').value,document.getElementById('altura_f').value,document.getElementById('cor_ff').value)></TD>
					</TR><TR>
						<TD align=right><B>Align: </B></TD>
						<TD><SELECT id=alinhamento_f><OPTION value=center>Center<OPTION value=left>Left<OPTION value=right>Right</SELECT> <B>Background: </B><INPUT type=text class=text size=7 maxlength=6 name=cor_f id=cor_ff value='$editor_cor' onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'ffffff':this.value;ccor_f.style.background=elv;this.value=elv\"><IMG id=ccor_f style='background:$editor_cor' onclick=corp('cor_f',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14></TD>
					</TR><TR>
						<TD align=right><B>Width: </B></TD>
						<TD><INPUT type=text id=largura_f size=3 maxlength=4 class=text> <B>Height:</B><INPUT type=text id=altura_f size=3 maxlength=4 class=text></TD>
					</TR></form>
				</TABLE></TD>	
			</TR>
		</TABLE>

		<!--29 enquete-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f29>
			<TR id=barra name=barra>
				<TD><B>Poll</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(29,1,event)></TD>
			</TR><TR>
				<TD><B>Options:</B> <SELECT id=opcao><OPTION value=2>2<OPTION value=3>3<OPTION value=4>4<OPTION value=5>5<OPTION value=6>6<OPTION value=7>7<OPTION value=8>8<OPTION value=9>9<OPTION value=10>10</SELECT> <SELECT id=ten><OPTION value=1>Ver.<OPTION value=0>Hor.</SELECT></TD>
				<TD align=right><IMG src=i/save.gif align=absmiddle onclick=enquete_i(document.getElementById('copf').value,document.getElementById('copl').value,document.getElementById('corf').value,document.getElementById('corl').value,document.getElementById('opcao').value,document.getElementById('ten').value)></TD>
			</TR><TR>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0>
						<TR>
						<TD align=right><B>Question background: </B></TD>
						<TD><INPUT type=text class=text size=7 id=copf value='666666' onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'666666':this.value;document.getElementById('ccopf').style.backgroundColor=elv;this.value=elv\" maxlength=6><IMG id=ccopf style='background-color:666666' onclick=corp('copf',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14> </TD>
					</TR><TR>
						<TD align=right><B>Question color: </B></TD>
						<TD><INPUT type=text class=text size=7 id=copl value='FFFFFF' onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'FFFFFF':this.value;document.getElementById('ccopl').style.backgroundColor=elv;this.value=elv\" maxlength=6><IMG id=ccopl style='background-color:FFFFFF' onclick=corp('copl',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14> </TD>
					</TR><TR>
						<TD align=right><B>Answer background: </B></TD>
						<TD><INPUT type=text class=text size=7 id=corf value='CCCCCC' onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'CCCCCC':this.value;document.getElementById('ccorf').style.backgroundColor=elv;this.value=elv\" maxlength=6><IMG id=ccorf style='background-color:CCCCCC' onclick=corp('corf',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14></TD>
					</TR><TR>
						<TD align=right><B>Answer color: </B></TD>
						<TD><INPUT type=text class=text size=7 id=corl value='000000' onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'000000':this.value;document.getElementById('ccorl').style.backgroundColor=elv;this.value=elv\" maxlength=6><IMG id=ccorl style='background-color:000000' onclick=corp('corl',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14></TD>
					</TR>
				</TABLE></TD>
		</TR>
		</TABLE>

		<!--61 - Layouts - Barras de ferramentas -->
		<TABLE border=0 cellpadding=0 cellspacing=0 id=f61 class='camada cor3'>
			<TR>
				<TD width=10 name=barra id=barra><Br></TD>
				<TD><IMG src=i/let.gif title='Template page' id=b62 onclick=ferramentas(62,0,event) class=botao></TD>
				<TD><IMG src=i/men.gif title='Menu system' id=b63 onclick=ferramentas(63,0,event) class=botao></TD>
				<TD><IMG src=i/perso.gif title='Personal table' id=b30 onclick=ferramentas(30,0,event) class=botao></TD>
			</TR>
		</TABLE>

		<!--30 tabelas personalizadas-->
		<TABLE border=0 cellspacing=0 class='camada cor3' id=f30>
			<TR id=barra name=barra>
				<TD><B>Personal table</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(30,1,event)></TD>
			</TR><TR>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0 width=100%>
						<TR>
						<TD align=right><B>Columns:</B> </TD>
						<TD><SELECT id=tqf><OPTION value=1>1<OPTION value=2>2<OPTION value=3>3</SELECT></TD>
						<TD align=right><IMG src=i/save.gif onclick=i_tab($('tqf').value,$('cdff').value,$('cdtt').value)></TD>
					</TR><TR>
						<TD align=right><B>Background: </B> </TD>
						<TD colspan=2><INPUT type=text class=text size=7 id=cdff value='$editor_barra' onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'$editor_barra':this.value;document.getElementById('ccdff').style.backgroundColor=elv;this.value=elv\" maxlength=6><IMG id=ccdff style='background-color:$editor_barra' onclick=corp('cdff',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14></TD>
					</TR><TR>
						<TD align=right><B>Title:</B> </TD>
						<TD colspan=2><INPUT type=text class=text size=7 id=cdtt value='$nome_cor' onblur=\"elv=this.value.match(/[^0-6|a-f|A-F]/)?'$nome_cor':this.value;document.getElementById('ccdtt').style.backgroundColor=elv;this.value=elv\" maxlength=6><IMG id=ccdtt style='background-color:$nome_cor' onclick=corp('cdtt',event) align=absmiddle src=i/nada.gif border=1 width=14 height=14></TD>
					</TR>
				</TABLE></TD>
			</TR>
		</TABLE>

		<!--62 Modelos de páginas -->
		<TABLE border=0 cellpadding=0 cellspacing=0 id=f62 class='camada cor3'>
			<TR>
				<TD align=right name=barra id=barra><B>Model:</B></TD>
				<TD colspan=2><SELECT name=modelo onchange=\"carrega('?z=5&mod='+this.value,'jok');this.value=0;ferramentas(62,0,event)\"><OPTION value=0>Selecione$modelo</SELECT></TD>
			</TR>
		</TABLE>

		<!--63 Sistema menu -->
		<TABLE border=0 cellpadding=0 cellspacing=0 id=f63 class='camada cor3'>
			<TR>
				<TD align=right name=barra id=barra><B>Menu:</B></TD>
				<TD colspan=2><SELECT name=menut onchange=\"inserir('<TABLE border=0 cellpadding=0 cellspacing=0><TR><TD><!--'+this.value+'--></TD></TR></TABLE>');ferramentas(63,0,event)\"><OPTION value=0>Selecione$menu</SELECT></TD>
			</TR>
		</TABLE>


		<!--31 - Texto - Barras de ferramentas -->
		<TABLE border=0 cellpadding=0 cellspacing=0 class='camada cor3' id=f31>
			<TR>
				<TD name=barra id=barra width=10><Br></TD>
				<TD>&nbsp;<SELECT onChange=editar('fontname',this[this.selectedIndex].value);this.selectedIndex=0 style='font:8pt verdana;width:80'> <OPTION>Font <OPTION value='Arial'>Arial <OPTION value='Arial Black'>Arial Black <OPTION value='Arial Narrow'>Arial Narrow <OPTION value='Comic Sans MS'>Comic Sans<OPTION value='Courier New'>Courier New <OPTION value='System'>System <OPTION value='Times New Roman'>Times<OPTION value='Verdana'>Verdana <OPTION value='Wingdings'>Wingdings </SELECT></TD>
				<TD><SELECT onChange=editar('fontsize',this[this.selectedIndex].value);this.selectedIndex=0 style='font:8pt verdana;width:52'> <OPTION>Tam <OPTION value='1'>1 <OPTION value='2'>2 <OPTION value='3'>3 <OPTION value='4'>4 <OPTION value='5'>5 <OPTION value='6'>6 <OPTION value='7'>7 </SELECT></TD>
				<TD><font size=4>|</font></TD>
				<TD><IMG src=i/bold.gif title='Bold' id=b15 onclick=editar('bold') class=botao></TD>
				<TD><IMG src=i/italic.gif title='Italic' id=b16 onclick=editar('italic') class=botao></TD>
				<TD><IMG src=i/under.gif title='Underlined' id=b17 onclick=editar('underline') class=botao></TD>
				<TD><font size=4>|</font></TD>
				<TD><IMG src=i/backcolor.gif title='Background color' id=b25 onclick=corp('backcolor',event) class=botao></TD>
				<TD><IMG src=i/fcolor.gif title='Font color' id=b26 onclick=corp('forecolor',event) class=botao></TD>
			</TR>
		</TABLE>

		<!--60 - Formulários - Barras de ferramentas -->
		<TABLE border=0 cellpadding=0 cellspacing=0 id=f60 class='camada cor3'>
			<TR>
				<TD width=10 name=barra id=barra><Br></TD>
				<TD><IMG src=i/form.gif title='Complet form' id=b21 onclick=ferramentas(21,0,event) class=botao></TD>
				<TD><IMG src=i/ctexto.gif title='text, password and hidden field' id=b50 onclick=ferramentas(50,0,event) class=botao></TD>
				<TD><IMG src=i/carea.gif title='Textarea' id=b51  onclick=ferramentas(51,0,event) class=botao></TD>
				<TD><IMG src=i/csele.gif title='Selection box' id=b53  onclick=ferramentas(53,0,event) class=botao></TD>
				<TD><IMG src=i/cradio.gif title='Checkbox and radio button' id=b54  onclick=ferramentas(54,0,event) class=botao></TD>
				<TD><IMG src=i/ane.gif title='File field' id=b55  onclick=ferramentas(55,0,event) class=botao></TD>
				<TD><IMG src=i/cbot.gif title='Submit, clean and simple button' id=b52  onclick=ferramentas(52,0,event) class=botao></TD>
			</TR>
		</TABLE>

		<!--21 Completo-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f21><form name=c21>
			<TR name=barra id=barra>
				<TD><B>Complet form</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(21,1,event)></TD>
			</TR><TR>
				<TD colspan=2><B>E-mail: </B><INPUT type=text id=cnome class=text size=17><IMG align=absmiddle src=i/save.gif onclick=textoi(21,0,document.getElementById('cnome').value,0)></TD>
			</TR></form>
		</TABLE>

		<!--50 Input-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f50><form name=c50>
			<TR name=barra id=barra>
				<TD><B>Text field</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(50,1,event)></TD>
			</TR><TR>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0>
					<TR>
						<TD align=right><B>Type: </B></TD>
						<TD><SELECT id=btipo><OPTION value=text>Text<OPTION value=password>Password<OPTION value=hidden>Hidden</SELECT></TD>
						<TD align=right><IMG src=i/save.gif onclick=textoi(50,document.getElementById('btipo').value,document.getElementById('bnome').value,document.getElementById('bvalor').value) align=absmiddle></TD>
					</TR><TR>
						<TD align=right><B>Name: </B></TD>
						<TD colspan=2><INPUT type=text id=bnome class=text size=18></TD>
					</TR><TR>
						<TD align=right><B>Value: </B></TD>
						<TD colspan=2><INPUT type=text id=bvalor class=text size=18></TD>
					</TR></form>
				</TABLE></TD>
				</TR>
		</TABLE>

		<!--51 Textarea-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f51><form name=c51>
			<TR id=barra name=barra>
				<TD><B>Textarea</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(51,1,event)></TD>
			</TR><TR>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0>
					<TR>
						<TD align=right><B>Name: </B></TD>
						<TD><INPUT type=text id=tnome class=text size=14><IMG src=i/save.gif align=absmiddle onclick=textoi(51,0,document.getElementById('tnome').value,document.getElementById('tvalor').value)></TD>
					</TR><TR>
						<TD align=right><B>Value: </B></TD>
						<TD><INPUT type=text id=tvalor class=text size=18></TD>
					</TR></form>
				</TABLE></TD>
			</TR>
		</TABLE>	

		<!--52 Button-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f52><form name=c52>
			<TR name=barra id=barra>
				<TD><B>Button</B></TD>
				<TD align=right colspan=2><IMG src=i/clo.gif onclick=ferramentas(52,1,event)></TD>
			</TR><TR>
				<TD align=right><B>Type: </B></TD>
				<TD><SELECT id=xtipo><OPTION value=submit>Submit<OPTION value=reset>Clean<OPTION value=button>Simple</SELECT></TD>
				<TD align=right><IMG src=i/save.gif align=absmiddle onclick=textoi(52,document.getElementById('xtipo').value,document.getElementById('xnome').value,0)></TD>
			</TR><TR>
				<TD align=right><B>Name: </B></TD>
				<TD colspan=2><INPUT type=text id=xnome class=text size=18></TD>
			</TR></form>
		</TABLE>

		<!--53 Select-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f53><form name=c53>
			<TR name=barra id=barra>
				<TD><B>Selection box</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(53,1,event)></TD>
			</TR><TR>
				<TD colspan=2><B>Name: </B> <INPUT type=text id=snome class=text size=15> <IMG src=i/save.gif align=absmiddle onclick=textoi(53,0,document.getElementById('snome').value,document.getElementById('svalor').value)></TD>
			</TR><TR>
				<TD colspan=2 align=center><B>Options: </B>(o1,o2)<Br><textarea id=svalor style=width:150></textarea></TD>
			</TR></form>
		</TABLE>

		<!--54 Campo de seleção -->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f54><form name=c54>
			<TR name=barra id=barra>
				<TD><B>Checkbox and Radio</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(54,1,event)></TD>
			</TR><TR>
				<TD colspan=2><TABLE border=0 cellpadding=0 cellspacing=0>
					<TR>
					<TD align=right><B>Type: </B></TD>
					<TD><SELECT id=ytipo><OPTION value=radio>Radio<OPTION value=checkbox>Checkbox</SELECT></TD>
					<TD align=right><IMG src=i/save.gif align=absmiddle onclick=textoi(54,document.getElementById('ytipo').value,document.getElementById('ynome').value,document.getElementById('yvalor').value)></TD>
				</TR><TR>
					<TD align=right><B>Name: </B></TD>
					<TD colspan=2><INPUT type=text id=ynome class=text size=18></TD>
				</TR><TR>
					<TD colspan=3 align=center><B>Options: </B>(o1,o2)<Br><textarea id=yvalor style=width:150></textarea></TD>
				</TR></form>
				</TABLE></TD>
			</TR>
		</TABLE>

		<!--55 Anexo-->
		<TABLE cellspacing=0 border=0 class='camada cor3' id=f55><form name=c55>
			<TR name=barra id=barra>
				<TD align=center><B>File</B></TD>
				<TD align=right><IMG src=i/clo.gif onclick=ferramentas(55,1,event)></TD>
			</TR><TR>
				<TD colspan=2 align=right><B>Name:</B> <INPUT type=text id=nnome class=text size=17><IMG src=i/save.gif align=absmiddle onclick=textoi(55,0,document.getElementById('nnome').value,0)></TD>
			</TR></form>
		</TABLE>
		
		<!--aviso - AVISO -->
		<TABLE border=0 class='camada cor3' style=z-index:5001 cellspacing=0 id=aviso width=250>
			<TR id=barra name=barra>
				<TD><B>Notice</B></TD>
				<TD align=right><IMG src=i/clo.gif style=cursor:hand onclick=fechaviso()></TD>
			</TR><TR>
				<TD colspan=2 align=center height=100 style=color:dimgray;font-weight:bold id=mensagem></TD>
			</TR>
		</TABLE>

		<IFRAME name=saveme frameborder=0 style=width:0;height:0></IFRAME>
		<SCRIPT>
			window.load=load()
			classe()
		</SCRIPT>
		<SPAN id=jok style=position:absolute;top:-5000;left:-5000;display:none></SPAN>
		<DIV class=cor1 style='position:absolute;top:0;left:0;display:none;width:100%;height:100%;z-index:5000;text-align:right;-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=50);filter:alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5' id=jax></DIV>
	</BODY>
</HTML>";
		}
	}
} else {
	header ("Location: index.php"); 
}
?>