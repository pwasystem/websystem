<?PHP
function novo(){
	$linhas=explode('|º|',base64_decode(file_get_contents(realpath("_gravar/db.sql"))));
	foreach($linhas as $sql) if(!empty($sql))$GLOBALS['mysql']->exec($sql);
	DIE("<SCRIPT>window.location='?id=1'</SCRIPT>");
}

function thumbnail($imagem_inicio,$x_final,$y_final,$imagem_saida,$qualidade){
	$xy=getimagesize($imagem_inicio);
	$x_inicial=$xy[0];
	$y_inicial=$xy[1];
	$y_final=$y_final==0?($y_inicial*(($x_final*100)/$x_inicial))/100:$y_final;
	$x_final=$x_final==0?($x_inicial*(($y_final*100)/$y_inicial))/100:$x_final;
	$recebe_imagem=imagecreatetruecolor($x_final-1,$y_final-1);
	if($xy[2]==3) {
		$carrega_imagem=imagecreatefrompng($imagem_inicio);
	} elseif($xy[2]==1) {
		$carrega_imagem=imagecreatefromgif($imagem_inicio);
	} else {
		$carrega_imagem=imagecreatefromjpeg($imagem_inicio);
	}	
	ImageCopyResampled($recebe_imagem,$carrega_imagem,0,0,0,0, $x_final , $y_final ,$x_inicial,$y_inicial);
	imageinterlace($recebe_imagem,100);
	if($xy[2]==3) {
		return imagepng ($recebe_imagem,$imagem_saida);
	} elseif($xy[2]==1) {		
		return imagegif ($recebe_imagem,$imagem_saida);
	} else {
		return imagejpeg ($recebe_imagem,$imagem_saida,$qualidade);
	}	
}
function clui($variavel){
	$resultado='';
	for($i=0;$i<=strlen($variavel);$i+=2)$resultado.=substr($variavel,$i+1,1).''.substr($variavel,$i,1);
	return $resultado;
}
?>