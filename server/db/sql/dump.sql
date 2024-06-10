USE websystem;
DROP TABLE IF EXISTS acesso;
CREATE TABLE `acesso` (
  `arquivo` int(11) unsigned NOT NULL DEFAULT '0',
  `usuario` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
DROP TABLE IF EXISTS arquivos;
CREATE TABLE `arquivos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pai` int(11) NOT NULL DEFAULT '0',
  `data` date NOT NULL DEFAULT '1001-01-01',
  `nome` varchar(100) NOT NULL DEFAULT '',
  `fonte` longtext NOT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT '0',
  `menu` tinyint(1) NOT NULL DEFAULT '0',
  `tipo` tinyint(1) NOT NULL DEFAULT '1',
  `privilegio` tinyint(1) NOT NULL DEFAULT '0',
  `chave` varchar(250) DEFAULT NULL,
  `nivel` varchar(255) NOT NULL DEFAULT '1,',
  `pasta` tinyint(1) NOT NULL DEFAULT '0',
  `enquete` int(11) NOT NULL DEFAULT '0',
  `view` int(11) NOT NULL DEFAULT '0',
  `ordem` int(10) unsigned NOT NULL DEFAULT '0',
  `idioma` int(10) unsigned NOT NULL DEFAULT '1',
  `extra` longtext,
  `ajax` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
INSERT INTO arquivos VALUES('1','0','1001-01-01','Home','<font class=\"Apple-style-span\" size=\"3\"><br></font>','1','3','1','0','Home site','1,','0','0','0','100','1',NULL,NULL);
INSERT INTO arquivos VALUES('2','0','2010-09-17','Page templates','1','0','0','0','0','Page templates','1,','0','0','0','0','1',NULL,NULL);
INSERT INTO arquivos VALUES('3','2','2010-09-17','News','<table class=\"cor3\" cellspacing=\"2\" cellpadding=\"1\" align=\"right\">
<tbody valign=\"top\">
<tr>
<td><div style=\"TEXT-ALIGN: center; WIDTH: 250px; BACKGROUND: mintcream; HEIGHT: 200px\">250X200</div></td></tr>
<tr>
<td style=\"FONT: italic 10px verdana\">Legend</td></tr></tbody></table>Subtitle<br><br><strong><font color=\"#006600\" size=\"2\">Title</font></strong><br><br>News<table border=\"1\" cellspacing=\"1\" bordercolor=\"#f1f2f3\" cellpadding=\"1\" bgcolor=\"#f1f2f3\" align=\"center\">
<tbody valign=\"top\">
<tr>
<td><strong style=\"COLOR: darkgreen\">Title of box<br><br></strong>content of box</td></tr></tbody></table><br><br><i style=\"FONT-SIZE: 10px\">Author: <br>Font: 
<p style=\"TEXT-ALIGN: right\">date</p></i>
<p><strong><font color=\"darkgreen\" size=\"2\">S</font><font color=\"darkgreen\" size=\"1\">e to<br></font></strong><br>&gt;&gt; <strong>Link to other news</strong></p>','0','0','1','0','Page template','1,','0','0','0','0','1',NULL,NULL);
INSERT INTO arquivos VALUES('12','0','2011-01-04','Sitemap','8','1','0','0','0','Sitemap','1,','0','0','0','0','1',NULL,'1');
INSERT INTO arquivos VALUES('13','0','2011-01-04','News','5','1','3','0','0','News page on the site','1,','0','0','0','90','1',NULL,NULL);
INSERT INTO arquivos VALUES('16','0','2011-01-04','Gallery','2','1','3','0','0','Photo gallery','1,','0','0','0','80','1','This text displays what is written inside the extra field in page. Use this feature to complement the information on the gallery.
',NULL);
INSERT INTO arquivos VALUES('15','0','2011-01-04','RSS','9','1','0','0','0','RRS','1,','0','0','0','0','1',NULL,'1');
INSERT INTO arquivos VALUES('17','0','2011-01-04','Login','3','1','3','0','0','Login','1,','0','0','0','50','1',NULL,NULL);
INSERT INTO arquivos VALUES('18','0','2011-01-04','Sign In','4','1','3','0','0','Sign In','1,','0','0','0','60','1',NULL,NULL);
INSERT INTO arquivos VALUES('19','0','2011-01-04','Scrapbook','6','1','3','0','0','Scrapbook','1,','0','0','0','70','1',NULL,NULL);
INSERT INTO arquivos VALUES('45','0','2011-10-27','Contact','<div style=\"text-align: center;\">Fill out the fields and click \'Submit\' to send your message and contact.</div><div style=\"text-align: center;\"><br></div><table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\"><input type=\"hidden\" class=\"hidden\" name=\"email\" value=\"spider_poison@hotmail.com\"><tbody><tr><td align=\"right\">Name: </td><td><input type=\"text\" name=\"name\" size=\"44\" class=\"text\"></td></tr><tr><td align=\"right\">Address: </td><td><input type=\"text\" name=\"address\" size=\"44\" class=\"text\"></td></tr><tr><td align=\"right\">Cit: </td><td><input type=\"text\" name=\"cit\" size=\"26\" class=\"text\"> State: <input type=\"text\" name=\"state\" size=\"5\" class=\"text\"></td></tr><tr><td align=\"right\">Contact form: </td><td><input type=\"radio\" name=\"contact_form\" value=\"Phone\" checked=\"\" class=\"radio\">Phone <input type=\"radio\" name=\"contact_form\" value=\"E-mail\" class=\"radio\">E-mail <input type=\"radio\" name=\"contact_form\" value=\"Mobile\" class=\"radio\">Mobile</td></tr><tr><td align=\"right\">Phone: </td><td><input type=\"text\" name=\"phone\" size=\"15\" class=\"text\"> Mobile: <input type=\"text\" name=\"mobile\" size=\"15\" class=\"text\"></td></tr><tr><td align=\"right\">E-mail: </td><td><input type=\"text\" name=\"e_mail\" size=\"44\" class=\"text\"></td></tr><tr><td align=\"right\" valign=\"top\">Comment: </td><td><textarea name=\"comment\" cols=\"43\" rows=\"5\"></textarea></td></tr><tr><td align=\"right\">Annex: </td><td><input type=\"file\" name=\"anexo\" size=\"29\" class=\"text\"></td></tr><tr><td colspan=\"2\" align=\"right\"><input type=\"submit\" value=\"Submit\" class=\"button\">&nbsp;<input type=\"reset\" value=\"Clear\" class=\"button\">&nbsp;</td></tr></tbody></table>','1','3','1','0','Contact page','1,','0','0','0','40','1',NULL,NULL);
DROP TABLE IF EXISTS banner;
CREATE TABLE `banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(250) DEFAULT NULL,
  `imagem` varchar(250) DEFAULT NULL,
  `visualizar` varchar(10) DEFAULT '0',
  `target` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
INSERT INTO banner VALUES('23','topo','index.php?id=1','b2011102794616.gif','2500-01-01','0');
DROP TABLE IF EXISTS dados;
CREATE TABLE `dados` (
  `titulo` varchar(50) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `palavra_chave` varchar(255) DEFAULT NULL,
  `script` tinyint(1) NOT NULL DEFAULT '0',
  `charset` varchar(255) DEFAULT NULL,
  `editor_cor` varchar(20) NOT NULL DEFAULT '',
  `nome_cor` varchar(20) NOT NULL DEFAULT '',
  `editor_barra` varchar(20) NOT NULL DEFAULT '',
  `editor_x` int(11) unsigned DEFAULT '0',
  `editor_y` int(11) unsigned DEFAULT '0',
  `editor_w` int(11) unsigned DEFAULT '0',
  `editor_h` int(11) unsigned DEFAULT '0',
  `nome_x` int(11) unsigned DEFAULT '0',
  `nome_y` int(11) unsigned DEFAULT '0',
  `css` varchar(255) DEFAULT NULL,
  `visitas` int(11) unsigned DEFAULT '0',
  `view` int(11) NOT NULL DEFAULT '0',
  `fonte_cor` varchar(15) DEFAULT NULL,
  `erro` int(1) unsigned NOT NULL DEFAULT '0',
  `zerar` int(1) unsigned NOT NULL DEFAULT '0',
  `mail_de` varchar(50) NOT NULL,
  `mail_us` varchar(50) NOT NULL,
  `mail_se` varchar(50) DEFAULT NULL,
  `mail_sm` varchar(50) DEFAULT NULL,
  `mail_po` int(5) unsigned DEFAULT '25',
  `mail_en` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mail_lo` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `login` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `copia` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO dados VALUES('WebSystem','Administrative system to homepage','gate,system,CMS,administrator,MySQL,PHP','0','utf-8','FFFFFF','FFFFFF','063F3F','0','140','1000','550','10','120','#38B9C7;#5476A4;#24A02D;#762F95;#D22D2D;#D2C62D;#EF7F16;#DF2572','0','0','000000','1','1','email@dominio.com','usuario','senha','smtp.dominio.com','25','0','1','1','1');
DROP TABLE IF EXISTS email;
CREATE TABLE `email` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server` varchar(255) DEFAULT NULL,
  `data` datetime NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `reverso` varchar(20) DEFAULT NULL,
  `log` mediumtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS enquete;
CREATE TABLE `enquete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pai` int(11) NOT NULL DEFAULT '0',
  `pergunta` varchar(150) NOT NULL DEFAULT '',
  `resposta` text NOT NULL,
  `cor1` varchar(7) NOT NULL DEFAULT '#dcdcdc',
  `cor2` varchar(7) DEFAULT '#f5f5f5',
  `valor` varchar(255) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `per1` varchar(10) NOT NULL DEFAULT '',
  `per2` varchar(10) NOT NULL DEFAULT '',
  `dirc` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS estatistica;
CREATE TABLE `estatistica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dia` int(11) NOT NULL DEFAULT '0',
  `dia_temp` int(11) NOT NULL DEFAULT '0',
  `dia_data` date NOT NULL DEFAULT '1001-01-01',
  `semana` int(11) NOT NULL DEFAULT '0',
  `semana_temp` int(11) NOT NULL DEFAULT '0',
  `semana_data` date DEFAULT '1001-01-01',
  `mes` int(11) NOT NULL DEFAULT '0',
  `mes_temp` int(11) NOT NULL DEFAULT '0',
  `mes_data` date DEFAULT '1001-01-01',
  `ano` int(11) NOT NULL DEFAULT '0',
  `ano_temp` int(11) NOT NULL DEFAULT '0',
  `ano_data` date DEFAULT '1001-01-01',
  `segunda` int(11) NOT NULL DEFAULT '0',
  `segunda_madrugada` int(11) NOT NULL DEFAULT '0',
  `segunda_manha` int(11) NOT NULL DEFAULT '0',
  `segunda_tarde` int(11) NOT NULL DEFAULT '0',
  `segunda_noite` int(11) NOT NULL DEFAULT '0',
  `terca` int(11) NOT NULL DEFAULT '0',
  `terca_madrugada` int(11) NOT NULL DEFAULT '0',
  `terca_manha` int(11) NOT NULL DEFAULT '0',
  `terca_tarde` int(11) NOT NULL DEFAULT '0',
  `terca_noite` int(11) NOT NULL DEFAULT '0',
  `quarta` int(11) NOT NULL DEFAULT '0',
  `quarta_madrugada` int(11) NOT NULL DEFAULT '0',
  `quarta_manha` int(11) NOT NULL DEFAULT '0',
  `quarta_tarde` int(11) NOT NULL DEFAULT '0',
  `quarta_noite` int(11) NOT NULL DEFAULT '0',
  `quinta` int(11) NOT NULL DEFAULT '0',
  `quinta_madrugada` int(11) NOT NULL DEFAULT '0',
  `quinta_manha` int(11) NOT NULL DEFAULT '0',
  `quinta_tarde` int(11) NOT NULL DEFAULT '0',
  `quinta_noite` int(11) NOT NULL DEFAULT '0',
  `sexta` int(11) NOT NULL DEFAULT '0',
  `sexta_madrugada` int(11) NOT NULL DEFAULT '0',
  `sexta_manha` int(11) NOT NULL DEFAULT '0',
  `sexta_tarde` int(11) NOT NULL DEFAULT '0',
  `sexta_noite` int(11) NOT NULL DEFAULT '0',
  `sabado` int(11) NOT NULL DEFAULT '0',
  `sabado_madrugada` int(11) NOT NULL DEFAULT '0',
  `sabado_manha` int(11) NOT NULL DEFAULT '0',
  `sabado_tarde` int(11) NOT NULL DEFAULT '0',
  `sabado_noite` int(11) NOT NULL DEFAULT '0',
  `domingo` int(11) NOT NULL DEFAULT '0',
  `domingo_madrugada` int(11) NOT NULL DEFAULT '0',
  `domingo_manha` int(11) NOT NULL DEFAULT '0',
  `domingo_tarde` int(11) NOT NULL DEFAULT '0',
  `domingo_noite` int(11) NOT NULL DEFAULT '0',
  `temp` int(11) NOT NULL DEFAULT '0',
  `periodo` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO estatistica VALUES('1','0','0','2014-07-16','0','0','2014-07-16','0','0','2014-07-16','0','0','2014-07-16','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
DROP TABLE IF EXISTS flash;
CREATE TABLE `flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pai` int(11) NOT NULL DEFAULT '0',
  `data` date NOT NULL DEFAULT '1001-01-01',
  `nome` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS idioma;
CREATE TABLE `idioma` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idioma` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
INSERT INTO idioma VALUES('1','default');
DROP TABLE IF EXISTS imagens;
CREATE TABLE `imagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pai` int(11) NOT NULL DEFAULT '0',
  `data` date NOT NULL DEFAULT '1001-01-01',
  `nome` varchar(40) NOT NULL DEFAULT '',
  `descricao` varchar(255) DEFAULT NULL,
  `recado_e` longtext,
  `recado_a` longtext,
  `excelente` int(11) DEFAULT '0',
  `otima` int(11) DEFAULT '0',
  `boa` int(11) DEFAULT '0',
  `ruim` int(11) DEFAULT '0',
  `pessima` int(11) DEFAULT '0',
  `cliques` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS log;
CREATE TABLE `log` (
  `data` datetime NOT NULL DEFAULT '1001-01-01 00:00:00',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `usuario` varchar(50) NOT NULL DEFAULT 'Usuário',
  `senha` varchar(50) NOT NULL DEFAULT 'Senha',
  `reverso` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`data`),
  KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS menu;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL DEFAULT '',
  `fonte` text,
  `habilitado` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
INSERT INTO menu VALUES('1','menu_horizontal','$menu=\"\";
foreach($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s)	$menu.=\" -  <a href=index.php?id=$s[0]&idioma=$idioma class=menu_horizontal><b>$s[1]</a></b>\";
return $menu.(!empty($menu)?\' - \':\'\');','1');
INSERT INTO menu VALUES('2','menu_vertical','$menu=\"\";
foreach ($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and pai=0 and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s){
	$u=\'\';	
	foreach ($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and pai=$s[0] and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s1) $u.=\"<tr><td><a href=index.php?id=$s1[0]&idioma=$idioma class=submenu_vertical>&nbsp;&nbsp;-&nbsp;$s1[1]</a></td></tr>\";
	$menu.=\"<tr><td><a href=index.php?id=$s[0]&idioma=$idioma class=menu_vertical>&nbsp;•&nbsp;$s[1]</a></td></tr>$u\";
}
return \"<table border=0 cellpadding=0 cellspacing=0 width=100%>$menu</table>\";
','1');
INSERT INTO menu VALUES('3','menu_box','$im=0;
$mr=\"\";
$wi=100;
$menu=\"\";
foreach ($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and pai=0 and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s){
	$u=\'\';	
	foreach ($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and pai=$s[0] and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM)as $s1) $u.=\"<a href=index.php?id=$s1[0]&idioma=$idioma class=submenu_box>  $s1[1]</a><br>\";
	if (!empty($u)){
		$u=\"<div id=menu$im style=\'position:absolute;top:-1000;left:-1000;visibility:hidden;width:$wi\' class=caixa>$u</div>\";
		$z=\"# onclick=\'menum(menu$im,event)\'\";
		$im++;
	} else {
		$z=\"index.php?id=$s[0]&idioma=$idioma\";
	}
	$menu.=\"<td width=$wi><a href=$z class=menu_box>$s[1]</a></td><td style=color:white>|</td>\";
	$mr.=$u;
}
return \"
<script>
function menum(Menu,e){
	if (window.menu&&menu.id!=Menu.id) menue(menu)
   		menu=Menu
		menu.style.visibility==\'hidden\'?menu.style.visibility=\'visible\':menue()
		menu.style.left=document.body.scrollLeft+e.clientX-(msie||opera||chrome?e.offsetX:e.layerX)
		menu.style.top=document.body.scrollTop+e.clientY-(msie||opera||chrome?e.offsetY-20:e.layerY-22)
		msie||opera?event.cancelBubble=true:e.stopPropagation()
}
function menue() {
	if (window.menu) menu.style.visibility=\'hidden\'
}
document.onclick=menue
</script>
<table border=0 bgcolor=silver cellpadding=0 cellspacing=0 height=22><tr align=center>\".substr($menu,0,-28).\"</tr></table>$mr\";','1');
INSERT INTO menu VALUES('4','menu_restrito','$menu=\"\";
foreach($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and privilegio=1 and pai=0 and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s){
	$u=\'\';	
	foreach ($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and privilegio=1 and pai=$s[0] and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s1) $u.=\"<tr><td><a href=index.php?id=$s1[0]&idioma=$idioma class=submenu_vertical>  - $s1[1]</a></td></tr>\";
	$menu.=\"<tr><td><a href=index.php?id=$s[0]&idioma=$idioma class=menu_vertical> • $s[1]</a></td></tr>$u\";
}
return \"<table border=0 cellpadding=0 cellspacing=0 width=100%>$menu</table>\";','1');
INSERT INTO menu VALUES('5','menu_retratil','if(isset($_GET[\'crokie\'])){
	$x=explode(\'|\',$_GET[\'crokie\']);	
	setcookie(\"menu_v$x[0]\",$_GET[\'loc\'],time()+3600*3600);
	die();
}

$menu=\"\";
foreach ($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and pai=0 and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s){
	$u=\'\';	
	foreach ($mysql->query(\"SELECT id,nome FROM arquivos where menu=$id and habilitado=1 and pai=$s[0] and idioma=$idioma order by ordem desc,nome\",PDO::FETCH_NUM) as $s1) $u.=\"<a href=index.php?id=$s1[0]&idioma=$idioma class=submenu_retratil>- $s1[1]</a><br>\";
	if (isset($_COOKIE[\"menu_v$s[0]\"])){
	     $coki=$_COOKIE[\"menu_v$s[0]\"];
	} else {
	     setcookie(\"menu_v$s[0]\",\'0\',time()+3600*3600);
	     $coki=\'0\';
	}
	if ($coki==\'0\'){
	      $lm=\'display:none\';
	} else {
	      $lm=\'display:block\';
	}
	$menu.=\"<a \".(empty($u)?\"href=index.php?id=$s[0]&idioma=$idioma\":\"onclick=\'vnv($s[0])\'\").\" class=menu_retratil style=cursor:hand>• $s[1]</a><br>
	\".(empty($u)?\'\':\"<span id=\'u$s[0]\' style=$lm>$u</span>\");
}
return \"
<script>
function vnv(cama){
     if (document.all[\'u\'+cama].style.display==\'none\') {
        dis=\'block\'
        loc=\'1\'
     } else {
        dis=\'none\'
        loc=\'0\'
     }
     document.all[\'u\'+cama].style.display=dis
     carrega(\'?id=323&crokie=\'+cama+\'&loc=\'+loc,\'akaj\')
}
</script>
<table border=0 cellpadding=0 cellspacing=0 width=100%>$menu</table><span id=akaj></span>
\";','1');
DROP TABLE IF EXISTS nivel;
CREATE TABLE `nivel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL DEFAULT '0',
  `restrito` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pagina_c` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pagina_r` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pagina_a` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `estrutura` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `menu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `habilitado` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `privilegio` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `redirecionamento` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `chave` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `conteudo` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sistema_e` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sistema_a` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usuarios_e` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usuarios_n` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `idioma` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `master` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ftp` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `estatistica` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enquete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `banner` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sistemas` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `menu_e` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `log` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `raiz` varchar(50) DEFAULT '../',
  `xql` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `nivel` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `edita` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sistema_n` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sistema_d` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ajax` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pagina_p` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pagina_e` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `xmaix` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
INSERT INTO nivel VALUES('1','Web Master','0','1','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','1','1','1','1','1','1','1','1','../','1','1','0','1','1','1','1','1','1');
INSERT INTO nivel VALUES('23','Cliente','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','../_gravar','0','0','1','0','0','0','0','0','0');
DROP TABLE IF EXISTS sistemas;
CREATE TABLE `sistemas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `nivel` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '1,',
  `fonte` longtext COLLATE utf8_bin,
  `anexo` longtext COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO sistemas VALUES('1','Folder','0,','$m=\'\';
$e=20;
$i=isset($_GET[\'i\'])?$_GET[\'i\']:0;
foreach($mysql->query(\"select id,nome from arquivos where pai=$id and habilitado=1 order by ordem,nome limit $i,$e\",PDO::FETCH_NUM) as $c) $m.=\"<a href=?id=$c[0]> - $c[1]</a><br>\";
extract($mysql->query(\"select count(id) d from arquivos where pai=$id and habilitado=1\")->fetch(PDO::FETCH_ASSOC));
return \"$m<div align=center class=controle>\".($d>$e ? ($i==0 ? \'x\':\"<a href=?i=\".($i-$e).\"&amp;id=$id class=controle><b>Back</b></a>\").\"|\".(($i+$e)>=$d ? \'x\':\"<a href=?i=\".($i+$e).\"&amp;id=$id class=controle><b>Next</b></a>\"):\'<BR>\').\"</div>\";',NULL);
INSERT INTO sistemas VALUES('2','Gallery','0,','$acao=isset($_GET[\'acao\']) ? $_GET[\'acao\']:0;
if ($acao==1){
	$g=isset($_GET[\'g\']) ? $_GET[\'g\']-1:0;
	@$f=$mysql->query(\"select nome,descricao,pai,id from imagens where id=$g\")->fetch(PDO::FETCH_NUM);
	@extract($mysql->query(\"select id antes from imagens where pai=$f[2] and id>$f[3]+1 and nome like \'t%\' limit 1\")->fetch(PDO::FETCH_ASSOC));
	@extract($mysql->query(\"select id proxima from imagens where pai=$f[2] and id<$f[3] and nome like \'t%\' order by id desc limit 1\")->fetch(PDO::FETCH_ASSOC));
	$anterior=empty($antes)?\'\':\"<a href=?id=$id&acao=1&g=$antes class=controle><IMG src=admin/i/back.gif border=0 align=absmiddle alt=\'Back\'></a>\";
	$next=empty($proxima)?\'\':\"<a href=?id=$id&acao=1&g=$proxima class=controle><IMG src=admin/i/go.gif border=0 align=absmiddle alt=\'Next\'></a>\";
	$m=\"<div align=center><img src=_gravar/$f[0] width=500><br>$f[1]<br><br>
	$anterior <a href=?id=$id&acao=0 class=controle><IMG src=admin/i/up.gif border=0 align=absmiddle alt=\'Back to gallery\'></a> $next</div>\";
} else {
	extract($mysql->query(\"select chave,extra from arquivos where id=$id\")->fetch(PDO::FETCH_ASSOC));
	$o=\'\';
	$w=\'\';
	$p=1;
	$s=3;//colunas
	$e=9;//itens exibidos
	$m=\"<tr><td colspan=$s align=center>$chave<br><br>$extra<br><br></td></tr>\";
	$i=isset($_GET[\'i\']) ? $_GET[\'i\']:0;
	foreach($mysql->query(\"select id,nome,descricao from imagens where pai=$id and nome like \'t%\' order by id desc limit $i,$e\",PDO::FETCH_NUM) as $c){
		$p==1 ? $o=\'<tr align=center><td width=150>\':$o=\'<td width=150>\';
		$p==$s ? $w=\'</td></tr>\':$w=\'</td>\';
		$m.=\"$o<a href=?id=$id&acao=1&g=$c[0]><img src=_gravar/$c[1] border=0 width=150></a><br>$c[2]$w\";
		$p>=3 ? $p=1:$p+=1;
	}
	while ($p>1&&$p<($s+1)){
		$o=$p==1?\'<tr align=center><td width=150>\':\'<td width=150>\';
		$w=$p==$s?\'</td></tr>\':\'</td>\';
		$m.=$o.\" \".$w;
		$p+=1;
	}
	@extract($mysql->query(\"select count(id) t from imagens where pai=$id and nome like \'t%\'\")->fetch(PDO::FETCH_ASSOC));	
	$m=\"<table border=0  cellpadding=1 cellspacing=0 bordercolor=green align=center>$m<tr><td align=center class=controle colspan=3>\".($t>$e ? ($i==0 ? \'x\':\"<a href=?i=\".($i-$e).\"&id=$id class=controle><b>Back</b></a>\").\"|\".(($i+$e)>=$t ? \'x\':\"<a href=?i=\".($i+$e).\"&id=$id class=controle><b>Next</b></a>\"):\'<BR>\').\"</td></tr></table>\";
}
return \"<table border=0 bordercolor=red cellpadding=1 cellspacing=0 align=center width=96%><tr><td>$m</td></tr></table><br>\";','include(\'dados.php\');
error_reporting(E_ALL);
$apaga=isset($_GET[\'apaga\'])?$_GET[\'apaga\']:0;
$data=date(\"Y-m-d\");
$fez=$z==0?\'Send images\':\'\';

if (isset($_GET[\'z\'])==\"upload\"){
	for ($a=0;$a<9;$a++){
		if (!empty($_FILES[\"f$a\"][\"tmp_name\"])){
			$id=$_POST[\'id\'];
			$arquivo=$_FILES[\"f$a\"][\"tmp_name\"];
			$nome=$id.date(\"YmdGis\").\".jpg\";
			$tnome=\"t$nome\";
			move_uploaded_file ($arquivo,$arquivo);
			thumbnail($arquivo,150,0,\"../_gravar/$tnome\",100);
			thumbnail($arquivo,500,0,\"../_gravar/$nome\",100);
			$mysql->exec(\"insert into imagens(pai,data,nome,descricao) values($id,\'$data\',\'$nome\',\'\".$_POST[\"d$a\"].\"\');insert into imagens(pai,data,nome,descricao) values($id,\'$data\',\'$tnome\',\'\".$_POST[\"d$a\"].\"\')\");
			$fez=\"Uploaded files\";
			sleep(1);
		}
	}
}
if (isset($_GET[\'apaga\'])){	
	foreach($mysql->query(\"select nome from imagens where id=$apaga or id=$apaga-1\",PDO::FETCH_NUM) as $sql) unlink(\"../_gravar/$sql[0]\");
	$mysql->exec(\"delete from imagens where id=$apaga or id=$apaga-1\");
	header (\"location:?z=1&id=$id\");
}

isset($_GET[\'descricao\'])?$mysql->exec(\"update imagens set descricao=\'$_GET[descricao]\' where id=$_GET[reg] or id=\".($_GET[\'reg\']-1)):null;

$o=\'\';
$k=\'\';
$p=1;
$s=3;//colunas
$e=9;//itens exibidos
$m=\"
<script>
//posta
function pn(id,valor,nome){
	window.location=\'?reg=\'+id+\'&id=$id&\'+nome+\'=\'+escape(valor)
}
//Gera caixa
caixa=true
function d(id,nome,valor){
	if (caixa){
		l=document.all[nome+id]
		bkp=l.innerHTML
		valor=unescape(valor)
		while (valor.match(\'\\\"\')){
			valor=valor.replace(\'\\\"\',\'&xquot;\')
		}
		l.innerHTML=\'<input type=text id=\'+nome+\' value=\\\"\'+valor+\'\\\" class=text size=20 maxlength=250 onclick=this.select()><input type=button onclick=pn(\'+id+\',$(\\\"\'+nome+\'\\\").value,\\\"\'+nome+\'\\\") value=ok class=button><input type=button value=\\\" X \\\" class=button onclick=retorna(\'+id+\')>\'
	} else {
		l.innerHTML=bkp
		caixa=true
		d(id,nome,valor)
	} 
	caixa=false

}
function retorna(id){
	!caixa ? l.innerHTML=bkp:null;
	caixa=true
}
</script>

<table border=0 bordercolor=red cellpadding=0 cellspacing=0 width=10 
lign=center><tr>
	<td align=center><table border=0 bordercolor=blue cellpadding=0 cellspacing=0 width=500 align=center>\";
foreach($mysql->query(\"select id,nome,descricao from imagens where pai=$id and nome like \'t%\' order by id desc limit $i,$e\",PDO::FETCH_NUM) as $c){
	$p==1 ? $o=\'<tr align=center><td>\':$o=\'<td>\';
	$p==$s ? $k=\'</td></tr>\':$k=\'</td>\';
	$m.=\"$o<a href=# onclick=\\\"avisar(\'The deleted item could not be retrieved<BR>Do you really want to continue?<BR><BR><INPUT type=button class=button value=Yes onclick=window.location=\\\'?z=2&id=$id&apaga=$c[0]\\\'> <INPUT type=button class=button value=No onclick=fechaviso()>\')\\\"><img src=../_gravar/$c[1] border=0 width=150></a>
<br></a><span id=descricao$c[0]><a href=\\\"javascript:d($c[0],\'descricao\',\'$c[2]\')\\\"><img src=i/cad.gif border=0 alt=\'Change image comment\' align=absmiddle> $c[2]</a></span></td>$k\";
	$p>=3 ? $p=1:$p+=1;
}
while ($p>1&&$p<($s+1)){
	$o=$p==1?\'<tr align=center><td width=150>\':\'<td width=150>\';
	$k=$p==$s?\'</td></tr>\':\'</td>\';
	$m.=$o.\" \".$k;
	$p+=1;
}
extract($mysql->query(\"select count(id) d from imagens where pai=$id and nome like \'t%\'\")->fetch(PDO::FETCH_ASSOC));
$m=\"<table border=0 cellpadding=0 cellspacing=0 align=center width=100%>
<tr align=center>
	<td><table border=0 align=center cellpadding=0 cellspacing=0 width=100%><form enctype=multipart/form-data action=?z=upload&id=$id method=post><input type=hidden name=id value=$id>
<tr>
	<td class=cor4><img src=i/image.gif align=absmiddle> <b>Galeria de imagens</b></td>
</tr><tr>
	<td align=center class=cor2><table border=0 align=center cellpadding=0 cellspacing=0 width=100%>
		<tr>
			<td><input type=text name=d0 maxlenght=250 class=text size=10><input name=f0 type=file class=text size=2 style=width:100></td>
			<td><input type=text name=d1 maxlenght=250 class=text size=10><input name=f1 type=file class=text size=2 style=width:100></td>
			<td><input type=text name=d2 maxlenght=250 class=text size=10><input name=f2 type=file class=text size=2 style=width:100></td>
		</tr><tr>
			<td><input type=text name=d3 maxlenght=250 class=text size=10><input name=f3 type=file class=text size=2 style=width:100></td>
			<td><input type=text name=d4 maxlenght=250 class=text size=10><input name=f4 type=file class=text size=2 style=width:100></td>
			<td><input type=text name=d5 maxlenght=250 class=text size=10><input name=f5 type=file class=text size=2 style=width:100></td>
		</tr><tr>
			<td><input type=text name=d6 maxlenght=250 class=text size=10><input name=f6 type=file class=text size=2 style=width:100></td>
			<td><input type=text name=d7 maxlenght=250 class=text size=10><input name=f7 type=file class=text size=2 style=width:100></td>
			<td><input type=text name=d8 maxlenght=250 class=text size=10><input name=f8 type=file class=text size=2 style=width:100></td>
		</tr>
	</table></td>
</tr><tr>
	<td align=center class=cor3><input type=submit value=enviar class=button></td>
</tr>
</form></table>$m<tr><td align=center class=controle colspan=3>\".($v=$d>$e?($i==0?\'x\':\"<a href=?&z=1&i=\".($i-$e).\"&id=$id class=controle><b>Voltar</b></a>\").\"|\".(($i+$e)>=$d?\'x\':\"<a href=?z=1&i=\".($i+$e).\"&id=$id class=controle><b>Avançar</b></a>\"):\'<BR>\').\"</td></tr></table></td></tr>
</table>\";
	
return \"$inicio_pagina$m$final_pagina\";');
INSERT INTO sistemas VALUES('3','Login','0,','$ip=$_SERVER[\'REMOTE_ADDR\'];
$l = isset($_COOKIE[\'l\']) ? $_COOKIE[\'l\']:0;
$usuario=isset($_POST[\'usuario\']) ? addslashes(htmlentities(strip_tags(str_replace(\'\"\',\'\',$_POST[\'usuario\'])))):\'\';
$senha=isset($_POST[\'senha\']) ? addslashes(htmlentities(strip_tags($_POST[\'senha\']))):\'\';
$reverso= isset($_SERVER[\"HTTP_X_FORWARDED_FOR\"])?$_SERVER[\"HTTP_X_FORWARDED_FOR\"]:\'Desabilitado\';
//faz logoff
if (isset($_GET[\'log\'])) {
	setcookie (\'pedido\',\'\',0);
	setcookie (\'l\',0,0);
	setcookie (\'n\',0,0);
	echo (\"<script>window.location=\'?id=$id\'</script>\");
}

//faz login
if ($sql=$mysql->query(\"SELECT id,nivel,usuario,banner FROM usuarios where usuario=\'$usuario\' and senha=\'\".md5($senha).\"\' and habilitado=1\")->fetch(PDO::FETCH_NUM)){
	$mysql->exec(\"delete from log where to_days(now()) - to_days(data) > 30\");
	$mysql->exec(\"insert into log values(now(),\'$ip\',\'$usuario\',\'logou!\',\'$reverso\')\");
	extract($mysql->query(\"select md5(data) t from log where ip=\'$ip\' and usuario=\'$usuario\' and senha=\'logou!\' order by data desc \")->fetch(PDO::FETCH_ASSOC));
	setcookie (\'pedido\',\'\',0);
	setcookie (\'l\',$sql[0],0);
	setcookie (\'n\',$sql[1],0);
	if(isset($_COOKIE[\'t\'])) unset($_COOKIE[\'t\']);
	setcookie (\'t\',$t,0);
	echo (\"<script>window.location=\'?id=$id\'</script>\");
}  else if (!empty($usuario)) {
	$mysql->exec(\"insert into log values(now(),\'$ip\',\'$usuario\',\'$senha\',\'$reverso\')\");
}

//questiona log
if ($l==0){
	$b=$mysql->query(\"select a.id from arquivos a inner join sistemas s on a.fonte=s.id where s.nome like \'%Register%\'\")->fetch(PDO::FETCH_NUM);
	if (empty($b[0])){
		$m=\'The configuration of the \"Register\" system is required to operate the system login.\';
	} else {
		$m=\"<table align=center><tr><td>Fill out the fields and click on \'Login\' to access the system in the system.<br>If you are not a registered user, <a href=?id=$b[0]>click here</a> to sign up.<br><BR><form method=post action=?id=$id><table border=0 align=center><tr><td align=right>User: </td><td><input type=text name=usuario class=text></td></tr><tr><td align=right>Password: </td><td><input type=password name=senha class=text></td></tr><tr><td colspan=2 align=right><input type=submit value=Login class=button></td></tr></table></form></td></tr></table>\";
	}
} else {
	$m=\"The user is logged in, click here to make the <a href=?id=$id&log=0>Logout</a>\";
}

return $m;',NULL);
INSERT INTO sistemas VALUES('4','Register','0,','$z=isset($_GET[\'z\'])?$_GET[\'z\']:0;
$l = isset($_COOKIE[\'l\']) ? $_COOKIE[\'l\']:0;

$br=$mysql->query(\"select a.id from arquivos a inner join sistemas s on a.fonte=s.id where s.nome like \'%Login%\'\")->fetch(PDO::FETCH_NUM);

if ($z==1){
	//novos dados
	extract($_POST);
	$a=$mysql->query(\"select id from nivel where nome=\'Cliente\'\")->fetch(PDO::FETCH_NUM);
	$b=$mysql->query(\"select id from usuarios where usuario=\'$usuario\'\")->fetch(PDO::FETCH_NUM);
	if (!empty($a[0])){
		if (empty($b[0])) {
			$mysql->exec(\"insert into usuarios(nivel, usuario, senha, email, banner, nome, data, telefone, habilitado, cpf, rg, nascimento, sexo, rua, bairro, cep, cidade, estado,coordenada1) values ($a[0],\'$usuario\',md5(\'$senha\'),\'$e_mail\',0,\'$nome\',now(),\'$fone\',0,\'\',\'\',\'$ano-$mes-$dia\',\'$sexo\',\'$rua\',\'$bairro\',\'$cep\',\'$cidade\',\'$estado\',\'$coordenada1\')\");
			$m=\"<center><br>Your data has been successfully registered.<br><a href=?id=$br[0]>Click here</a> to make login.</center>\";
		} else {
			$m=\"<center><br>Your username is registered in the system. <a href=javascript:history.back()>Click here</a> to go back and correct the information.</center>\";
		} 
	} else {
		$m=\"<center>Setting a level of users \'Client\' in the system is required.</center>\";
	}
}  else if ($z==2) {
	//atualiza dados
	extract($_POST);
	$b=$mysql->query(\"select id from usuarios where usuario=\'$usuario\' and id!=$l\")->fetch(PDO::FETCH_NUM);
	if (empty($b[0])) {
		$mysql->exec(\"update usuarios set usuario=\'$usuario\', email=\'$e_mail\', nome=\'$nome\', telefone=\'$fone\', nascimento=\'$ano-$mes-$dia\', sexo=\'$sexo\', rua=\'$rua\', bairro=\'$bairro\', cep=\'$cep\', cidade=\'$cidade\', estado=\'$estado\', coordenada1=\'$coordenada1\' where id=$l\");
		!empty($senha)?$mysql->exec(\"update usuarios set senha=md5(\'$senha\') where id=$l\"):\'\';
		$m=\"<center><br>Your data has been successfully changed.<br><a href=?id=$id>Click here</a> view their information.</center>\";
	} else {
		$m=\"<center><br>Your username is registered in the system. <a href=javascript:history.back()>Click here</a> to go back and correct the information.</center>\";
	}
} else {
	$dias=\"<option value=01>1<option value=02>2<option value=03>3<option value=04>4<option value=05>5<option value=06>6<option value=07>7<option value=08>8<option value=09>9<option value=10>10<option value=11>11<option value=12>12<option value=13>13<option value=14>14<option value=15>15<option value=16>16<option value=17>17<option value=18>18<option value=19>19<option value=20>20<option value=21>21<option value=22>22<option value=23>23<option value=24>24<option value=25>25<option value=26>26<option value=27>27<option value=28>28<option value=29>29<option value=30>30<option value=31>31</select>\";
	$meses=\"<option value=01>1<option value=02>2<option value=03>3<option value=04>4<option value=05>5<option value=06>6<option value=07>7<option value=08>8<option value=09>9<option value=10>10<option value=11>11<option value=12>12\";
	$a=$l>0 ? $mysql->query(\"select usuarios.id, usuario, email, usuarios.nome, telefone, cpf, rg, nascimento, sexo, rua, bairro, cep, cidade, estado, nivel.nome, extra,coordenada1 from usuarios inner join nivel on nivel.id=usuarios.nivel where usuarios.id=$l\")->fetch(PDO::FETCH_NUM):0;
	$h=$a!=0?explode(\'-\',$a[7]):\'\';
	$ex=explode(\'|\',$a[15]);
	$m=\"
<SCRIPT src=http://maps.google.com/maps/api/js?sensor=false></SCRIPT>
<script>
	function testa(){
		f=document.form
		if(!f.nome.value) {alert(\'The Name field is mandatory\');return false}
		if(!f.ano.value) {alert(\'The Year field is mandatory\');return false}
		if(mail(f.e_mail.value)&&f.e_mail.value){alert(\'The Email field is not filled out correctly\');return false}
		if(!f.usuario.value) {alert(\'The User field is required\');return false}
		if(f.senha.value&&f.senha.value!=f.senhac.value\".($a==0?\'||!f.senha.value\':\'\').\") {alert(\'The Password field and the Confirm field must contain identical information\');return false}
		return true
	}
	var local
	var marker
	function mapa(loca,camada,e) {
		local=loca
		coordenada=document.getElementById(local).value
		var lat=(coordenada==\'\'?\'1,1\':coordenada).split(\',\')
		var latlng = new google.maps.LatLng(lat[0],lat[1])
		var myOptions = {zoom: (coordenada==\'\'?1:17),center: latlng,mapTypeId: google.maps.MapTypeId.HYBRID}
		map = new google.maps.Map(document.getElementById(\'map_canvas\'), myOptions)		
		google.maps.event.addListener(map,\'click\', function(event) {placeMarker(event.latLng)});
		if (coordenada!=\'\') marker = new google.maps.Marker({position: latlng ,map: map});
		geocoder = new google.maps.Geocoder();
		ver(camada,e)
	}
	function placeMarker(location) {	
		if (marker) marker.setMap(null);	
		var clickedLocation = new google.maps.LatLng(location);
		marker = new google.maps.Marker({position: location,map: map});
		map.setCenter(location);
		geocoder.geocode({\'latLng\': location}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {			
						if (geo=results[0].address_components) {
							geodata=\'\'
							for (ii=0;ii<geo.length;ii++) {
								if(geo[ii].types[0]==\'administrative_area_level_1\'&&geo[ii].short_name.length>2) geo[ii].short_name=\'SP\'
								if($(geo[ii].types[0])) $(geo[ii].types[0]).value=geo[ii].short_name
							}		
						}
					}		
				})
		x=location+\'\'
		y=x.substring(1,x.length-2).split(\',\')
		document.getElementById(local).value=y[0].substring(0,10)+\',\'+y[1].substring(1,11)
	}
</script>

<table border=0 cellpadding=0 align=center>
	<form method=post name=form action=?z=\".($a==0?1:2).\"&id=$id onsubmit=\'return testa()\'>
		<tr><td colspan=2>\".($a==0?\'Fill in\':\'Change\').\" the data in the fields and click <i>\'\".($a==0?\'Register\':\'Save\').\"\'</i>  to store your information.<br><br></td></tr>
		<tr><td align=right><b>Name:</b></td><td><input type=text name=nome size=60 class=text maxlength=150 onclick=this.select() \".($a!=0?\"value=\'$a[3]\'\":\'\').\"></td></tr>
		<tr><td align=right><b>Address:</b></td><td><input type=text name=rua id=route size=55 class=text maxlength=100 onclick=this.select() \".($a!=0?\"value=\'$a[9]\'\":\'\').\"> <img src=admin/i/idi.gif style=cursor:hand align=absmiddle onclick=\\\"mapa(\'coordenada1\',\'maas\',event)\\\"> <INPUT type=hidden name=coordenada1 id=coordenada1 value=\'$a[16]\'> </td></tr>
		<tr><td align=right><b>District:</b></td><td><input type=text name=bairro id=sublocality size=40 class=text maxlength=50 onclick=this.select() \".($a!=0?\"value=\'$a[10]\'\":\'\').\"> <b>Zip:</b><input type=text name=cep id=postal_code size=10 class=text maxlength=9 onclick=this.select() \".($a!=0?\"value=\'$a[11]\'\":\'\').\"></td></tr>
		<tr><td align=right><b>City:</b></td><td><input type=text name=cidade id=locality size=40 class=text maxlength=50 onclick=this.select() \".($a!=0?\"value=\'$a[12]\'\":\'\').\"> <b>State:</b><input type=text name=estado id=administrative_area_level_1 size=7 class=text maxlength=10 onclick=this.select() \".($a!=0?\"value=\'$a[13]\'\":\'\').\"></td></tr>
		<tr><td align=right><b>Phone:</b></td><td><input type=text name=fone size=60 class=text maxlength=250 onclick=this.select() \".($a!=0?\"value=\'$a[4]\'\":\'\').\"></td></tr>
		<tr><td align=right><b>E-mail:</b></td><td><input type=text name=e_mail size=60 class=text maxlength=50 onclick=this.select() \".($a!=0?\"value=\'$a[2]\'\":\'\').\"></td></tr>
		
		
		<tr><td align=right><b>Birth:</b></td><td><select name=dia>\".($a!=0?str_replace(\"value=$h[2]\",\"value=$h[2] selected\",$dias):$dias).\"</select>/<select name=mes>\".($a!=0?str_replace(\"value=$h[1]\",\"value=$h[1] selected\",$meses):$meses).\"</select>/<input type=text name=ano size=4 class=text maxlength=4 onclick=this.select() value=\'\".($a!=0? $h[0]:\'\').\"\'> <b>Sex: </b><input type=radio name=sexo class=radio value=0 \".($a==0?\'checked\': $a[8]==0?\'checked\':\'\').\">Male <input type=radio name=sexo calss=radio value=1 \".($a==0?\'\':$a[8]==1?\'checked\':\'\').\">Female</td></tr>
		<tr><td align=right><b>User:</b></td><td><input type=text name=usuario size=20 class=text maxlength=20 onclick=this.select() \".($a!=0?\"value=\'$a[1]\'\":\'\').\"></td></tr>
		<tr><td align=right><b>Password:</b></td><td><input type=password name=senha size=20 class=text maxlength=20> <b>Confirm:</b><input type=password name=senhac size=19 class=text maxlength=20></td></tr>
		<tr><td align=center colspan=2 height=30><br><input type=submit class=button value=\".($a!=0?\'Save\':\'Register\').\"><input type=button value=Cancel class=button onclick=\\\"window.location=\'?id=$id&z=0\'\\\"></td></tr>
		</form>
	</table>

<TABLE border=0 cellspacing=0 name=maas id=maas class=\'camada cor3\' width=450>
	<TR id=barra name=barra class=cor4>
		<TD><B>Map</B></TD>
		<TD align=right><IMG src=admin/i/clo.gif name=fecha id=fecha></TD>
	</TR><TR>
		<TD colspan=2><DIV id=map_canvas style=width:450;height:450></DIV></TD>
	</TR>
</TABLE>


\";
}
$m = empty($br[0])?\'The configuration of the system login is required for the functioning of the system.\':$m;
return $m;',NULL);
INSERT INTO sistemas VALUES('5','Folder News','0,','function ima($ma,$at){
	if (preg_match(\"/<[img||IMG][^<>]+>/\", $ma, $reg)) {
		if (preg_match(\"/src=[^<>]+\\.(jpg|JPG)/\",$reg[0],$loc)){
			$ma=str_replace(\'src=\',\'\',str_replace(\'\"\',\'\',\"$loc[0]\"));
		}
	}
	return !stristr($ma,\'.jpg\')?\'\':\"<img src=$ma width=$at border=0 align=\".(rand(0,1)==1?\'left\':\'right\').\">\";
}

$d=array(\'\',\'\',\'\',\'\');
$r=0;
$ex=\'\';
foreach($mysql->query(\"select id,nome,fonte,chave from arquivos where pai=$id and habilitado=1 \".(isset($_COOKIE[\'n\'])&&$_COOKIE[\'n\']!=1?\"and (concat(\',\',nivel) like \'%$_COOKIE[n],%\' or concat(\',\',nivel) like \'%,1,%\')\":\'\').\"  order by id desc\",PDO::FETCH_NUM) as $c){
	if ($r<4) {
		$f=300;
		$c[3]=str_replace(\'News category: \',\'\',$c[3]);
		$texto=strip_tags(str_replace(\"<BR>\",\"\\n\",str_replace(\"<br>\",\"\\n\",str_replace($c[3],\'\',str_replace($c[1],\'\',strstr($c[2],\'<!--ini-->\'))))));
		if (strlen($texto)>$f) while (substr($texto,$f,1)!=\' \') $f++;
		$texto=nl2br(substr($texto,0,$f+1));
		$d[$r]=\"<table border=0 cellspacing=0 cellpadding=1 width=97% align=center><tr><td class=cor3><B>$c[3]</B></td></tr><tr><td><br><a href=?id=$c[0] style=color:black><b>$c[1]</b> <br><br>\".ima($c[2],rand(50,75)).\"$texto<font color=red> <b>Read more</b></font></a><br><br> \".$d[$r].\"</td></tr></table>\";
	} else {
		$ex.=\"<a href=?id=$c[0] style=color:black> - $c[1]</a><br>\";
	}
	$r++;

}

return \"<BR><table border=0 width=100%>
	<tr valign=top>
		<td width=50%>$d[0]<br>$d[1]</td>
		<td width=50%>$d[2]<br>$d[3]</td>
	</tr>
</table>\".(!empty($ex)?\"<table border=0 width=97% align=center>
	<tr>
		<td Class=cor3><b>Other news</b></td>
	</tr><tr>
		<td>$ex</td>
	</tr>
</table>\":\'\');','include(\"dados.php\");
$data=date(\"Y-m-d\");
$meio_pagina=\'\';
if (isset($_GET[\'acao\'])){
	$mat=nl2br($_POST[\'m\']);
	if ($_FILES[\"f\"][\"size\"]>0){
		$id=$_POST[\'id\'];
		$nome=$id.date(\"YmdGis\").\".jpg\";
		thumbnail($_FILES[\"f\"][\"tmp_name\"],rand(200,230),0,\"../_gravar/$nome\",95);
		$ali= rand(0,1)==1 ? \'left\':\'right\';
		$mysql->exec(\"insert into arquivos(pai,data,nome,fonte,habilitado,menu,tipo,privilegio,chave,nivel,pasta,ordem,idioma) values($id,\'$data\',\'$_POST[n]\',\'<TABLE borderColor=white cellSpacing=1 cellPadding=1  width=100% align=center border=0><TBODY><TR class=cor3><TD> $_POST[c]</TD></TR><TR vAlign=top><TD><br><b>$_POST[n]</b><br><br><b>$_POST[dia]/$_POST[mes]/$_POST[ano]</b><br><p align=justify><!--ini--><img src=_gravar/$nome align=$ali>$mat<br><!--fim--></p></TD></TR><tr><td align=center><a href=javascript:history.back()><b>Voltar</b></a></td></tr></TBODY></TABLE>\',1,0,1,0,\'$_POST[c]\',\'1,\',0,0,$o)\");
		$mysql->exec(\"insert into imagens(pai,data,nome) values(\".$mysql->lastInsertId().\",\'$data\',\'$nome\')\");
	} else {
		$mysql->exec(\"insert into arquivos(pai,data,nome,fonte,habilitado,menu,tipo,privilegio,chave,nivel,pasta,ordem,idioma) values($id,\'$data\',\'$_POST[n]\',\'<TABLE borderColor=white cellSpacing=1 cellPadding=1 width=100% align=center border=0><TBODY><TR class=cor3><TD> $_POST[c]</TD></TR><TR vAlign=top><TD><br><b>$_POST[n]</b><br><br><b>$_POST[dia]/$_POST[mes]/$_POST[ano]</b><br><p align=justify><!--ini-->$mat<br><!--fim--></p></TD></TR></TBODY></TABLE>\',1,0,1,0,\'$_POST[c]\',\'1,\',0,0,$o)\");
	}
}
$meio_pagina=\"
<script>
function testa(){
	if (!document.l.ano.value) {alert(\'The year must be completed\');return false}
	if (!document.l.c.value) {alert(\'The subtitle should be filled\');return false}
	if (!document.l.n.value) {alert(\'The title should be filled\');return false}
	if (!document.l.m.value) {alert(\'The news must be filled\');return false}
	return true;
}
</script>
<table border=0 width=100% cellpadding=0 cellspacing=0><form enctype=multipart/form-data action=?acao=1&id=$id method=post name=l onsubmit=\'return testa()\'><input type=hidden name=id value=$id>
	<tr>
		<td colspan=2 class=cor3><img src=i/perso.gif align=absmiddle><b>System News</b></td>
	</tr><tr>
		<td align=right><b>Date:</b></td>
		<td><select name=dia>\".str_replace(\"value=\".date(\'d\').\">\",\"value=\".date(\'d\').\" selected>\",\'<option value=01>1<option value=02>2<option value=03>3<option value=04>4<option value=05>5<option value=06>6<option value=07>7<option value=08>8<option value=09>9<option value=10>10<option value=11>11<option value=12>12<option value=13>13<option value=14>14<option value=15>15<option value=16>16<option value=17>17<option value=18>18<option value=19>19<option value=20>20<option value=21>21<option value=22>22<option value=23>23<option value=24>24<option value=25>25<option value=26>26<option value=27>27<option value=28>28<option value=29>29<option value=30>30<option value=31>31</select>\').\" / <select name=mes>\".str_replace(\"value=\".date(\'m\').\">\",\"value=\".date(\'m\').\" selected>\",\'<option value=01>1<option value=02>2<option value=03>3<option value=04>4<option value=05>5<option value=06>6<option value=07>7<option value=08>8<option value=09>9<option value=10>10<option value=11>11<option value=12>12\').\"</select> / <input type=text name=ano size=4 value=\'\".date(\'Y\').\"\' class=text></td>
	</tr><tr>
		<td align=right width=100><b>Title:</b></td>
		<td><input type=text name=n size=77 maxlenght=250 class=text></td>
	</tr><tr>
		<td align=right><b>Subtitle:</b></td>
		<td><input type=text name=c size=77 maxlenght=250 class=text></td>
	</tr><tr>
		<td align=right><b>Image file:</b></td>
		<td><input name=f type=file size=62 class=text></td>
	</tr><tr>
		<td colspan=2><b>News</b><br><textarea name=m rows=15 style=width:650px></textarea></td>
	</tr><tr>
		<td align=center colspan=2 class=cor3><input type=submit value=Send class=button></td>
	</tr>
</form></table>\";
return \"$inicio_pagina$meio_pagina$final_pagina\";');
INSERT INTO sistemas VALUES('6','Folder Scrapbook','0,','//variaveis
$assinou=isset($_COOKIE[\'assinou\']) ? $_COOKIE[\'assinou\']:0;
$insere=\'\';
//insere mensagem
if (isset($_GET[\'assina\'])&&$assinou<3) {
	setcookie (\"assinou\", $assinou+1);
	include(\'admin/captcha.php\');
	if (isset($_POST[\'verificador\'])){
		if ($_POST[\'verificador\']==$captcha){
			$email=strip_tags($_POST[\'livro_email\']);
			$fonte=\"(<a href=mailto:$email>$email</a>)<br>\".strip_tags($_POST[\'livro_mensagem\']);
			$mysql->query(\"insert into arquivos(pai,data,nome,fonte,habilitado) values($id,now(),\'\".strip_tags($_POST[\'livro_nome\']).\"\', \'$fonte\', 0)\");
			$insere=\"<b>Your message has been sent for approval queue.<br><br></b>\";
		}
	}
}
//le livro
$d=0;
$e=20;
$i=isset($_GET[\'i\'])?$_GET[\'i\']:0;
$livro=\'\';
foreach($mysql->query(\"select id,date_format(data,\'%Y-%m-%d\'),nome,fonte from arquivos where pai=$id and habilitado=1 order by id desc limit $i,$e\",PDO::FETCH_NUM) as $c)  $livro.=\"<tr><td><hr></td></tr><tr><td><i>$c[1]</i> - <B>$c[2]</B> $c[3] <br></td></tr>\";
extract($mysql->query(\"select count(id) as t from arquivos where pai=$id and habilitado=1\")->fetch(PDO::FETCH_ASSOC));
//escreve tela
$conteudo=\"
<script src=admin/captcha.php?act=1></script>
<script>
function testa_livro(){
	f=document.livro;
	if(f.livro_nome.value==\'\'||f.livro_email.value==\'\'||f.livro_mensagem.value==\'\'){
		alert(\'All fields must be completed to enter your message\'); 
		return false
	}
	if(f.verificador.value!=captcha){
		alert(\'The verifier should be the same number of image\'); 
		return false
	}
	return true;	
}
function cap(){
	document.write(\'<img src=admin/captcha.php?act=2 align=absmiddle>\')
}
</script><table border=0 cellpadding=0 cellspacing=0 align=center><form method=post action=?id=$id&assina=1 onsubmit=\'return testa_livro()\' name=livro>
<tr>
	<td colspan=2>$insere Fill out the fields and click <b> \'Subscribe\' </b> to leave your message. <br> All posts will be reviewed before publication.<br><br></td>
</tr><tr>
	<td align=right><b>Name:</b> </td>
	<td><input type=input name=livro_nome maxlength=50 class=text size=50></td>
</tr><tr>
	<td align=right><b>E-mail:</b> </td>
	<td><input type=input name=livro_email maxlength=50 class=text size=50></td>
</tr><tr valign=top>
	<td align=right><b>Message:</b> </td>
	<td><textarea rows=3 cols=50 name=livro_mensagem></textarea></td>
</tr><tr>
	<td colspan=2 align=center><b>Verifier:</b> <script>cap()</script> <input type=input name=verificador maxlength=3 class=text size=3> <input type=submit value=Assinar class=button></td>
</tr></form>
</table><BR><table border=0 align=center>$livro<TR><TD align=center>\".($d>$e?($i==0 ? \'x\':\"<a href=?i=\".($i-$e).\"&id=$id class=controle><b>Back</b></a>\").\"|\".(($i+$e)>=$d? \'x\':\"<a href=?i=\".($i+$e).\"&id=$id class=controle><b>Next</b></a>\"):\'<BR>\').\"</TD></TR></table>\";
return $conteudo;','include(\"dados.php\");
isset($_GET[\'a\'])?$mysql->exec(\"update arquivos set habilitado=1 where id=$_GET[a]\"):null;
isset($_GET[\'d\'])?$mysql->exec(\"delete from arquivos where id=$_GET[d]\"):null;
//le livro
$e=20;
$i=isset($_GET[\'i\'])?$_GET[\'i\']:0;
$m=\'<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td class=cor4><img src=i/blist.gif align=absmiddle> <b>Aprovação de mensagens</b></td></tr><tr><td><br></td></tr>\';
foreach($mysql->query(\"select id,date_format(data,\'%d/%m/%Y\'),nome,fonte from arquivos where pai=$id and habilitado=0 order by id desc limit $i,$e\",PDO::FETCH_NUM) as $c) $m.=\"<tr><td><input type=checkbox class=radio onclick=\\\"window.location=\'?id=$id&a=$c[0]\'\\\"> <a href=?id=$id&d=$c[0]><img src=i/del.gif alt=\'Apagar mensagem\' border=0 align=absmiddle></a> <font color=navy>$c[1]</font> - $c[2] $c[3] <br></td></tr><tr><td><hr></td></tr>\";
$m=substr($m,0,-22);
extract($mysql->query(\"select count(id) d from arquivos where pai=$id and habilitado=0\")->fetch(PDO::FETCH_ASSOC));
$m=\"$m<tr><td align=center class=controle>\".($d>$e ? ($i==0 ? \'x\':\"<a href=?i=\".($i-$e).\"&id=$id class=controle><b>Voltar</b></a>\").\"|\".(($i+$e)>=$d? \'x\':\"<a href=?i=\".($i+$e).\"&id=$id class=controle><b>Avançar</b></a>\"):\'<BR>\').\"</td></tr><tr><td><br></td></tr><tr><td class=cor4><br></td></tr></table>\";

return $inicio_pagina.$m.$final_pagina;');
INSERT INTO sistemas VALUES('7','Folder Page','0,','if ($id>0) $a=$mysql->query(\"select arquivos.fonte from arquivos as ar inner join arquivos on ar.extra=arquivos.id where ar.id=$id\")->fetch(PDO::FETCH_NUM);
return $a[0];','include(\'dados.php\');

isset($_GET[\'s\'])?$mysql->exec(\"update arquivos set extra=\'$_POST[pagina]\' where id=$id\"):\'\';

foreach($mysql->query(\"select id,nome from arquivos where pai=$id and habilitado=1 order by ordem desc,nome\",PDO::FETCH_NUM) as $c) $m.=\"<option value=\'$c[0]\'>$c[1]\";
$a=$mysql->query(\"select extra from arquivos where id=$id\")->fetch(PDO::FETCH_NUM);

$m= \"
$inicio_pagina<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
<td class=cor4><b>See page</b></td>
</tr><tr><form method=post action=?id=$id&s=1 name=fo>
<td>Select the page to be displayed and click Save.<br>
<select name=pagina>\".str_replace(\"value=\'$a[0]\'>\",\"value=\'$a[0]\' selected>\",$m).\"</select><input type=submit class=button value=Save></td>
</tr></form>
</table>

$final_pagina
\";

return $m;');
INSERT INTO sistemas VALUES('8','Sitemap','0,','$m=\'\'; 
$a=$mysql->query(\"select titulo,descricao from dados\")->fetch(PDO::FETCH_NUM);
$b=$mysql->query(\"select max(id) from arquivos\")->fetch(PDO::FETCH_NUM);
foreach($mysql->query(\"select id,data,nome,chave from arquivos where habilitado=1 and privilegio=0 order by id\",PDO::FETCH_NUM) as $c){
	$m.=\"<item>
			<title>\".str_replace(\'&\',\'e\',utf8_encode($c[2])).\"</title>  
			<link>http://$_SERVER[HTTP_HOST]/index.php?id=$c[0]</link> 
			<description>\".str_replace(\'&\',\'e\',utf8_encode(strip_tags($c[3]))).\"</description>  
			<datePosted>\".date(\'D, d M Y H:i:s\', strtotime($c[1])).\" GMT</datePosted>  
		</item>\";
}

header (\"content-type: text/xml\");
header(\'Content-Disposition: inline; filename=sitemap.xml\');

return \"<!--nocode--><?xml version=\'1.0\' encoding=\'utf-8\'?>
<rss version=\'2.0\'> 
    <channel> 
        <title>\".str_replace(\'&\',\'e\',utf8_encode($a[0])).\"</title>  
        <link>http://$_SERVER[HTTP_HOST]/</link>  
        <description>\".str_replace(\'&\',\'e\',utf8_encode($a[1])).\"</description>  
        <language>pt-br</language>
		<image>
				<url>http://$_SERVER[HTTP_HOST]/_gravar/logo.gif</url>
				<title>\".str_replace(\'&\',\'e\',utf8_encode(\"$a[0] - $a[1]\")).\"</title>
				<link>http://$_SERVER[HTTP_HOST]/</link>
		</image>
        <copyright>Copyright \".utf8_encode(\"$a[0] - All rights are reserved. Reproduction of the contents of this website in any media, electronic or print, without permission is prohibited.\").\"</copyright> 
        <lastBuildDate>\".date(\'D, d M Y H:i:s\', strtotime($b[0])).\" GMT</lastBuildDate>
		<generator>WebSystem</generator>
        <ttl>20</ttl> 
         
		$m
			
	</channel> 
</rss> \";',NULL);
INSERT INTO sistemas VALUES('9','RRS','1,','$m=\'\'; 
$a=$mysql->query(\"select titulo,descricao from dados\")->fetch(PDO::FETCH_NUM);
$b=$mysql->query(\"select max(id) from arquivos\")->fetch(PDO::FETCH_NUM);
foreach($mysql->query(\"select a.id,a.data,a.nome,a.chave from arquivos a inner join arquivos b on a.pai=b.id where a.habilitado=1 and b.nome=\'News\' order by a.id desc\",PDO::FETCH_NUM) as $c){
	$m.=\"<item>
	<title>\".str_replace(\'&\',\'e\',utf8_encode($c[2])).\"</title>  
	<link>http://$_SERVER[HTTP_HOST]/index.php?id=$c[0]</link> 
	<description>\".str_replace(\'&\',\'e\',utf8_encode($c[3])).\"</description>  
	<datePosted>\".date(\'D, d M Y H:i:s\', strtotime($c[1])).\" GMT</datePosted>  
</item>\";
}

header (\"content-type: text/xml\");
header(\'Content-Disposition: inline; filename=sitemap.xml\');

return \"<!--nocode--><?xml version=\'1.0\' encoding=\'utf-8\'?>
<rss version=\'2.0\'> 
	<channel> 
		<title>\".utf8_encode($a[0]).\"</title>  
		<link>http://$_SERVER[HTTP_HOST]/</link>  
		<description>\".utf8_encode($a[1]).\"</description>  
		<language>pt-br</language>
		<image>
				<url>http://$_SERVER[HTTP_HOST]/_gravar/logo.gif</url>
				<title>\".str_replace(\'&\',\'e\',utf8_encode(\"$a[0] - $a[1]\")).\"</title>
				<link>http://$_SERVER[HTTP_HOST]/</link>
		</image>
		<copyright>Copyright \".utf8_encode(\"$a[0] - All rights are reserved. Reproduction of the contents of this website in any media, electronic or print, without permission is prohibited.\").\"</copyright> 
		<lastBuildDate>\".date(\'D, d M Y H:i:s\', strtotime($b[0])).\" GMT</lastBuildDate>
		<generator>WebSystem</generator>
		<ttl>20</ttl>
		$m
	</channel> 
</rss> \";',NULL);
DROP TABLE IF EXISTS temp;
CREATE TABLE `temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pai` int(11) NOT NULL DEFAULT '0',
  `nome` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=658 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS time;
CREATE TABLE `time` (
  `ip` char(16) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `pagina` int(10) unsigned DEFAULT NULL,
  `tempo` char(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS usuarios;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nivel` int(11) NOT NULL DEFAULT '0',
  `usuario` varchar(15) NOT NULL DEFAULT '',
  `senha` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(60) DEFAULT NULL,
  `banner` int(11) DEFAULT '0',
  `nome` varchar(150) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `telefone` varchar(250) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT '0',
  `cpf` varchar(250) DEFAULT NULL,
  `rg` varchar(250) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `sexo` tinyint(1) NOT NULL DEFAULT '0',
  `rua` varchar(250) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `razao` varchar(250) DEFAULT NULL,
  `fantasia` varchar(250) DEFAULT NULL,
  `insc` varchar(250) DEFAULT NULL,
  `cnpj` varchar(250) DEFAULT NULL,
  `enderecoe` varchar(250) DEFAULT NULL,
  `bairroe` varchar(50) DEFAULT NULL,
  `cidadee` varchar(50) DEFAULT NULL,
  `estadoe` varchar(100) DEFAULT NULL,
  `cepe` varchar(8) DEFAULT NULL,
  `fonee` varchar(250) DEFAULT NULL,
  `fax` varchar(250) DEFAULT NULL,
  `emaile` varchar(60) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `extra` longtext,
  `coordenada1` varchar(30) DEFAULT NULL,
  `coordenada2` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `id` (`id`),
  KEY `senha` (`senha`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
INSERT INTO usuarios VALUES('1','1','user','1a1dc91c907325c69271ddf0c944bc72','spider_poison@hotmail.com',NULL,'WebMaster','2005-06-21','1691152168','1','12312312387','1234567890','1978-01-01','0','Endereço Residênncia','Bairro Residência','12345678','Cidade','SP','Razão Social','Nome Fantasia',NULL,NULL,'Endereço Empresa','Bairro Empresa','Cidade Empresa','SP','12345678',NULL,NULL,NULL,NULL,NULL,'-21.794813,-48.183639','-21.763781,-48.200087');
