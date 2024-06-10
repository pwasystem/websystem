<?PHP
require("conexao.php");
if (isset($_GET['paleta'])){
	$mysql->exec("update dados set css='$_GET[paleta]'");
	die();
}
extract($mysql->query("SELECT css FROM dados")->fetch(PDO::FETCH_ASSOC));
$css=explode(';',$css);
header("content-type: application/javascript");
?>
function cor(){}
function pic(k){
	l=$('lcp').value
	cor.value=k
	pmc.style.visibility='hidden' 
	if (k!='null'){
		if (l=='backcolor'||l=='forecolor') {
			editar(l,k)		
		} else {
			$('c'+l).style.backgroundColor=k
			$(l).value=k
		}
	}
}
function corp(v,event){
	$('lcp').value=v
	ver('pmc',event)
}
function hex(d) {
	d=(d>255?255:(d<0?0:d)).toString(16).toUpperCase()
	return d.length<2?'0'+d:d;
}
function r2h(v){
	b=v.substr(4,v.length-5).split(',')
	return '#'+hex(parseInt(b[0]))+''+hex(parseInt(b[1]))+''+hex(parseInt(b[2]))
}
function hue(v1,v2,vH) {
	if (vH<0) vH+=1
	if (vH>1) vH-=1
	if ((6*vH)<1) return (v1+(v2-v1)*6*vH)
	if ((2*vH)<1) return (v2)
	if ((3*vH)<2) return (v1+(v2-v1)*((2/3)-vH)*6)
	return (v1);
}
cDown=false
var corY,corX=0
var corZ=50
function setCor(o,e) {
	if(o==1){
		corZ=ff?e.layerY:e.offsetY
	}else{
		corX=ff?e.layerX:e.offsetX
		corY=ff?e.layerY:e.offsetY
	}
	if(corY==0)corY=0.1
	H=corX/100
	S=corY/100
	L=corZ/100
	if(S==0){
		R,G,B=L*255
	} else {
		var_2=L<0.5?L*(1+S):(L+S)-(S*L)
		var_1 = 2*L-var_2
		R = Math.round(255*hue(var_1,var_2,H+(1/3)))
		G = Math.round(255*hue(var_1,var_2,H))
		B = Math.round(255*hue(var_1,var_2,H-(1/3)))
	}
	R=!R?0:R
	G=!G?0:G
	B=!B?0:B
	Cor="#"+hex(R)+""+hex(G)+""+hex(B)
	document.getElementById('corH').value=Cor
	document.getElementById('pbf').bgColor=Cor
	document.getElementById('cfu').style.backgroundColor=Cor
}
var cPer=false
function peCor(cmp){
	if(cPer){
		cmp.style.backgroundColor=cPer
		cPer=false
		document.getElementById('corH').style.backgroundColor=''
		cpr=''
		for (ij=0;ij<8;ij++){
			cur=document.getElementById('pcu'+ij).style.backgroundColor
			cpr=cpr+';'+(cur.indexOf('rgb')>=0?r2h(cur):cur)
		}
		carrega('paleta.php?paleta='+escape(cpr.substr(1)))
	}else{
		cur=cmp.style.backgroundColor
		if(cur){
			if (cur.indexOf('rgb')>=0)cur=r2h(cur)
			document.getElementById('corH').value=cur.toUpperCase()
			document.getElementById('cfu').style.backgroundColor=cur
		}		
	}
}
function pPer(){
	if(!cPer||cPer!=document.getElementById('corH').value){
		cPer=document.getElementById('corH').value
		document.getElementById('corH').style.backgroundColor=document.getElementById('corH').value
	}else{
		cPer=false
		document.getElementById('corH').style.backgroundColor=''
	}
}

document.write("<table cellpadding=0 cellspacing=2 border=0 onselectstart='return false'><tr><td><img src='data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAAiAAD/7gAOQWRvYmUAZMAAAAAB/9sAhAAOCgoKCwoOCwsOFQ0MDRUYEg4OEhgcFhYXFhYcGxUXFxcXFRsbICEjISAbKysuLisrPj09PT5AQEBAQEBAQEBAAQ8NDQ8RDxMQEBMUDxEPFBcSFBQSFyIXFxkXFyIsHxsbGxsfLCYpIyMjKSYvLywsLy87Ozk7O0BAQEBAQEBAQED/wAARCABkAGQDASIAAhEBAxEB/8QAcgAAAgMBAQAAAAAAAAAAAAAABAUAAgMGAQEAAwEBAAAAAAAAAAAAAAAABAUGAwIQAAEDBAICAwEAAAAAAAAAAAABAgQhMUEDYTIiExEzFBIRAAEDBAICAwEAAAAAAAAAAAABEQMxAgQFIUEyE7Ei0rL/2gAMAwEAAhEDEQA/AOCSMXSJwM2R+DdkXgxukY2ujYTfj4PFicD5YtLGbovBykoPerCT8vBPy8Dn83B6kbg2sucxWUSrF4KLG4Hjo3Bg/R8YN7bHOrZHFPoPUjh/pqXbpO1iN7EcXfm4PPz8DVNHBF0cGd1rGyRCr85Bp6OCGTnXqGmrSGao/BNOsY6NIvlkY3mjYEWNSxi+PwOl0UsD7NJlbKKclWcU+jgskfgO9VS6aQ/HucXXSizZopYE26fgd7dVBfvZcbRWOawyOK111NGai6t8jbWwIui4GuOjsZppoRdIYmuh4usDntZBnZFwBeogV/FSAL/J36Q+O2o2ja7CyLdB1FbYTZEjHWXGzmjtXiCbdY1czxAtzQaOXkms5WcA9dTRusv/ADU0a0c4NzsIr5eQTfroKpLbjuQlBNKyUuNY7BOLI4tXsEakBnO8wnSobfFwUeEjsFNbQq5po2xVwrzLWQfxxcIZfFSHvzUgof5NvSEw3VQfw8HNQn1Q6SCtiezL2czz7GcZPTxF+/IwevgLpC3AYZeSL2is4MlzZoOjqmzFKTWK7EvJLypnK6iGY74+R3Ld4qc5OfcssOx0QMwZHVAB2zzC9D7Cl2zzDY+yw0ki+pYa1HYbsd4lXOMmP8Sj3iLYWsilbDH9UL/1UgP/AHUhPv8A0EeoMg3Q6WBg5qDdDpYGCbzexfsOxo/oLJGRm/oLJGQCGpC7bsDS5uwwS5uwqNV0SUtVB5nVTnJ+To5nVTnJ+S4waIG4FUEjvsDI+AN32BkfA2k8S21XQxZ1KvLM6lXk/sqKWUHihjkhMkJv9BIwg3Q6WBg5qDdDpYGCczexRsOxo/oLJGRm/oLJGQCGpC7bsDS5uwwS5uwqNV0SUtVB5nVTnJ+To5nVTnJ+S4waIG4FUEjvsDI+AN32BkfA2k8S21XQxZ1KvLM6lXk/sqKWUHihjkhMkJv9BIwg3Q6WBghCczexRsOxo/oLJGSEAIakLtuwNLm7CEKjVdElLVQeZ1U5yfkhC4waIG4FUEjvsDI+CEG0niW2q6GLOpV5CE/sqKWUHihjkhCE3+gk/9k=' id=co onclick=setCor(0,event) onmousemove=if(cDown)setCor(0,event) onmousedown=cDown=true onmouseup=cDown=false onmouseout=cDown=false oncontextmenu='return false' ondragstart='return false' style=cursor:crosshair></td><td id=pbf><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAABkCAYAAABHLFpgAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAOBJREFUeNp8j8FuwjAQRLOzm5JQtRWXov7/D/XYW0/cuKAUx4lpYGcTQEhVLeXpeWadxNX7+vVcbZ/fSvXxskkQkQIBClwH+BpdQJPMIrKeyH9YD1WHQBM0Mo1C++tWlxHXTMvMMtuwwZ8ACBuZFW4dCNOJWQU1QheYtw4jmMWI6X3EmGHJwLOGMFtG7HZM/8fDd3kPntLaC9T8yZr7J2arGR6uWNC04WVabhsWLV/QzlnYPfMVxXq2K5pHa6Truk85Hn++pE/pW3LOOxmHcS+llIOcTr9JpmkqcvYVuAgwAGZZTjW5lit+AAAAAElFTkSuQmCC' id=pb onclick=setCor(1,event) onmousemove=if(cDown)setCor(1,event) onmousedown=cDown=true onmouseup=cDown=false onmouseout=cDown=false oncontextmenu='return false'  ondragstart='return false' style=cursor:crosshair;width:10;height:100></td></tr><tr><td colspan=2><table cellpadding=0 cellspacing=1 width=100% onselectstart='return false'><tr><td><input type=text id=corH value=#FFFFFF style='font:10px arial;border:solid 1px gray;height:16;width:48'></td><td height=14><div style='border:solid 1px black;height:14;width:14;font-size:1' onclick=pPer() align=absmiddle id=cfu></div></td><td><input type=button value=ok style='font:bold 9px verdana;border:solid 1px gray;height:16;width:20;padding:0' onclick=pic($('corH').value)></td><td><input type=button value=X onclick=ver_n('pmc') style='font:bold 9px verdana;border:solid 1px gray;height:16;width:20;padding:0'></td></tr></table></td></tr><tr><td colspan=2 onselectstart='return false'><table cellpadding=0 cellspacing=1 width=100% height=10><tr><td id=pcu0 onclick=peCor(this) style='background-color:<?=$css[0]?>;border:solid 1px gray;font-size:1;height:14'>&nbsp;</td><td id=pcu1 onclick=peCor(this) style='background-color:<?=$css[1]?>;border:solid 1px gray;font-size:1'>&nbsp;</td><td id=pcu2 onclick=peCor(this) style='background-color:<?=$css[2]?>;border:solid 1px gray;font-size:1'>&nbsp;</td><td id=pcu3 onclick=peCor(this) style='background-color:<?=$css[3]?>;border:solid 1px gray;font-size:1'>&nbsp;</td><td id=pcu4 onclick=peCor(this) style='background-color:<?=$css[4]?>;border:solid 1px gray;font-size:1'>&nbsp;</td><td id=pcu5 onclick=peCor(this) style='background-color:<?=$css[5]?>;border:solid 1px gray;font-size:1'>&nbsp;</td><td id=pcu6 onclick=peCor(this) style='background-color:<?=$css[6]?>;border:solid 1px gray;font-size:1'>&nbsp;</td><td id=pcu7 onclick=peCor(this) style='background-color:<?=$css[7]?>;border:solid 1px gray;font-size:1'>&nbsp;</td></tr></table></td></tr></table>")