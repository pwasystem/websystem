<?PHP
include'dados.php';
if ($l>0&&$per[26]==1){
	//Salva variaveis
	//query bkp
	if(isset($_POST['q']))$_POST['q']=clui($_POST['q']);
	$tq=isset($_POST['q'])?(isset($_COOKIE['q'])?$_COOKIE['q']:$_POST['q']):'SHOW DATABASES;';
	//query
	$q=stripcslashes(isset($_POST['q'])?$_POST['q']:(isset($_COOKIE['q'])?$_COOKIE['q']:'SHOW DATABASES;'));
	 //db
	$db=isset($_GET['db'])?(!empty($_GET['db'])?$_GET['db']:(isset($_COOKIE['db'])?$_COOKIE['db']:'')):(isset($_COOKIE['db'])?$_COOKIE['db']:'');
	setcookie("tq",$tq);
	setcookie("q",$q);
	setcookie("db",$db);
	if(!empty($db))$mysql->exec("USE $db");
	//Importa arquivo
	if (isset($_FILES['file']))if($_FILES['file']['size']>0)$mysql->exec(file_get_contents($_FILES['file']['tmp_name']));
	//Exporta Tabela
	if (isset($_GET['ex'])){
		$ex=explode('|',$_GET['ex']);
		$cx=explode(',',$ex[3]);
		$ret=($ex[4]==1?"USE $db;\n":'');
		$dt=($ex[4]==1?"$db.":'');
		for ($j=0;$j<count($cx)-1;$j++){
			$cot="";
			$mt=$mysql->query("SHOW CREATE TABLE $db.$cx[$j]")->fetch(PDO::FETCH_NUM);
			if ($ex[1]>0){
				$pri='';
				foreach($pesqui=$mysql->query("SHOW FIELDS FROM $db.$cx[$j]",PDO::FETCH_NUM) as $dados){
					if($dados[3]=='PRI')$pri=$dados[0];
					$indices[$dados[0]]=$dados;
				}
				foreach($mysql->query("SELECT * FROM $db.$cx[$j]",PDO::FETCH_ASSOC) as $uc){
					$values='';
					foreach ($uc as $nm => $ct){
						if($indices[$nm][2]=='NO'&&empty($ct)&&!is_numeric($ct)){
							if(!empty($indices[$nm][4])){
								$ct=$indices[$nm][4];
							} else {
								$tc=$indices[$nm][1];
								if(strstr($tc,'int')||strstr($tc,'decimal')||strstr($tc,'double')) {
									$ct="'0'";
								}elseif($tc=='date'){
									$ct="'0000-00-00'";
								}elseif($tc=='datetime'||$tc=='timestamp'){
									$ct="'0000-00-00 00:00:00'";
								}elseif($tc=='time'){
									$ct="'00:00:00'";
								}elseif($tc=='year'){
									$ct="'0000'";
								}else{
									$ct="' '";
								}
							}							
						}elseif(empty($ct)&&!is_numeric($ct)){
							$ct='NULL';
						}else{
							$ct="'".addslashes($ct)."'";
						}						
						$pri==$nm?$we="WHERE $pri=$ct":'';						
						$values.=($ex[6]==0?$ct:"$nm=$ct").",";						
					}
					$values=substr($values,0,-1);
					if($ex[2]==0)$values=htmlentities($values,ENT_QUOTES,'UTF-8');
					$cot.=($ex[6]==0?"INSERT INTO $dt$cx[$j] VALUES($values);\n":"UPDATE $dt$cx[$j] set $values $we;\n");
				}
			}
			$ret.=($ex[5]==1?"DROP TABLE IF EXISTS $dt$cx[$j];\n":'').($ex[0]==0?'':"$mt[1];\n").(isset($cot)?$cot:null)."\n";
		}
		if ($ex[2]==0) {
			$por="<PRE style='font:10px verdana'>$ret</PRE>";
		} else {
			header("Content-type: text/plain; charset=utf-8"); 
			header("Content-disposition: attachment; filename=$db.sql");
			die(utf8_encode($ret));
		}
	}
//----------------------------------------------------------------------Executa Consulta----------------------------------------------------------------------------//
	if (!empty($q)){
		$executou='';
		$q=stripcslashes($q);
		$a=explode(";\n",substr($q,-1,1)==";"?substr($q,0,-1):$q);
		$cex="";
		for ($g=0;$g<count($a);$g++){
			$m.='<table border=0 bordercolor=blue cellspacing=0 cellpadding=1 width=100% align=center>';
			$ec=$mysql->query($a[$g],PDO::FETCH_NUM);
			$erro=$mysql->errorInfo();
			$errou=$erro[2];
			$gat=0;
			if (substr($a[$g],0,1)!='#'){
				if (empty($errou)){
					//executa uma query
					if ($ec&&!empty($ec)) {
						$nome='';
						$campos='';
						//colunas
						$col=$ec->columnCount();
						//campos usados
						for ($j=0;$j<$col;$j++){
							$n=$ec->getColumnMeta($j);
							$campos.="$n[name],";
							$nome.="<td><b>$n[name]</B></td>";
							$name[$j]=$n['name'];
							$ky=in_array('primary_key',$n['flags'])?$j:'';
						}
//---------------------------------------Antes Consulta-------------------------------------------//
						//Show Databases
						if (stristr($a[$g],'show databases')){
							$m.="<tr class=cor3>
								<td colspan=2><b>Databases</b> <input type=text id=banco class=text> <input type=button value=ok class=button onclick=\"$('banco').value?SQL('CREATE DATABASE '+$('banco').value+';',1):null\"></td>
							</tr>";
						//Show Tables
						} else if (!empty($db)&&stristr($a[$g],'show tables')){
							$gat=1;
							$cex.=str_replace('',';',$db).'="';
							$m.="<tr class=cor3>
								<td colspan=2><img src=i/save.gif align=absmiddle border=0 title='Export tables' onclick=\"exportar(1,".str_replace(' ','_',$db).",'$db',event)\" style=cursor:hand><img src=i/new.gif align=absmiddle border=0 style=cursor:hand title='New table' onclick=\"ver('tanova',event);ver_n('ta2');ver_n('ta1')\">
								<b>Tables in $db</b>
								</td>
							</tr>";
						//Show Fields
						} else if (!empty($db)&&stristr($a[$g],'show fields')){
							$table=str_replace(';','',substr(trim($a[$g]),16));
							$tp=0;							
							foreach($mysql->query("SHOW KEYS FROM $table",PDO::FETCH_NUM) as $ky){
								$tk=$ky[2]=='PRIMARY'?'primary_key':(isset($ky[10])&&$ky[10]=='FULLTEXT'?'fulltext':($ky[1]==0?'unique':'index'));
								isset($ks[$ky[4]]) ? $ks[$ky[4]].="$ky[2]:$tk;":$ks[$ky[4]]="$ky[2]:$tk;";
								isset($kx[$ky[4]]) ? $kx[$ky[4]].=" $tk":$kx[$ky[4]]=" $tk";
								$ky[2]=='PRIMARY'?$tp=1:null;
							}
							$m.="<tr>
								<td class=cor3><img src=i/print.gif border=0 align=absmiddle  title='Print information' onclick=imprime(imp$g) style=cursor:hand><img src=i/new.gif align=absmiddle border=0 title='New field' style=cursor:hand onclick=tabren(0,event)></td>
								</tr><tr>
									<td><table border=0 width=100% id=imp$g>
									<tr>
										<td colspan=".($col+1)." class=cor3 align=center><B>Table structure $table</b></td>
									</tr><tr class=cor3>
										$nome
										<td width=40><br></td>
									</tr>";
						//Select
						} else if (!empty($db)&&stristr($a[$g],'select')&&stristr($a[$g],'from')){
							//Identifica variaveis
							$cm=array('SELECT','FROM','WHERE','GROUP BY','HAVING','ORDER BY','LIMIT','PROCEDURE','FOR UPDATE','LOCK IN SHARE MODE');
							$lk=strtoupper($a[$g]);
							$wf='';
							unset($po,$pi);
							for ($j=0;$j<count($cm);$j++){
								$pi[]=strstr($lk,$cm[$j])?(strpos($lk,$cm[$j])+(strlen($cm[$j])+1)) : 0 ;
								for ($jj=($j+1);$jj<count($cm);$jj++){
									if (strpos($lk,$cm[$jj])>0){
										$po[]=strpos($lk,$cm[$jj]);
										break;
									}
								}
								$pi[$j]==0 ? $po[$j]=0:null;
								isset($po[$j])?null:$po[]=0;
								$nm=strtolower(str_replace(' ','_',$cm[$j]));
								$$nm=trim($po[$j]-$pi[$j]<0? substr($a[$g],$pi[$j]):substr($a[$g],$pi[$j],$po[$j]-$pi[$j]));
							}
							//executa paginação
							$vx='<br>';
							if (!empty($limit)){
								extract($mysql->query("SELECT COUNT(*) total FROM $from ".(empty($where)?'':"WHERE $where"))->fetch(PDO::FETCH_ASSOC));
								$li=explode(',',$limit);
								isset($li[1])?'':$li[1]=0;
								$va=str_replace($limit,($li[0]+$li[1].($li[1]>0?",$li[1]":",$li[0]")),$q);
								$vt=str_replace($limit,((($li[0]-$li[1])<0?'0':$li[0]-$li[1]).($li[1]>0?",$li[1]":'')),$q);
								$vx=$total>$li[1]?($li[0]<=0||$li[1]==0?'x':"<a onclick=\"SQL('$vt',0)\" class=controle style=cursor:hand>Back</a>")."|".(($li[0]+$li[1])>=$total?'x':"<a onclick=\"SQL('$va',0)\" class=controle style=cursor:hand>Next</a>"):'<br>';
							}
							//Cria checkbox das tabelas
							$exibir='';	
							$tab=explode(' ',$from);
							unset($tx);
							$tx[0]=$tab[0];	
							//Lista tabelas na query
							if (stristr($a[$g],'join')){
								for($j=0;$j<count($tab);$j++){
									$tab[$j]=='join'?$tx[]=$tab[$j+1]:null;
								}
							}
							//Cria caixas
							$exibir.='<table border=0 cellpadding=0 cellspacing=0>';
							for ($j=0;$j<count($tx);$j++){
								$exibir.="<tr valign=top>
									<td align=right><b>".(strstr($tx[$j],'.')?substr(strstr($tx[$j],'.'),1):$tx[$j]).":</b></td>
									<td>";
								foreach($mysql->query("desc $tx[$j]",PDO::FETCH_NUM) as $te){
									$tl=substr(strstr($tx[$j],'.'),1).'.';
									if (strstr("$select","$tl$te[0]")||$select=='*'){
										$qc=str_replace("$tl$te[0]",'',$select);
										$marca='checked';
									} else if (strstr("$select","$te[0]")){
										$qc=str_replace("$te[0]",'',$select);
										$marca='checked';
									} else {
										$qc="$select$te[0],";
										$marca='';
									}
									$exibir.="<input type=checkbox name=select$g value=".(empty($tx[$j])?'':"$tx[$j].")."$te[0] class=radio $marca>$te[0] ";
								}
								$exibir.="</td>
							</tr>";
							}
							$exibir.="</table>";
							$lm=explode(',',$limit);
							//cabeçalho
							$all="select$g";
							$m.="<tr class=cor3>
								<td><img src=i/print.gif border=0 align=absmiddle onclick=imprime(imp$g) style=cursor:hand title='Print content'><img src=i/csele.gif align=absmiddle style=cursor:hand title='Personalize query' onclick=\"if(ex$g.style.visibility=='visible'){ex$g.style.visibility='hidden';ex$g.style.position='absolute'}else{ex$g.style.visibility='visible';ex$g.style.position='static'}\"><br>
		<span id=ex$g style=visibility:hidden;position:absolute><input type=hidden name=se$g value=\"".$select."\"><input type=hidden name=fr$g value=\"".$from."\">
		<table border=0 cellpadding=0 cellspacing>
			<tr>
				<td align=right><b>Where:&nbsp;</b></td>
				<td><input type=text class=text name=where$g value=\"".$where."\" size=20> <b>Order:</b> <input type=text class=text name=order_by$g value=\"".str_replace('desc','',strtolower($order_by))."\" size=15> <b>Direction: </b><select id=desc$g><option>Ascendant<option value=DESC ".(stristr($order_by,'desc')?'selected':'').">Descendent</select> <b>Begin:</b> <input type=text class=text name=ini$g value=\"".(isset($lm[0])?$lm[0]:'')."\" size=2> <b>View:</b> <input type=text class=text name=exi$g value=\"".(isset($lm[1])?$lm[1]:'')."\" size=2> <input type=button value='ok' class=button onclick=filtra($g)></td>
			</tr><tr valign=top>
				<td align=right><b>Fields:&nbsp;<br><img src=i/ne.gif align=absmiddle style=cursor:hand onclick=\"for(j=0;j<document.getElementsByName('select$g').length;j++)document.getElementsByName('select$g')[j].checked=false\" title='Uncheck all'><img src=i/to.gif align=absmiddle style=cursor:hand onclick=\"for(j=0;j<document.getElementsByName('select$g').length;j++)document.getElementsByName('select$g')[j].checked=true\" title='check all'></b></td>
				<td>$exibir</td>
			</tr>
		</table>
		</span>
		</td>
</tr><tr>
		<td align=center><table border=0 width=100% cellspacing=1 id=imp$g>
			<tr>
				<td colspan=$col align=center class=cor3><b>".(strstr($tx[0],'.')?substr(strstr($tx[0],'.'),1):$tx[0])."</b></td>
			</tr><tr class=cor3 align=center>
				$nome
			</tr>";
						//Não detectado
						} else {
							$m.="<tr>
									<td class=cor3><img src=i/print.gif border=0 align=absmiddle onclick=imprime(imp$g) style=cursor:hand></td>
									</tr><tr>
										<td><table border=0 id=imp$g cellpadding=1 cellspacing=1 width=100%>
											<tr class=cor3>
												<td align=center colspan=$col><b>$a[$g]</b></td>
									</tr><tr class=cor3>
										$nome
									</tr>";
						}
//-----------------------------------------------------Consulta--------------------------------------------------------------//
						$li=0;
						foreach($ec as $re){
							$td='';
							$cp='';
							for ($j=0;$j<count($re);$j++){
								//Show Databases
								if (stristr($a[$g],'show databases')){
									$td.="<td>$re[$j]</td>
									<td align=right><img src=i/tab.gif border=0 align=absmiddle title='Displays the database tables' style=cursor:hand onclick=\"SQL('SHOW TABLES FROM $re[$j];',0,'$re[$j]')\"> <img src=i/log.gif border=0 align=absmiddle title='Database properties' style=cursor:hand onclick=\"SQL('SHOW TABLE STATUS FROM $re[$j];',0,'$re[$j]')\"><a href=# onclick=\"avisar('Drop database: $re[$j]<BR><BR><INPUT type=button class=button value=Yes onclick=&quot;SQL(\'DROP DATABASE $re[$j];\',1)&quot;> <INPUT type=button class=button value=No onclick=fechaviso()>')\"><img src=i/del.gif border=0 align=absmiddle title='Drop database'></a></td>";
								//Show Tables
								} else if (stristr($a[$g],'show tables')){
									$tabelas=(isset($tabelas)?$tabelas:null)."$re[$j],";
									$cex.="<input type=checkbox name=campos value=$re[$j] class=radio checked>$re[$j]<br>";
									$td.="<td>
									<span id=n$j>$re[$j]</span>	
									</td>
										<td align=right><img src=i/dat.gif border=0 align=absmiddle title='Table of contents' onclick=\"SQL('SELECT * FROM $db.$re[$j] LIMIT 0,30;',0)\" style=cursor:hand>
										<img src=i/nlist.gif border=0 align=absmiddle title='Properties of table' onclick=\"SQL('SHOW FIELDS FROM $db.$re[$j];',0)\" style=cursor:hand>
		<img src=i/cad.gif border=0 align=absmiddle title='Rename table' onclick=\"ver('taatu',event),$('nomh').value='$re[$j]';$('noty').value='$re[$j]'\" style=cursor:hand>
		<img src=i/del.gif border=0 align=absmiddle title='Drop table' onclick=\"avisar('Drop table: $re[$j]<BR><BR><INPUT type=button class=button value=Yes onclick=&quot;SQL(\'DROP TABLE $db.$re[$j];\',1)&quot;> <INPUT type=button class=button value=No onclick=fechaviso()>')\" style=cursor:hand>
		</td>";
								//Show Fileds
								} else if (stristr($a[$g],'show fields')){
									$cp.=addslashes("$re[$j]|");
									$re[5].=$j==5&&isset($kx[$re[0]])?$kx[$re[0]]:null;
									$td.="<td>$re[$j]</td>";
								//Select
								} else if (stristr($a[$g],'select')&&stristr($a[$g],'from')){
									$ky=empty($ky)?0:$ky;
									$td.="<td valign=top><span style=cursor:hand onclick=window.location='editor.php?tepo=5&nome=$tx[0]&campo=$name[$j]&id=$re[$ky]'>".(empty($re[$j])?'<img src=i/lap.gif>':nl2br(htmlentities($re[$j])))."</span></td>";
								//Não detectado
								} else {
									$td.="<td>".nl2br($re[$j])."</td>";
								}
							}
							$li++;
//---------------------------------------------------Apos Consulta-------------------------------------------------------------//
							//Show Fields
							if (stristr($a[$g],'show fields')){
								$cp=substr($cp,0,-1)." ".(isset($kx[$re[0]])?$kx[$re[0]]:'')."|".(isset($ks[$re[0]])?$ks[$re[0]]:'')."|$tp";
								$td.="<td align=right><img onclick=\"tabren('$cp',event)\" src=i/edit.gif align=absmiddle border=0 title='Edit field' style=cursor:hand> <img src=i/del.gif border=0 align=absmiddle title='Drop field' onclick=\"avisar('Drop field: $re[0]<BR><BR><INPUT type=button class=button value=Yes onclick=&quot;SQL(\'ALTER TABLE $table DROP $re[0];\',1)&quot;> <INPUT type=button class=button value=No onclick=fechaviso()>')\" style=cursor:hand></td>";
							}
							//Finaliza tabela
							$m.="<tr id=flip name=flip class=".($k=$k=='cor1'?'cor2':'cor1').">
									$td
								</tr>";
							}
							//Select
							 if (stristr($a[$g],'select')&&stristr($a[$g],'from')){
								$m.="<tr class=cor3 align=center>
										<td colspan=$col class=controle>$vx</td>
									</tr>";
							}
					//Retorna comando
					} else {
						$m.="<tr>
								<td>Command executed successfully</td>
							</tr>";
					}
				//Retorn erro
				} else {
					$m.="<tr>
							<td>$errou</td>
						</tr>";
				}
			}
			$m.="
		</table></td>
		</tr>
	</table>
		";
		$cex.=($gat==1?'"
':'');
		}
	}
	$m=stristr($q,'select')?"<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr><form name=fo><input type=hidden name=gg value=$g>
		<td>$m</td>
	</form></tr>
</table>":$m;
echo "$inicio_pagina 
<script>
".(isset($tabelas)?"tabelas='".substr($tabelas,0,-1)."'":'')."
tkp='';	
".(stristr($q,'select')?"//Select
function filtra(){
	r=document.fo
	x=r['gg'].value
	ab=''
	for(g=0;g<x;g++){
		sel='';
		if (r['select'+g]){
			for(j=0;j<r['select'+g].length;j++){
				sel=sel+(r['select'+g][j].checked? (r['select'+g][j].value+',') :'')
			}
		sel = sel=='' ? r['se'+g].value:sel.substr(0,sel.length-1);
		wh=r['where'+g].value? ' WHERE '+r['where'+g].value:'';
		ob=r['order_by'+g].value? ' ORDER BY '+r['order_by'+g].value+' '+$('desc'+g).value:'';
		li=r['ini'+g].value? ' LIMIT '+r['ini'+g].value+(r['exi'+g].value?','+r['exi'+g].value:''):'';
		ab=ab+'SELECT '+sel+' FROM '+r['fr'+g].value+wh+ob+li+';'
		}
	}
	SQL(ab,0)
}":'').(stristr($q,'show fields')?"//Show Fields
function disar(m){
	$('sinal').disabled = m>7? true:false
	$('zeros').disabled = m>7? true:false
	$('auto').disabled = m>7? true:false
	$('binario').disabled = m>12&&m<15 ? false:true
}
function tabren(valores,event){
	ver('barra1',event)
	barra1.style.zIndex='10'
	if (valores!=0){
		$('ino').disabled=false
		$('keys').disabled=false
		$('indi').disabled=false
		$('undu').disabled=false
		$('full').disabled=false
		vr=valores.split('|')
		$('nome').value=vr[0]
		if (vr[1].indexOf('(')=='-1') {
			$('tamanho').value=''
			tp=vr[1]
		} else {
			$('tamanho').value=vr[1].substring(vr[1].indexOf('(')+1,vr[1].indexOf(')'))
			tp=vr[1].substr(0,vr[1].indexOf('('))
		}
ti='tinyint,smallint,mediumint,int,bigint,float,double,decimal,date,datetima,timestamp,time,year,char,varchar,tinytext,text,mediumtext,longtext,enum,set'
		ti=ti.split(',')
		for (j=0;j<ti.length;j++){
			tp==ti[j]? tp=j:''
		}
		disar(tp)
		$('tipo').selectedIndex=tp
		$('sinal').checked=vr[1].match('unsigned')?true:false
		$('zeros').checked=vr[1].match('zerofill')?true:false
		$('keys').checked=vr[5].match('primary_key')?true:false
		$('indi').checked=vr[5].match('index')?true:false
		$('undu').checked=vr[5].match('unique')?true:false
		$('full').checked=vr[5].match('fulltext')?true:false
		$('nulo').checked=vr[2]=='YES'?false:true
		$('padrao').value=vr[4]
		$('auto').checked=vr[5].match('auto_increment')?true:false
		$('auto').disabled=vr[3]=='PRI'?false:true
		$('keys').disabled=vr[7]==1&&!vr[5].match('primary_key')?true:false;
	} else {
		disar(0)
		$('nome').value=''
		$('tamanho').value=''
		$('tipo').selectedIndex=0
		$('sinal').checked=false
		$('zeros').checked=false
		$('nulo').checked=false
		$('padrao').value=''
		$('auto').checked=false
		$('ino').disabled=true
		$('keys').disabled=true
		$('indi').disabled=true
		$('undu').disabled=true
		$('full').disabled=true
	}
	$('vv').value=valores
}
function ncam(){
	comu=$('nome').value+' '+$('tipo').value+($('tamanho').value? '('+$('tamanho').value+')' :'')+(!$('binario').disabled&&$('binario').checked?' BINARY':'')+(!$('sinal').disabled&&$('sinal').checked?' UNSIGNED':'')+(!$('zeros').disabled&&$('zeros').checked?' ZEROFILL':'')+(!$('padrao').disabled&&$('padrao').value?' DEFAULT \"'+$('padrao').value+'\"':'')+($('nulo').checked?' NOT NULL':'')+(!$('auto').disabled&&$('auto').checked?' AUTO_INCREMENT':'')
	if ($('vv').value==0){
	 	mu=' ADD '+comu
	} else {
		vr=$('vv').value.split('|')
		mu=''
		ia=vr[6].split(';')
		pk=0
		un=0
		ie=0
		fu=0
		for (j=0;j<ia.length-1;j++){
			ix=ia[j].split(':')
			ix[1]=='primary_key'?pk=1:null
			ix[1]=='unique'?un=' DROP INDEX '+ix[0]:null
			ix[1]=='index'?ie=' DROP INDEX '+ix[0]:null
			ix[1]=='fulltext'?fu=' DROP INDEX '+ix[0]:null
		}
		mu+=!$('keys').disabled? ($('keys').checked? (pk==0?' ADD PRIMARY KEY('+$('nome').value+');\\nALTER TABLE $table':''):(pk==1?' DROP PRIMARY KEY;\\nALTER TABLE $table':'')):'';
		mu+=$('undu').checked? (un==0?' ADD UNIQUE '+($('ino').value?$('ino').value:$('nome').value)+' ('+$('nome').value+');\\nALTER TABLE $table':'') : (un!=0?un+';\\nALTER TABLE $table':'')
		mu+=$('indi').checked? (ie==0?' ADD INDEX '+($('ino').value?$('ino').value:$('nome').value)+' ('+$('nome').value+');\\nALTER TABLE $table':'') : (ie!=0?ie+';\\nALTER TABLE $table':'')
		mu+=$('full').checked? (fu==0?' ADD FULLTEXT '+($('ino').value?$('ino').value:$('nome').value)+' ('+$('nome').value+');\\nALTER TABLE $table':'') : (fu!=0?fu+';\\nALTER TABLE $table':'')
		mu+=' CHANGE '+vr[0]+' '+comu
	}
	$('nome').value? SQL('ALTER TABLE $table'+mu+';',1):null
}":'').(stristr($q,'show tables')?"//Show tables
$cex
function nova(db,nt){
	if (nt) SQL('CREATE TABLE '+db+'.'+nt+' (id INT (11) AUTO_INCREMENT, PRIMARY KEY(id), UNIQUE(id), INDEX(id)) ENGINE=MyISAM DEFAULT CHARSET=latin1;',1)
}
function renomeia(db,nt,nn){
	if (nt&&nn) SQL('ALTER TABLE $db.'+nn+' RENAME $db.'+nt+';',1)
}
function exportar(ax,tabe,ta,event){
	if (ax==1){
		$('cf').innerHTML=tabe
		ver('barra2',event)
		ta2.style.visibility='visible'
		ta1.style.visibility='visible'
		ta1.style.position='relative'
		ta2.style.position='relative'
		exp.innerHTML='<img src=i/save.gif onclick=exportar(0,'+ta+',\"$db\",event)>'
	} else {
		camp=''
		if (document.getElementsByName('campos').length){
			for(j=0;j<document.getElementsByName('campos').length;j++){
				camp+=document.getElementsByName('campos')[j].checked?document.getElementsByName('campos')[j].value+',':''
			}
		} else {
			camp=document.getElementsByName('campos').checked?document.getElementsByName('campos').value+',':''
		}
	exp.innerHTML='x'
		if ((document.getElementsByName('estr')[0].checked||document.getElementsByName('dado')[0].checked)&&camp){
		abre=('?ex='+(document.getElementsByName('estr')[0].checked?1:0)+'|'+(document.getElementsByName('dado')[0].checked?1:0)+'|'+(document.getElementsByName('saida')[0].checked?0:1)+'|'+camp+'|'+(document.getElementsByName('use')[0].checked&&document.getElementsByName('estr')[0].checked?1:0)+'|'+(document.getElementsByName('drop')[0].checked&&document.getElementsByName('estr')[0].checked?1:0)+'|'+(document.getElementsByName('trat')[0].checked?0:1))
		if (document.getElementsByName('trat')[0].checked||document.getElementsByName('trat')[1].checked){
			ver_n('barra2')
			ver_n('ta1')
			ver_n('ta2')
		}
		window.location=abre
		}
	}
}":'')."
</script>
<table border=0 cellpadding=1 cellspacing=0 width=100% bordercolor=red>
	<tr>
		<td class=cor3 colspan=2><table border=0 cellpadding=1 cellspacing=0 width=80% bordercolor=blue>
		<tr>
			<td><img src=i/bd.gif align=absmiddle onclick=\"SQL('SHOW DATABASES;',0,'0')\" title='Show databases'>".(stristr($q,'select')||stristr($q,'show fields')?"<img src=i/tab.gif align=absmiddle border=0 title='Show tables' onclick=\"SQL('SHOW TABLES FROM $db;',0)\" style=cursor:hand>":'')." <b>MySQL Manager</b></td>
			<form method=post enctype=multipart/form-data>
			<td align=right><img src=i/lista.gif align=absmiddle title='Send SQL files'><span class=button><input type=file name=file class=button style=height:17;border:0;font-weight:bold ></span><input type=submit value=ok class=button></td>
			</form>
		</tr>
	</table></td>
	</tr><tr valign=top><form method=post onsubmit=document.getElementById('q').value=clui(document.getElementById('q').value) action=?db=$db>
		<td class=cor3 width=1% align=center><input type=image src=i/alt.gif title='Run query' align=absmiddle border=0><br><img src=i/back.gif title='Run the previous query' align=absmiddle border=0 onclick=\"SQL(unescape('".str_replace("\r",'',str_replace("\n",'\n',rawurlencode($tq)))."'),0)\"></td>
		<td><textarea name=q id=q style=width:100% rows=5>$q</textarea></td>
	</tr></form>
</table>
<script>
function SQL(comando,mix,db){
	Q=document.forms[1]
	if(db)Q.action='?db='+db
	mix==0?Q.q.value=comando:Q.q.value=comando+'\\n'+Q.q.value;
	document.getElementById('q').value=clui(document.getElementById('q').value)
	Q.submit();	
}
</script>
<div id=imprimir>$m</div>
".(stristr($q,'show fields')?"<!--Show Fields - Novo campo-->
<table border=0 cellpadding=0 cellspacing=0 id=barra1 class='camada cor3'>
	<tr name=barra id=barra>
		<td><b>&nbsp; Field Properties</b></td>
		<td align=right><img src=i/clo.gif name=fecha></td>
	</tr><tr>
		<td colspan=2 align=right><input type=hidden id=vv value=0><span id=vaf><img src=i/save.gif onclick=ncam()></span></td>
	</tr><tr>
		<td colspan=2><table border=0 cellpadding=0 cellspacing=0>
		<td align=right><b>Name: </b></td>
		<td><input type=text id=nome size=30 maxlength=30 class=text></td>
	</tr><tr>
		<td align=right><b>Type: </b> </td>		
		<td><select id=tipo style=width:165px onchange=disar(this.selectedIndex)><option value=TINYINT>TINYINT<option value=SMALLINT>SMALLINT<option value=MEDIUMINT>MEDIUMINT<option value=INT>INT<option value=BIGINT>BIGINT<option value=FLOAT>FLOAT<option value=DOUBLE>DOUBLE<option value=DECIMAL>DECIMAL<option value=DATE>DATE<option value=DATETIME>DATETIME<option value=TIMESTAMP>TIMESTAMP<option value=TIME>TIME<option value=YEAR>YEAR<option value=CHAR>CHAR<option value=VARCHAR>VARCHAR<option value=TINYTEXT>TINYTEXT<option value=TEXT>TEXT<option value=MEDIUMTEXT>MEDIUMTEXT<option value=LONGTEXT>LONGTEXT<option value=ENUM>ENUM<option value=SET>SET</select></td>
	</tr><tr>
		<td align=center align=center colspan=2><b>Size or specifications<br><input type=text id=tamanho size=30 maxlength=30 class=text><br>Default <br><input type=text id=padrao size=30 maxlength=30 class=text><br></b>
		<table border=0 cellspacing=0 align=center>
			<tr>
				<td align=center><b>Attributes</b></td>
				<td align=center><b>Index</b></td>
			</tr><tr valign=top>
				<td><input type=checkbox id=auto class=radio disabled>Auto numbering<br>
				<input type=checkbox id=nulo class=radio>Not null<br>
				<input type=checkbox id=binario class=radio disabled>Binary<br>
				<input type=checkbox id=sinal class=radio>With sign<br>
				<input type=checkbox id=zeros class=radio>Show zeros</td>
				<td> &nbsp;<input type=text class=text size=10 id=ino><br>
				<input type=checkbox id=keys class=radio>Primary<br>
				<input type=checkbox id=indi class=radio>Index<br>
				<input type=checkbox id=undu class=radio>Unique<br>
				<input type=checkbox id=full class=radio>Text</td>
			</tr>
		</table></td>
	</tr></table></td>
	</tr>
</table>
":'').(stristr($q,'show tables')?"<!--Show Tables - exporta-->
<table border=0 cellpadding=0 cellspacing=0 id=tanova class='camada cor3'>
	<tr>
		<td width=10 name=barra id=barra></td>
		<td><b>&nbsp;New: </b><input size=15 type=text id=nomy class=text><img src=i/save.gif onclick=nova('$db',$('nomy').value) align=absmiddle></td>
		<td align=right><img src=i/clo.gif name=fecha id=fecha>&nbsp;</td>
	</tr>
</table>
<table border=0 cellpadding=0 cellspacing=0 id=taatu class='camada cor3'>
	<tr>
		<td width=10 name=barra id=barra></td>
		<td><b>&nbsp;Rename: </b><input type=hidden id=noty><input size=15 type=text id=nomh class=text  onclick=this.select()><img src=i/save.gif onclick=\"renomeia('$db',$('nomh').value,$('noty').value)\" align=absmiddle></td>
		<td align=right><img src=i/clo.gif onclick=\"ver_n('taatu')\" align=absmiddle>&nbsp;</td>
	</tr>
</table>
<table border=0 cellpadding=0 cellspacing=0 id=barra2 class='camada cor3'>
	<tr name=barra id=barra>
		<td><b>&nbsp; Export Tables</b>
		<td align=right><img src=i/clo.gif onclick=\"ver_n('barra2');ver_n('ta1');ver_n('ta2')\"></td>
	</tr><tr>
		<td colspan=2 align=right><span id=exp></span></td>
	</tr><tr valign=top>
		<td colspan=2><table border=0 cellspacing=0 cellpadding=0 width=100%>
			<tr>
				<td align=right><b>Output :</b></td>
				<td><input type=radio class=radio name=saida checked>Screen <input type=radio class=radio name=saida>File<br></td>
			</tr><tr>
				<td align=right valign=top width=80><b>Export :</b></td>
				<td><input type=checkbox class=radio name=estr checked onclick=\"if(this.checked==true){ta1.style.visibility='visible';ta1.style.position='relative';document.getElementsByName('trat')[0].checked=true;document.getElementsByName('trat')[1].disabled=true}else{ta1.style.visibility='hidden';ta1.style.position='absolute';document.getElementsByName('trat')[1].disabled=false}\">Structure <input type=checkbox class=radio name=dado onclick=\"if(this.checked==true){ta2.style.visibility='visible';ta2.style.position='relative'}else{ta2.style.visibility='hidden';ta2.style.position='absolute'}\">Data</td>
			</tr><tr id=ta1>
				<td align=right valign=top width=80><b>Add on :</b></td>
				<td><input type=checkbox class=radio name=use>Use database<br><input type=checkbox class=radio name=drop>Drop table if exists</td>
			</tr><tr id=ta2>
				<td align=right valign=top width=80><b>Records :</b></td>
				<td><input type=radio class=radio name=trat value=insere checked>Insert <input type=radio class=radio name=trat value=troca disabled>Update</td>
			</tr><tr valign=top>
				<td align=right><img src=i/ne.gif align=absmiddle style=cursor:hand onclick=\"for(j=0;j<document.getElementsByName('campos').length;j++)document.getElementsByName('campos')[j].checked=false\" title='Uncheck all'><img src=i/to.gif align=absmiddle style=cursor:hand onclick=\"for(j=0;j<document.getElementsByName('campos').length;j++)document.getElementsByName('campos')[j].checked=true\" title='Check all'><b>Tables :</b></td>
				<td id=cf></td>
			</tr>
			</table></td>
	</tr>
</table>
":'').(isset($por)?"<br>
<table border=0 width=100%>
	<tr>
		<td class=cor3><b>Exported data</b></td>
	</tr><tr>
		<td><br>$por</td>
	</tr>
</table>":'')."
$final_pagina";
} else {
	header ("Location: index.php"); 
}
?>