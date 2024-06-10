<?PHP
//include'dados.php';
function xmail($para,$assunto,$mensagem,$cabecalho) {
	$log='';
	$ip=$_SERVER['REMOTE_ADDR'];
	$reverso= isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:'Desabilitado';
	$emaix=$GLOBALS['mysql']->query("select mail_de,mail_se,mail_sm,mail_po,mail_lo,mail_us from dados")->fetch(PDO::FETCH_NUM);
	$data = date('r',time());
	$dmail="XYZ-".date("dmYis")."-ZYX";	
	
	//conexao
	$conexao = fsockopen(($emaix[3]=='465'?"tls://$emaix[2]":$emaix[2]), $emaix[3], $errno, $errstr, 10);	
	if (!$conexao) die("erro ao conectar no servidor<br>$errno<br>$errstr");	
	$log.= sock($conexao,'','');
	$server = str_replace('<BR>','',str_replace('S: ','',$log));	
	
	//apresentacao
	if ($emaix[3]=='465'){ //USA TLS		
		$ehlo=explode(' ',$log);
		$log.=sock($conexao,"EHLO $ehlo[2]");
	} else {
		$log.= sock($conexao,"HELO $emaix[2]");
	}
	
	//TLS
	if (strstr($log,'STARTTLS')){
		$log.=sock($conexao,'STARTTLS localhost');
		$log.=sock($conexao,'EHLO localhost');
	}
	
	//autoriza
	$log.= sock($conexao, "AUTH LOGIN\r\n".base64_encode($emaix[5])."\r\n".base64_encode($emaix[1]));
	$log.= sock($conexao,'','');
	$log.= sock($conexao,'','');
	
	//rementente
	$log.= sock($conexao, "MAIL FROM: <$emaix[0]>");
	
	//destinatarios	
	$rcpt_to="";
	$par=explode(',',str_replace(';',',',$para));
	foreach ($par as $pa) $log.= !empty($pa)?sock($conexao, "RCPT TO: <$pa>"):'';
	
	//dados
	$log.= sock($conexao, 'DATA');	
	fputs($conexao, "Subject: $assunto\r\n", 512);
	fputs($conexao, "To: ".str_replace(';',',',$para)."\r\n", 512);
	fputs($conexao, "$cabecalho\r\n\r\n");
	fputs($conexao, $mensagem);			
	fputs($conexao, "\r\n\r\n.\r\n");
	$log.=sock($conexao,'','');
	
	//sai
	$log.= sock($conexao, 'QUIT');	
	fclose($conexao);
	
	$emaix[4]==1?$GLOBALS['mysql']->query("insert into email() values(null,'$server',now(),'$ip','$reverso','$log')"):'';
}

function sock($conexao,$comando){
	$dados='';
	if (!empty($comando)){
		fputs($conexao, "$comando\r\n", 1024);
		$dados.= "C: $comando<BR>";
	}
	$loop = $rcv = 0;
	while(!feof($conexao)){	
		$loop++;
		if($rcv = fgets($conexao, 1024)){
			$dados.="S: $rcv<BR>";
			if($loop == 99 || substr($rcv, 3, 1) != "-") break;
		}else break;
	}
	return trim(utf8_encode($dados));
}
?>