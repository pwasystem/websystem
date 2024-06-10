<?PHP
include "dados.php";
$mysql->exec("delete from usuarios where usuario is null or usuario=''");
if ($l>0&&$per[14]==1){
	if (isset($_GET['listar'])){
		$r='' ;
		$e=20;
		$g=isset($_GET['g'])?$_GET['g']:(isset($_COOKIE['g']) ? $_COOKIE['g']:'');
		setcookie("z",$z,0);
		$ze=$g;
		$g=!empty($g)?"and nome like '%$g%' or rg='$g'":'';
		$g=isset($_GET['g'])?(!empty($_GET['g'])?"and nome like '%$_GET[g]%' or rg='$_GET[g]%'":''):'';
		extract($mysql->query("select count(id) total from usuarios where 1=1 $g")->fetch(PDO::FETCH_ASSOC));
		if (!empty($g))foreach($mysql->query("select id,nome from usuarios where 1=1 $g order by nome limit $i,$e") as $a) $r.="<option value='$a[0]'>$a[1]";			
		$r= empty($r)?"<input type=button class=button onclick=\"window.location='?listar=$_GET[listar]&i=$i&g='\" value='ok'><input type=button onclick=parent.usua(false) value=' x ' class=button> Sem Resultados":"<span class=controle>".($total>$e?($i==0?'<input type=button value=" x " disabled class=button>':"<input type=button value=' < ' onclick=window.location='?listar=$_GET[listar]&i=".($i-$e)."&g=".$ze."' class=button>")."".(($i+$e)>=$total?'<input type=button value=" x " disabled class=button>':"<input type=button value=' > ' onclick=window.location='?listar=$_GET[listar]&i=".($i+$e)."&g=".$ze."' class=button>"):'')."</span> <input type=button class=button style='text-decoration:none' onclick=\"parent.document.form.banner.value=document.getElementById('usuarios').value;parent.us.innerHTML='<A href=#nus name=nus onclick=parent.usua(true)>'+document.getElementById('usuarios').options[document.getElementById('usuarios').selectedIndex].text+'</A>  <IMG src=i/del.gif align=absmiddle onclick=usua(true,1)>'\" value='ok'><input type=button class=button onclick=parent.usua(false) value=' x '> <select id=usuarios align=absmiddle style=width:200>$r</select>";
		// retorna pagina
		$m=(empty($g)?"<input type=text id=b class=text style=width:200><input type=button class=button onclick=\"window.location='?id=$id&listar=$_GET[listar]&g='+document.getElementById('b').value\" value='ok'><input type=button onclick=parent.usua(false) value=' x ' class=button>":$r);
		die("<link rel=stylesheet href=../_gravar/css.css>$m");	
	}
	$f=(isset($_GET['f'])?$_GET['f']:(isset($_COOKIE['f'])?$_COOKIE['f']:0)); //nivel do usuário
	setcookie("f",$f,0);
	//gera arrays javascript
	function ls($qr){
		$r0='';
		$r1='';
		$r2='';		
		foreach($GLOBALS['mysql']->query($qr) as $s){
			$r0.="$s[0],";
			$r1.="$s[1],";
			$r2.="<option value=$s[0]>$s[1]";
		}
		return substr($r0,0,-1)."|".substr($r1,0,-1)."|".$r2;
	}	
	function trata($texto){
		return addslashes(html_entity_decode($texto,ENT_QUOTES,'ISO8859-1'));		
	}
	//exclui
	if (isset($_GET['deleta'])){
		$mysql->exec("delete from usuarios where id=$_GET[deleta]");
		$mysql->exec("delete from acesso where usuario=$_GET[deleta]");
	}
	//updates
	$update=explode(',','usuario,email,nome,level,senha,habilitado');
	foreach($update as $ii){
		if (isset($_GET[$ii])){
			$vr=$_GET[$ii];
			if (($ii=='level'||$ii=='habilitado')&&$id==1)die();
			!empty($_GET[$ii])?$mysql->exec("update usuarios set ".($ii=='level'?'nivel':$ii)."='".($ii=='senha'?md5(vr):($ii=='habilitado'?($vr=='true'?1:0):$vr))."' where id=$id"):null;
			die('ok');
		}
	}
	//lista niveis
	$niveis="";	
	$io=explode('|',ls("select id,nome from nivel order by nome"));	
	foreach($mysql->query("select id,nome from nivel ".($n!=1?"where id!=1":"")." order by nome") as $sn)$niveis.="<option value=$sn[0]>$sn[1]";
	//dias e meses
	$dias="<option value=01>1<option value=02>2<option value=03>3<option value=04>4<option value=05>5<option value=06>6<option value=07>7<option value=08>8<option value=09>9<option value=10>10<option value=11>11<option value=12>12<option value=13>13<option value=14>14<option value=15>15<option value=16>16<option value=17>17<option value=18>18<option value=19>19<option value=20>20<option value=21>21<option value=22>22<option value=23>23<option value=24>24<option value=25>25<option value=26>26<option value=27>27<option value=28>28<option value=29>29<option value=30>30<option value=31>31";
	$meses="<option value=01>1<option value=02>2<option value=03>3<option value=04>4<option value=05>5<option value=06>6<option value=07>7<option value=08>8<option value=09>9<option value=10>10<option value=11>11<option value=12>12";
	//Exibe formulário de Cadastro
	if ($z==1){
		$err='';
		if (isset($_GET['g'])){
			extract($_POST);
			$b=$mysql->query("select id from usuarios where usuario='$_POST[usuario]' ".($id>0?"and id!=$id":''))->fetch(PDO::FETCH_NUM);
			if (empty($b[0])) {
				if ($id>0){//executa update					
					$mysql->exec("update usuarios set nivel='".($id!=1?$nivel:1)."',usuario='$usuario',email='$email',banner=".(empty($banner)?'null':$banner).",nome='$nome',telefone='$fone',cpf='$cic',rg='$rg',nascimento='".(empty($ano)?'0000':$ano)."-$mes-$dia',sexo='$sexo',rua='$rua',bairro='$bairro', cep='$cep', cidade='$cidade', estado='$estado', razao='$razao',fantasia='$fantasia',cnpj='$cnpj',insc='$insc', enderecoe='$enderecoe',bairroe='$bairroe',cidadee='$cidadee',estadoe='$estadoe',cepe='$cepe',fonee='$fonee',fax='$fax',emaile='$emaile',site='$site',extra='".trata($extra)."',coordenada1='$coordenada1',coordenada2='$coordenada2' where id=$id");
					!empty($senha)?$mysql->exec("update usuarios set senha=md5('$senha') where id=$id"):'';
					$err="<tr><td colspan=2 align=center>Os dados foram alterados com êxito.</td></tr>";
					} else {//grava novo					
						$mysql->exec("insert into usuarios(nivel,usuario,senha,email,banner,nome,data,telefone,habilitado,cpf,rg,nascimento,sexo,rua,bairro,cep,cidade,estado,razao,fantasia,insc,cnpj,enderecoe,bairroe,cidadee,estadoe,cepe,fonee,fax,emaile,site,extra,coordenada1,coordenada2) values('$nivel','$usuario',md5('$senha'),'$email',".(empty($banner)?'null':$banner).",'$nome',now(),'$fone',1,'$cic','$rg','".(empty($ano)?'0000':$ano)."-$mes-$dia','$sexo','$rua','$bairro','$cep','$cidade','$estado','$razao', '$fantasia','$insc','$cnpj','$enderecoe','$bairroe', '$cidadee','$estadoe','$cepe','$fonee','$fax','$emaile', '$site','".trata($extra)."','$coordenada1','$coordenada2')");
						$err="<tr><td colspan=2 align=center>Data were registered successfully.</td></tr>";
						$id=$mysql->lastInsertId();
				}
			} else {	
				$err="<tr><td colspan=2 align=center>The user is already registered in our system.</td></tr>";
			}
		}		
		$a=$id>0?$mysql->query("select id, usuario, email, nome, telefone, cpf, rg, nascimento, sexo, rua, bairro, cep, cidade, estado, nivel, razao, fantasia, insc, cnpj, enderecoe, bairroe, cidadee, estadoe, cepe, fonee, fax, emaile, site, extra, banner, coordenada1, coordenada2 from usuarios where id=$id")->fetch(PDO::FETCH_NUM):0;
		if (isset($_GET['g'])){
			unset($a);
			$a=array(0,$usuario,$email,$nome,$fone,$cic,$rg,"$ano-$mes-$dia",$sexo,$rua,$bairro,$cep,$cidade,$estado,$nivel,$razao,$fantasia,$insc,$cnpj,$enderecoe,$bairroe,$cidadee,$estadoe,$cepe,$fonee,$fax,$emaile,$site,$extra,$banner,$coordenada1,$coordenada2);
		}
		$dis=$mysql->query("SELECT nome FROM usuarios WHERE id='$a[29]'")->fetch(PDO::FETCH_NUM);
		$h=$a!=0?explode('-',$a[7]):'';
		$m="
<SCRIPT src=http://maps.google.com/maps/api/js?sensor=false></SCRIPT>
<SCRIPT>
	function testa(){
		f=document.form
		if(!f.nome.value) {avisar('The name field is required',1);return false}
		if(mail(f.email.value)||!f.email.value){avisar('The E-mail field is required',1);return false}
		if(numero(f.cep.value)&&f.cep.value){avisar('The zip code field should only contain numbers',1);return false}
		if(!f.usuario.value) {avisar('The user is mandatory',1);return false}		
		if(f.senha.value&&f.senha.value!=f.senhac.value".($a==0?'||!f.senha.value':'').") {avisar('The password and the confirmation field must contain identical information',1);return false}
		if(numero(f.cepe.value)&&f.cepe.value){avisar('The zip code field should only contain numbers',1);return false}
		if(mail(f.emaile.value)&&f.emaile.value){avisar('The Email field in enterprise data is not populated correctly',1);return false}
		return true
	}
	var local
	function mapa(loca,camada,e) {
		local=loca
		coordenada=$(local).value
		var lat=(coordenada==''?'1,1':coordenada).split(',')
		var latlng = new google.maps.LatLng(lat[0],lat[1])
		var myOptions = {zoom: (coordenada==''?1:17),center: latlng,mapTypeId: google.maps.MapTypeId.HYBRID}
		map = new google.maps.Map($('map_canvas'), myOptions)		
		google.maps.event.addListener(map,'click', function(event) {placeMarker(event.latLng)});
		if (coordenada!='') marker = new google.maps.Marker({position: latlng ,map: map});
		ver(camada,e)
	}
	function placeMarker(location) {	
		if (marker) marker.setMap(null);	
		var clickedLocation = new google.maps.LatLng(location);
		marker = new google.maps.Marker({position: location,map: map});
		map.setCenter(location);
		x=location+''
		y=x.substring(1,x.length-2).split(',')
		$(local).value=y[0].substring(0,10)+','+y[1].substring(1,11)
	}
	ue='<iframe src=?listar=$a[29]&g= scrolling=no height=21 width=100% frameborder=0 marginheight=0 marginwidth=0 name=cli></iframe>'	
	function usua(usu,tum){
		if (usu){
			if (tum==1) {
				document.form.banner.value=''
				$('us').innerHTML='<iframe src=?listar=$a[29]&g= scrolling=no height=21 width=100% frameborder=0 marginheight=0 marginwidth=0 name=cli></iframe>'
			}
			ue=$('us').innerHTML
			$('us').innerHTML='<iframe src=?listar=$a[29]&g= scrolling=no height=21 width=100% frameborder=0 marginheight=0 marginwidth=0 name=cli></iframe>'
		} else {
			$('us').innerHTML=ue
		}
	}	
</SCRIPT>
<TABLE border=0 cellspacing=0 name=maas id=maas class='camada cor3' width=450>
	<TR id=barra name=barra class=cor4>
		<TD><B>Map</B></TD>
		<TD align=right><IMG src=i/clo.gif name=fecha id=fecha></TD>
	</TR><TR>
		<TD colspan=2><DIV id=map_canvas style=width:450;height:450></DIV></TD>
	</TR>
</TABLE>


<table cellpadding=0 cellspacing=0 border=0 width=100%>
	<tr>
		<td colspan=2 align=center class=cor3><b>".($a==0?'Create new account':"Edit user data")."</b><br></td>
	<tr>
		<td><table cellpadding=0 border=0 width=100%>
				$err<tr>
					<td colspan=2>&nbsp;</td>
				</tr><tr><form method=post name=form action=?z=1&id=$id&i=$i&g=1 onsubmit='return testa()'>
					<td align=right><b>Level:</b></td>
					<td><select name=nivel>".str_replace("value=$a[14]>","value=$a[14] selected>",$niveis)."</select></td>
				</tr><tr>
					<td align=right><b>Name:</b></td>
					<td><input type=text name=nome size=60 class=text maxlength=150 onclick=this.select() value='$a[3]'></td>
				</tr><tr>
					<td align=right><b>Birth:</b></td>
					<td><select name=dia>".($a!=0?str_replace("value=$h[2]>","value=$h[2] selected>",$dias):$dias)."</select>/<select name=mes>".($a!=0?str_replace("value=$h[1]>","value=$h[1] selected>",$meses):$meses)."</select>/<input type=text name=ano size=4 class=text maxlength=4 onclick=this.select() value='".($a!=0? $h[0]:'')."'> <b>Sex: </b> <input type=radio name=sexo class=radio value=0 ".($a==0?'checked': $a[8]==0?'checked':'').">Male <input type=radio name=sexo class=radio value=1 ".($a==0?'':$a[8]==1?'checked':'').">Female</td>
				</tr><tr>
					<td align=right><b>Document 1:</b></td>
					<td><input type=text name=cic size=19 class=text maxlength=250 onclick=this.select() value='$a[5]'> <b>Document 2: </b><input type=text name=rg size=19 class=text maxlength=250 onclick=this.select() value='$a[6]'></td>
				</tr><tr>
					<td align=right><b>E-mail:</b></td>
					<td><input type=text name=email size=60 class=text maxlength=50 onclick=this.select() value='$a[2]'></td>
				</tr><tr>
					<td align=right><b>Phone:</b></td>
					<td><input type=text name=fone size=60 class=text maxlength=250 onclick=this.select() value='$a[4]'></td>
				</tr><tr>
					<td align=right><b>Address:</b></td>
					<td><input type=text name=rua size=60 class=text maxlength=100 onclick=this.select() value='$a[9]'></td>
				</tr><tr>
					<td align=right><b>District:</b></td>
					<td><input type=text name=bairro size=17 class=text maxlength=50 onclick=this.select() value='$a[10]'> <b>ZIP:</b> <input type=text name=cep size=8 class=text maxlength=15 onclick=this.select() value='$a[11]'> <B>Map:</b> <INPUT type=text class=text size=9 name=coordenada1 id=coordenada1 value='$a[30]'> <img src=i/idi.gif style=cursor:hand align=absmiddle onclick=\"mapa('coordenada1','maas',event)\"> </td>
					</tr><tr>
						<td align=right><b>Cit:</b></td>
						<td><input type=text name=cidade size=40 class=text maxlength=50 onclick=this.select() value='$a[12]'> <b>State: </b> <input type=text name=estado size=7 class=text maxlength=100 onclick=this.select() value='$a[13]'></td>
					</tr><tr>
						<td align=right><b>User:</b></td>
						<td><input type=text name=usuario size=20 class=text maxlength=20 onclick=this.select() ".($a!=0?"value='$a[1]'":'')."></td>
					</tr><tr>
						<td align=right><b>Password:</b></td>
						<td><input type=password name=senha size=19 class=text maxlength=20> <b>Confirmation:</b><input type=password name=senhac size=19 class=text maxlength=20></td>
					</tr><tr>
						<td colspan=2 class='cor2 ' align=center><b>Business</b></td>
					</tr><tr>
						<td align=right><b>Corporate:</b></td>
						<td><input type=text name=razao value='$a[15]' size=60 class=text maxlength=250 onclick=this.select()></td>
					</tr><tr>
						<td align=right><b>Company:</b></td>
						<td><input type=text name=fantasia value='$a[16]' size=60 class=text maxlength=250 onclick=this.select()></td>
					</tr><tr>
						<td align=right><b>Document 1:</b></td>
						<td><input type=text name=insc value='$a[17]' size=19 class=text maxlength=250 onclick=this.select()> <b>Document 2:</b> <input type=text name=cnpj value='$a[18]' size=19 class=text maxlength=250 onclick=this.select()></td>
					</tr><tr>
						<td align=right><b>Address:</b></td>
						<td><input type=text name=enderecoe value='$a[19]' size=60 class=text maxlength=250 onclick=this.select()></td>
					</tr><tr>
						<td align=right><b>District:</b></td>
						<td><input type=text name=bairroe size=17 class=text maxlength=50 onclick=this.select() ".($a!=0?"value='$a[20]'":'')."> <b>Zip:</b> <input type=text name=cepe size=8 class=text maxlength=15 onclick=this.select() ".($a!=0?"value='$a[23]'":'')."> <B>Map:</B> <INPUT type=text class=text size=9 name=coordenada2 id=coordenada2 value='$a[31]'> <img src=i/idi.gif style=cursor:hand align=absmiddle onclick=\"mapa('coordenada2','maas',event)\"> </td>
					</tr><tr>
						<td align=right><b>Cit:</b></td>
						<td><input type=text name=cidadee size=40 class=text maxlength=50 onclick=this.select() ".($a!=0?"value='$a[21]'":'')."> <b>State: </b>  <input type=text name=estadoe size=7 class=text maxlength=100 onclick=this.select() value='$a[22]'></td>
					</tr><tr>
						<td align=right><b>Phone:</b></td>
						<td><input type=text name=fonee value='$a[24]' size=60 class=text maxlength=250 onclick=this.select()></td>
					</tr><tr>
						<td align=right><b>Fax:</b></td>
						<td><input type=text name=fax value='$a[25]' size=60 class=text maxlength=250 onclick=this.select()></td>
					</tr><tr>
						<td align=right><b>E-mail:</b></td>
						<td><input type=text name=emaile value='$a[26]' size=60 class=text maxlength=60 onclick=this.select()></td>
					</tr><tr>
						<td align=right><b>Site:</b></td>
						<td><input type=text name=site value='$a[27]' size=60 class=text maxlength=50 onclick=this.select()></td>
					</tr><tr>
						<td colspan=2 class='cor2 ' align=center><b>Extra</b></td>
					</tr><tr valign=top>
						<td align=right><b>Boss:</b></td>
						<td><input type=hidden name=banner value='$a[29]'>
						<SPAN id=us style=width:100%>".(isset($dis[0])?"<A href=#nus name=nus onclick=usua(true)>$dis[0]</A> <IMG src=i/del.gif align=absmiddle onclick=usua(true,1)>":"<IFRAME src=?id=$id&listar=$a[29] scrolling=no height=21 width=100% frameborder=0 marginheight=0 marginwidth=0></IFRAME>")."</SPAN>
						</td>
					</tr><tr valign=top>
						<td align=right><b>Additional data:</b></td>
						<td><textarea name=extra cols=59 rows=5>".htmlspecialchars($a[28],ENT_QUOTES,'ISO8859-1')."</textarea></td>
					</tr><tr>
						<td colspan=2>&nbsp;</td>
					</tr><tr>
						<td align=center colspan=2 class=cor3><input type=submit class=button value=".($a!=0?'Save':'Register')."><input type=button value=Cancel class=button onclick=\"window.location='?id=$id&z=0&i=$i'\"></td>
					</tr></form>
				</table>
			</td>";
			
	} else {//Exibe controle de usuários
		$w=empty($w)?'':"and (nome like'%$w%' or usuario like'%$w%')";
		$filtro=str_replace("value=$f>","value=$f selected>",$niveis);
		$fil=$f>0?"and nivel=$f":'';
		extract($mysql->query("select count(id) total from usuarios where id>0 $w $fil")->fetch(PDO::FETCH_ASSOC));				
		foreach($mysql->query("select id,nivel,usuario,email,nome,habilitado from usuarios where id>0 $w $fil order by nivel,usuario limit $i,$e") as $a){
			$m.= $a[1]==1&&$n!=1?'':"
<form method=post action=usuarios.php?id=$a[0]>
<tr name=flip id=flip class=".($k=$k=='cor2' ? 'cor1':'cor2').">
	<td><span id=nome$a[0]><a href=\"javascript:c($a[0],'nome','$a[4]',30,50,1)\">$a[4]</a></span></td>
	<td><span id=usuario$a[0]><a href=\"javascript:c($a[0],'usuario','$a[2]',20,15,1)\">$a[2]</a></span></td>
	<td><span id=senha$a[0]><a href=\"javascript:c($a[0],'senha','********',20,15,1)\">********</a></span></td>
	<td><a href=mailto:$a[3]><img src=i/mai.gif border=0 title='Send e-mail to user' align=absmiddle></a>&nbsp;<span id=email$a[0]><a href=\"javascript:c($a[0],'email','$a[3]',30,60,1)\">$a[3]</a></span></td>
	<td align=center><input type=checkbox name=habilitado ".($a[0]==1?'disabled':'')." ".($a[5]==0?'':'checked')." onclick=\"carrega('?id=$a[0]&i=$i&habilitado='+this.checked,'jax')\"></td>
	<td align=center><a href=?z=1&id=$a[0]&f=$f><img src=i/dat.gif border=0 title='Edit user profile' align=absmiddle></a></td>
	<td align=center><input type=hidden id=level$a[0] value=$a[1]><img title='Change level of user' src=i/niv.gif border=0 style=cursor:hand onclick=\"men($a[0],parseInt($('level$a[0]').value),i1,'level',0,0,0,i0,1,'Level',event)\"></td>
	<td align=center>".($a[0]==1?'&nbsp;':"<a href=\"javascript:posta($a[0],$a[0],'apaga')\"><img src=i/del.gif border=0></a>")."</td>
</tr>
</form>";
		}
		$m= "
<script>
i0='$io[0]'.split(',')
i1='$io[1]'.split(',')
</script>

<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr class=cor3>
		<td><b>Name</b></td>
		<td><b>User</b></td>
		<td><b>Password</b></td>
		<td><b>E-mail</b></td>
		<td align=center><img src=i/hab.gif></td>
		<td colspan=3><br></td>
	</tr>
		$m
	<tr>
		<td align=center colspan=8 class=cor3 class=controle>".($total>$e?($i==0?'x':"<a href=?i=".($i-$e)."&id=$id&f=$f class=controle>Back</a>")."|".(($i+$e)>=$total?'x':"<a href=?i=".($i+$e)."&id=$id&f=$f class=controle>Next</a>"):'<BR>')."</td>
	</tr>
</table>
<!--BARRA 2 - Seleção -->
<table border=0 cellpadding=0 cellspacing=0 id=barra2 class='camada cor3'>
<tr>
	<td colspan=2 name=barra id=barra>&nbsp;<b><span id=labe></span>:</b></td>
	<td><select name=menux id=meu onchange=\"if(mu!=1)$(ir+''+mu).value=this.value;carrega('?id='+mu+'&'+ir+'='+this.value,'jax');ver_n('barra2')\" style=width:150></select></td>
	<td><input type=button value=' x ' class=button name=fecha></td>
</tr>
</table>";	
	}
	echo "$inicio_pagina
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr class=cor4>
		<td><table border=0 cellpadding=0 cellspacing=0 width=100% >
			<tr>
				<td><a href=?z=1&id=0>&nbsp;<img src=i/usu.gif border=0 border=0 align=absmiddle title='Create new user'></a> <a href=\"javascript:w_usuario(0,0)\"><b>User</b></a> <span id=wdor></span></td>
				<td align=right>".($z>0?'':"<b>View user level: </b><select name=filtro onchange=\"window.location='?id=$id&i=$i&f='+this.value\"&w=$w><option value=0>All$filtro</select>")."</td>
			</tr>
		</table></td>
	</tr><tr>
		<td>$m</td>
	</tr>
</table>
$final_pagina";
} else {
	header ("Location: index.php"); 
}
?>