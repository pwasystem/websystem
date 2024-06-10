function $(Obj){return document.getElementById(Obj)} //ponteido para objeto
function _(funcao,parametros){funcao(parametros)} //ponteiro de função
/*_______________________________________Bloqueios_____________________________________*/
function bloqueia_copia(event){
	if (typeof event.preventDefault != 'undefined' && event.target.type != 'text' && event.target.type != 'password' && event.target.type != "select-one" && event.target.type != "select-multiple" && event.target.type != "radio" && event.target.type != "checkbox" && event.target.type != "submit" && event.target.type != "reset" && event.target.type != "button" && event.target.type != "image" && event.target.type != "textarea" && event.target.type != "file") {
		event.preventDefault()
	}
}
/*_____________________________________Validadores ___________________________________*/

//valida numero
function numero(n){if(!n.match(/^[-]?[0-9]*$/)){return true}else{return false}}

//valida valor
function moeda(n){if(!n.match(/^[0-9,\.]*$/)){return true}else{return false}}

//valida e_mail
function mail(email){if(!email.match(/[A-Za-z0-9_.-]+@([A-Za-z0-9_-]+\.)+[A-Za-z]{2,4}/)){return true}else{return false}}

//valida data
function data(data){if (data.match(/[12][0-9]{3}(\-)(0[1-9]|1[012])(\-)(0[1-9]|[12][0-9]|3[01])/)){return true}else{return false}}

/*_____________________________________Sistema________________________________________*/
//funcao de busca 
function w_usuario(pai,param){
	wdor.innerHTML=param==0 ?"<b>Find: </b><input type=text id=wr class=text maxlenght=30><input type=button class=button onclick=\"window.location='?pai="+pai+"&w='+$('wr').value\" value='ok'><input type=button value=' x ' onclick=w_usuario(1) class=button>":'';
}

//função de postagem
function posta(id,valor,nome){	
	if (nome=='apaga') {
		avisar('The deleted item could not be retrieved<BR>Do you really want to continue?<BR><BR><INPUT type=button class=button value=Yes onclick=posta('+id+','+valor+',\'deleta\')> <INPUT type=button class=button value=No onclick=fechaviso()>')		
	} else {
		window.location='?pai='+$('pai').value+'&i='+$('i').value+'&id='+id+'&'+nome+'='+valor;
	}
}
//função de postagem
function pajax(id,valor,nome){
	re = new RegExp('>(.*?)<','g');
	cha=bkp.match(re).toString().substring(1)
	cha=cha.substring(0,cha.length-1)
	bkp=bkp.replace('>'+cha+'<','>'+(nome=='senha'?'********':valor)+'<')
	bkp=bkp.replace("'"+cha+"'","'"+(nome=='senha'?'********':valor)+"'")
	$(nome+id).innerHTML=bkp
	if (nome=='link') valor=escape(valor)
	carrega('?id='+id+'&'+nome+'='+valor,'jax')
}
//Gera caixa
caixa=true
function c(id,nome,valor,tamanho,caracteres,tipo){
	if (caixa){
		l=$(nome+id)
		bkp=l.innerHTML
		valor=unescape(valor)
		while (valor.match('"'))valor=valor.replace('"','&xquot;')
		l.innerHTML='<input type=text id='+nome+' value="'+valor+'" class=text size='+tamanho+' maxlength='+caracteres+' onclick=this.select()>'+ (tipo==0?'<input type=button onclick=posta('+id+',$(\''+nome+'\').value,"'+nome+'") value=ok class=button>':'<input type=button onclick=pajax('+id+',$(\''+nome+'\').value,"'+nome+'") value=ok class=button>')+'<input type=button value=" X " class=button onclick=retorna('+id+')>'
	} else {
		l.innerHTML=bkp
		caixa=true
		c(id,nome,valor,tamanho,caracteres,tipo)
	} 
	caixa=false
}
function retorna(id){
	!caixa ? l.innerHTML=bkp:null;
	caixa=true
}

//gera botao select
function men(a,b,c,d,k,f,g,h,j,k,e){//id,selecionar,array,label,excluir,exclui,limpa,acao,evento
	mu=a
	ir=d
	acao=j
	$('labe').innerHTML=k
	ver('barra2',e)	
	mel=$('meu')
	if (g==0) while (mel.length>0) mel.remove(0)	
	for (ii=0;ii<c.length;ii++) {
		if (c[ii]){
			if (f==1&&h[ii]==k){
			} else {
				var opt = document.createElement('OPTION')
				mel.options.add(opt)
				opt.innerText = c[ii]
				opt.text = c[ii]
				opt.value = h[ii]
				h[ii]==b?opt.selected=true:null
			}
		}
	}
}

//gerencia add niveis
seguranca=0
function niv(sel,se,event){
	seguranca=se
	ver('barra1',event)
	sel=','+sel
	tes=''
	while (per.length>0) per.remove(0)
	while (neg.length>0) neg.remove(0)	
	for (ii=0;ii<n1.length;ii++) {
			var opt = document.createElement('OPTION')
			sel.match(','+n0[ii]+',')? neg.options.add(opt):per.options.add(opt);
			opt.innerText = n0[ii]==1?'All':n1[ii]
			opt.text = n0[ii]==1?'All':n1[ii]
			opt.value = n0[ii]
	}
}

//move dados
function mn(ind,dev){
	if (ind>=0){
		a=(dev==0?neg:per)
		b=(dev==0?per:neg)
		var opt = document.createElement('OPTION')
		x=dev==0?$('per'):$('neg')
		a.options.add(opt)
		opt.innerText=x[ind].innerText
		opt.text=x[ind].innerText
		opt.value=x[ind].value
		b.remove(ind)
	}
}
//arrasta objeto
move=0
drag=0
dragObj=''
ff=navigator.userAgent.indexOf("Firefox")!=-1
window.document.onmousedown=function(e) {
	if (drag) {		
		clickleft=ff?e.layerX:e.offsetX
		clicktop=ff?e.layerY:e.offsetY
		dragObj.style.zIndex+=1
		move = 1
	}
}
window.document.ondragstart=function(e) {
	e.preventDefault()
}
window.document.onmousemove=function(e) {	
	if (move) {
		document.onselectstart=new Function ("return false")
		dragObj.style.left=e.clientX+document.body.scrollLeft-clickleft
		dragObj.style.top=e.clientY+document.body.scrollTop-clicktop
	}
}
window.document.onmouseup=function mouseUp() {
	move=0
	document.onselectstart=new Function ("return true")
}

//exibe camadas
bbkp=null
function ver(cam,e){//camada,comando
	if (!location.href.match('editorvisual.php')){
		bbkp?$(bbkp).style.visibility='hidden':null
		bbkp=cam
	}
	if(e==null)e=window.event;
	x=e.clientX+document.body.scrollLeft
	y=e.clientY+document.body.scrollTop
	if (x+$(cam).clientWidth>document.body.clientWidth) x=x-(x+$(cam).clientWidth-document.body.clientWidth-document.body.scrollLeft)-5
	if (y+$(cam).clientTop>document.body.clientHeight) y=y-(y+$(cam).clientHeight-document.body.clientHeight-document.body.scrollTop)-5
	$(cam).style.visibility=$(cam).style.visibility=='visible'?'hidden':'visible';
	$(cam).style.top=y
	$(cam).style.left=x
}

function ver_n(cam){
	$(cam).style.visibility='hidden'
}

//ativa comandos nas classes
function classe() {
	//fechar
	ix=document.getElementsByName('fecha')
	for (ii=0;ii<ix.length;ii++) ix[ii].onclick=function (){ver_n(this.parentNode.parentNode.parentNode.parentNode.id)}
	//existir
	ni=document.getElementsByTagName('TABLE')
	for (ii=0;ii<ni.length;ii++) if (ni[ii].className=='camada cor3') ni[ii].onmousedown=function(){dragObj=this}
	//arastar
	ic=document.getElementsByName('barra')
	for (ii=0;ii<ic.length;ii++){
		ic[ii].onmouseover=function(){drag=1}
		ic[ii].onmouseout=function(){drag=0}
		ic[ii].onselectstart=function(){return false}
		ic[ii].className='barra cor4'
	}
	//cor
	fi=document.getElementsByName('flip')
	for (ii=0;ii<fi.length;ii++){
		fi[ii].onmouseover= function () {this.className='cor3'}
		if (fi[ii].className=='cor1'){
			fi[ii].onmouseout= function () {this.className='cor1'}
		} else if (fi[ii].className=='cor2') {
			fi[ii].onmouseout= function () {this.className='cor2'}
		}
	}
}

//posta enquete
function enquete_posta(id){
	quant=document.enquete_post.r.length
	for (a=0;a<quant;a++){
		if (document.enquete_post.r[a].checked) {
			document.enquete_post.resposta.value=a
			document.enquete_post.submit()
		}
	}
}

//imprime conteudo visualizado
function imprime(camada){
	x_print=window.open('','imprime','toolbar=0,location=0,directories=0,status=0,menubar0,scrollbars=1,resizable=0,width=570,height=600')
	x_print.document.open();
	x_print.document.write('<html><head><title>Print</title><link rel=stylesheet href='+(window.location.href.match("admin")?'../':'')+'_gravar/css.css></head><body topmargin=0 leftmargin=0 onload=self.print()><table border=0 width=550 cellspacing=0><tr><td>'+camada.innerHTML+'</td></tr></table></body></html>')
	x_print.document.close()
}

//funções ajax url cam pos
function carrega(url,camada,form){
	this.data=''
	if (camada&&$(camada).tagName!='INPUT'&&$(camada).tagName!='TEXTAREA') $(camada).innerHTML='Loading ...'
	conexao=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP")
	conexao.onreadystatechange=function(){
		if (conexao.readyState==4&&conexao.status==200){
			if (camada) {
				$(camada).tagName=='INPUT'||$(camada).tagName=='TEXTAREA'?$(camada).value=unescape(conexao.responseText):$(camada).innerHTML=unescape(conexao.responseText)
			} else {
				this.data=conexao.responseText
			}
		}
	}
	url=form?form.action:url
	conexao.open((form?'POST':'GET'),(url+''+(url.match("\\?")?'&':'?')+'random='+Math.random()),true);
	if (form) {
		campos=''
		for (ii=0;ii<form.length;ii++) campos+=form[ii].name+'='+escape(Url(form[ii].value))+'&'
		conexao.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
	}
	conexao.send((form?campos:null));
}
function Url(variavel){
	while (variavel.match(/\+/) )variavel=variavel.replace(/\+/,'%2b')
	return variavel
}

//escreve banner na tela
function banner(src,largura,altura){	
	document.write("<EMBED src='"+src+"' wmode=transparent quality=high scale=noscale salign=LT bgcolor=#FFFFFF WIDTH="+largura+" HEIGHT="+altura+" TYPE=application/x-shockwave-flash PLUGINSPAGE=http://www.macromedia.com/go/getflashplayer></EMBED>")
}

//caixa de aviso
function avisar(mensage,fec){
	document.body.style.overflow='hidden'
	$('jax').style.display='table-cell'			
	$('mensagem').innerHTML=mensage+(fec==1?"<BR><BR><INPUT type=button class=button onclick=fechaviso() value=' Ok ' >":'')
	$('aviso').style.visibility='visible'
	$('aviso').style.top=document.body.scrollTop+document.body.clientHeight/2-50
	$('aviso').style.left=document.body.scrollLeft+document.body.clientWidth/2-125
	
}
function fechaviso(){
	$('jax').style.display='none'
	$('aviso').style.visibility='hidden'
	document.body.style.overflow='auto'
}

//funções do editor
function clui(variavel){
	resultado=''
	for(yi=0;yi<=variavel.length;yi+=2)resultado+=variavel.substr(yi+1,1)+''+variavel.substr(yi,1)
	return resultado
}