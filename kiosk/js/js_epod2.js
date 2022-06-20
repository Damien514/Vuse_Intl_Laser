var encours=99, couleurSelected=defaultcol, maxfonts=0, maxicons=0,  ScreenSaverON=0, ScreenSaverCompteur=0, boutonNEXT=0, fontencours=0, contenu='',choix='';
var $colourcarousel,$skincarousel, $fontcarousel, $iconscarousel;
var elem = document.documentElement;

var backtext='';

/*
window.onerror = function(msg, url, linenumber) {
   //alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
   $('#debug').html('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
   //window.location.reload(true);
   return true;
}
*/

if (debug!='1') {
	document.addEventListener('contextmenu', event => event.preventDefault());
}

window.addEventListener("touchstart", touchHandler, false);
function touchHandler(event){
	ScreenSaverCompteur=0;
	if(event.touches.length > 1){
		event.preventDefault();
	}
}

$('video').bind('touchstart', function(event) {
	if(event.touches.length > 1){
		event.preventDefault();
	}
});

$('body').bind('touchend click', function(event) {
	ScreenSaverCompteur=0;
});

$(document).ready(function() {

	$('#main').css({'display':'block'});

$colourcarousel=$('#iconescouleur').flickity({
	// options
	cellAlign: 'center',
	contain: true,
	freeScroll: true,
	freeScrollFriction: 0.03,
	prevNextButtons: false,
	pageDots: false,
	selectedAttraction: 0.2,
	friction: 0.30
});

	var t="<div class='carousel-cell icons' style='background:none!important'></div>";
	t+="<div class='carousel-cell icons' style='background:none!important'></div>";


	var iii=0;

	while (iii<elementsEpod.length) {

		console.log(elementsEpod[iii]);
		if (elementsEpod[iii]=='COMPLEMENTARY') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'>LIMITED<br>EDITION</div></div>";
		} else if (elementsEpod[iii]=='CORE') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'><span class='en'>CORE<br>COLLECTION</span></div></div>";
		} else if (elementsEpod[iii]=='SWIPE') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'><span class='en'>SWIPE<br>TO CHOOSE</span></div></div>";
		}else if (elementsEpod[iii]=='DESIGN') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'>COLLECTION</div></div>";
		} else if (elementsEpod[iii]=='ICONS') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'><span class='en'>MINI<br>ICONS</span></div></div>";
		} else {
			t+="<div id='ic"+iii+"' class='carousel-cell icons'><div class='icons_skin' style='background:url(/assets/icons/"+elementsEpod[iii]+") center -212px/114px no-repeat!important;filter: invert(1);'>";
			t+="</div><div class='icons_skin_active' onclick='changeEpod("+iii+",0)'></div><div class='textelegende en'>"+elementsEpodName[iii]+"</div></div>";
			preloadImage("/assets/icons/"+elementsEpod[iii]);
		}
		iii++;
	}

	// Un peu de vide...
	t+="<div class='carousel-cell icons' style='background:none!important'></div>";
	t+="<div class='carousel-cell icons' style='background:none!important'></div>";

	$("#icones_skin").html(t);


	$skincarousel=$('#icones_skin').flickity({
		// options
		cellAlign: 'center',
		contain: true,
		freeScroll: true,
		freeScrollFriction: 0.03,
		prevNextButtons: false,
		pageDots: false,
		selectedAttraction: 0.2,
		friction: 0.30
	});

	maxicons=miniicons.length;
	var t="<div class='carousel-cell icons' style='background:none!important'></div>";
		t+="<div class='carousel-cell icons' style='background:none!important'></div>";


	var iii=0;

	while (iii<maxicons) {

		console.log(miniicons[iii]);
		if (miniicons[iii]=='COMPLEMENTARY') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'><span class='en'>LIMITED<br>EDITION</span></div></div>";
		} else if (miniicons[iii]=='SWIPE') {
			t+="<div class='icons' style='background:none!important'><div class='titreinter'><span class='en'>SWIPE<br>TO CHOOSE</span></div></div>";
		}else if (miniicons[iii]=='DESIGN') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'>COLLECTION</div></div>";
		} else if (miniicons[iii]=='ICONS') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'><span class='en'>MINI<br>ICONS</span></div></div>";
		} else {
			t+="<div id='ico"+iii+"' class='carousel-cell icons'><div class='icons_skin' style='background:url(/assets/icons/"+miniicons[iii]+") center -212px/114px no-repeat!important;filter: invert(1);'>";
			t+="</div><div class='icons_skin_active' onclick='changeEpod("+iii+",1)'></div><div class='textelegende en'>"+miniiconsName[iii]+"</div></div>";
			preloadImage("/assets/icons/"+miniicons[iii]);
		}
		iii++;
	}

	t+="<div class='carousel-cell icons' style='background:none!important'></div>";
	t+="<div class='carousel-cell icons' style='background:none!important'></div>";

	$("#miniicons").html(t);

	$iconscarousel=$('#miniicons').flickity({
		// options
		cellAlign: 'center',
		contain: true,
		freeScroll: true,
		freeScrollFriction: 0.03,
		prevNextButtons: false,
		pageDots: false,
		selectedAttraction: 0.2,
		friction: 0.30
	});

	// On pré-affiche les polices
	var i=0;
	var t="<div class='carousel-cell icons' style='background:none!important'></div>";
	t+="<div class='carousel-cell icons' style='background:none!important'></div>";
	maxfonts=polices.length;
	while (i<maxfonts) {
		if (polices[i]=='SWIPE') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'><span class='en'>SWIPE<br>TO CHOOSE</span></div></div>";
		} else if (polices[i]=='COMPLEMENTARY') {
			t+="<div class='carousel-cell icons' style='background:none!important'><div class='titreinter'><span class='en'>LIMITED<br>EDITION</span></div></div>";
		} else {
			chargePolice(polices[i]);
			t+="<div id='font_"+i+"' class='carousel-cell icons'><div class='icons_fonts' style=\"font-family:'"+polices[i]+"'\">";
			t+="<span class='fit2'>Abc</span>";
			t+="</div><div class='icons_skin_active' onclick='changeFonts("+i+")'></div>";
			t+="<div class='en textelegende'>"+policesname[i]+"</div>";
			t+="</div>";
		}
		i++;
	}

	$("#icones_polices").html(t);

	$fontcarousel=$('#icones_polices').flickity({
		// options
		cellAlign: 'center',
		contain: true,
		freeScroll: true,
		prevNextButtons: false,
		pageDots: false,
		selectedAttraction: 0.05,
		friction: 0.25
	});


	var t="";
	var i=0;
	while (i<customLIST.length) {
		t+='<img src="/assets/design/'+customLIST[i]+'" id="custom'+i+'" onclick="touche('+i+')">';
		i++;
	}

	$("#custom").html(t);

	var a=0;
	var t="";
	while (a<45) {
		if (a==19 || a==22 || a==42) t+="<span style='opacity:0'></span>";
		else t+="<span id='od"+a+"' ontouchend='touche2("+a+")'></span>";
		a++;
	}
	$("#grille").html(t);



	// On va attendre 3 secondes pour être certain que tout est prêt
	setTimeout(function() {

		fitty('.fit2', {
			minSize: 60,
			maxSize: 95,
			multiLine: false
		});
		console.log('Text fit ON.');

		$('#keyboard').jkeyboard({
			input: $('#letexte'),
			layout: 'english'
		});
		console.log('Virtual keyboard activated.');


	}, 3000);


	$('#main').css({'display':'block'});

	Screensaver();
	ScreensaverInterval();
});


var toucheencours=null;

function touche(i) {
	toucheencours=i;
	$('#custom img').removeClass('selected').addClass('notselected');
	$('#custom'+i).removeClass('notselected').addClass('selected');
	$('#grille').addClass('ok');
}

function touche2(i) {
	if (toucheencours!==null) {
		$('#od'+i).html("<img src='/assets/design/"+customLIST[toucheencours]+"'>");
		customDesign[i]=customLIST[toucheencours];
		toucheencours=null;
		$('#grille').removeClass('ok');
		$('#custom img').removeClass('selected notselected');
	} else {
		$('#od'+i).html('');
		customDesign[i]='';
	}
}

function chargePolice(it,id) {
	try {
		var tempFace = new FontFace(it, 'url("/assets/fonts/'+it+'")');
		console.log('Font '+it+' loaded.');
		document.fonts.add(tempFace);
	}
	catch(err) {
		alert(err.message);
	}

}

function resetEpod() {
	compterreur=0;
	$skincarousel.flickity('select', 0);
	$colourcarousel.flickity('select', 0);
	switchSide(0);
	removeKeyboard(1);
	$("#iconescouleur").removeClass('hide');

	$('.titre').html("CHOOSE YOUR COLOR");

	changeEpodCouleur(defaultcol,1);

	valeurNEXT=1;
	valeurBACK=100;
	couleurSelected=defaultcol;
	texteOrientation='';
	txt='';
	$('#pickcollection, #thanks, #thanks_v2, #audiosignature').css({opacity:0,display:'none'});
	$('#boutonNEXT, #boutonPREV').removeClass('hide');
	$('#textemenu, #orientation_polices, #miniicons, #icones_skin, #icones_polices, #boutonSUBMIT, #typedegravure, #custom').addClass('hide');
	$('#grille').css('display','none');
	$('#letexte').val('');
	$('body').removeClass('back gold rosegold graphite black silver pinksalt citrine emerald glacier sandstone cyan darkminst red');
	$('body').addClass(defaultcol)
	$('#champstexte').css('display','none');
	$('#grille span').html('');
	$('#champstexte span').html('').css({opacity:1});
}


function Screensaver() {

	$('#screensaver').css({display:'block'});

	ScreenSaverON=1;
	ScreenSaverCompteur=0;
	resetEpod();
	backtext='';

}


function stopScreensaver() {
	if (ScreenSaverON==1) {
		ScreenSaverON=0;
		ScreenSaverCompteur=0;

		if (nbcol==1) nextSTEP();
		if (debug!='1') {
			if (elem.requestFullscreen) elem.requestFullscreen();
			else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen();
			else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
			else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
		}

		$('#pickcollection').css({opacity:1,display:'block'});
		$('#screensaver').css({display:'none'});
	}
}

function ScreensaverInterval() {
	//$('#debug').html(ScreenSaverCompteur);

	if (ScreenSaverON==0) {
		if (ScreenSaverCompteur++>=dureeScreensaver) Screensaver();
	}
	setTimeout('ScreensaverInterval()',1000);
}

function changeEpodCouleur(coul,f=0) {
		if (couleurSelected!=coul || f==1) {
			$('body').removeClass('back gold rosegold graphite black silver pinksalt citrine emerald glacier sandstone cyan darkminst red').addClass(coul);
			couleurSelected=coul;
			$('#iconescouleur .icons').removeClass('active');
			$('#ic_'+couleurSelected).addClass('active');
			$('#epod').transit({'x':-500}, 300, 'ease-in', function() {
				$('#epod .epodlayer').css({'background':'transparent'});
				$('#mainepod').removeClass().addClass(coul);
				$(this).css({'x':1080, 'display':'block'}).transit({'x':290}, 300, 'ease-out', function() {
				});
				if (!boutonNEXT) {
					boutonNEXT=1;
					$('#boutonNEXT').removeClass('hide');
				}
			});
		}
}

function addTextOnBack() {
	$('#epod .epodlayer').css({'background':'transparent'});
	selectType('texte');
	$('#thanks_v2').transit({scale:1.3,opacity:0},400).css({display:'none'});
}

var customDesign=[];

function audiosignature() {
	startAudio();
	$('#audiosignature').css({opacity:0,display:'block','scale':'1.3'}).transit({scale:1,opacity:1},400);
}

function selectType(t) {
	$('#typedegravure').addClass('hide');

	txt='';
	encours=99;
	fontencours=99;
	valeurBACK=1;

	choix=t;

	$('#boutonPREV').removeClass('hide');

	if (t=='texte') {
		// Horizontal
		$fontcarousel.flickity('select', 0);
		switchOrientation(1);
		if (backtext==='') {
			switchSide(0);
		} else {
			$('#boutonPREV').addClass('hide');
			switchSide(1);
			$('#orientation_polices span.back').hide();
			$('#orientation_polices span.front').hide();
		}
		changeFonts(1);
		$('#champstexte').css('display','block');
		$('.titre').html("CUSTOM TEXT");
		setTimeout("$('#textemenu').removeClass('hide')",300);
		afficheKeyboard();
	} else if (t=='icons'){
		$iconscarousel.flickity('select', 0);
		changeEpod(1,1);
		setTimeout("$('#miniicons, #boutonSUBMIT').removeClass('hide')",300);
	} else if (t=='custom'){
		customDesign=[];
		$('.titre').html("CUSTOM DESIGN");


		setTimeout("$('#custom, #boutonSUBMIT').removeClass('hide')",300);
		$('#grille span').html('');
		$('#grille').css({'display':'block',opacity:0}).transit({opacity:1},500);
	} else if (t=='audiosignature'){
		$('.titre').html("AUDIO SIGNATURE");
 	   audiosignature();
	} else {
		$skincarousel.flickity('select', 0);
		changeEpod(1,0);
		setTimeout("$('#icones_skin, #boutonSUBMIT').removeClass('hide')",300);
	}
}


function afficheKeyboard() {
	$('#boutonSUBMIT').addClass('hide');
	valeurBACK=2;
	$("#keyboard").css({opacity:0,display:'block',y:'150px', scale: 2.5}).transit({opacity:1,y:0},500,'ease');
}

function afficheFonts() {
	valeurBACK=2;
	$('#textemenu').addClass('hide');
	setTimeout("$('#icones_polices').removeClass('hide')",300);
	$('#boutonPREV').removeClass('hide');
}

function afficheOrientation() {
	valeurBACK=2;
	$('#textemenu').addClass('hide');
	setTimeout("$('#orientation_polices').removeClass('hide')",300);
	$('#boutonPREV').removeClass('hide');
}

function menuTexte() {
	valeurBACK=1;
	setTimeout("$('#textemenu').removeClass('hide')",300);
	$('#orientation_polices').addClass('hide');
	$('#icones_polices').addClass('hide');
	$('#boutonPREV').addClass('hide');
}


function preloadImage(url) {
	var img=new Image();
	img.src=url;
	//console.log('Image "'+url+'" preloaded.');
}



var navOQP=0;
var valeurNEXT=0;

function nextSTEP() {
//	if (navOQP==0) {
//		navOQP=1;
//		setTimeout('navOQP=0;', 650);
		if (valeurNEXT==1) {
			$('#boutonNEXT, #iconescouleur').addClass('hide');
			$('#pickcollection').transit({opacity:0},250, function() {
				$(this).css({display:'none'});
			});

			setTimeout("$('#boutonPREV, #typedegravure').removeClass('hide')",300);

			$('.titre').html("CHOOSE YOUR ENGRAVING");

			valeurBACK=3;
			switchSide(0);
		}
//	}
}

var valeurBACK=0;
function backSTEP() {
//	if (navOQP==0) {
//		navOQP=1;
//		setTimeout('navOQP=0;', 650);

		if (valeurBACK==1) {
			$('.titre').html("CHOOSE YOUR ENGRAVING");

			removeKeyboard();
			$('#audiosignature').transit({opacity:0},250, function() {
				$(this).css({display:'none'});
			});
			$('#miniicons, #custom, #icones_skin, #iconescouleur, #textemenu, #boutonSUBMIT').addClass('hide');
			$('#grille').css('display','none');
			setTimeout("$('#typedegravure, #boutonPREV').removeClass('hide')",300);
			switchSide(0);
			$('#epod .epodlayer, #champstexte span').transit({opacity:0},500, 'ease', function() {
				$('#epod .epodlayer').css({'background':'transparent', opacity:1});
				$('#champstexte span').html('').css({opacity:1});
				$("#letexte").val('');
				$('body').removeClass('back');
				txt='';
				texteOrientation='';
			});
			valeurBACK=3;
		} else if (valeurBACK==2) {
			if (backtext!=='') $('#boutonPREV').addClass('hide');
			valeurBACK=1;
			setTimeout("$('#textemenu').removeClass('hide')",300);
			$('#orientation_polices, #icones_polices').addClass('hide');
			removeKeyboard();
		} else if (valeurBACK==3 && nbcol>1) {
			setTimeout("$('#iconescouleur').removeClass('hide')",300);
			$('.titre').html("CHOOSE YOUR COLOR");
			$('#typedegravure,#boutonSUBMIT').addClass('hide');
			$('#boutonNEXT,#boutonPREV').removeClass('hide');
			valeurBACK=100;
			$('#pickcollection').css({opacity:0,display:'block'}).transit({opacity:1},250);
		} else if (valeurBACK==100 || (valeurBACK==3 && nbcol==1)) {
			Screensaver();
		}
//	}
}


function validKeyboard() {
	valeurBACK==2;
	backSTEP();
}

function removeKeyboard(q=0) {
	if (!q) $('#boutonSUBMIT').removeClass('hide');
	$('#keyboard').transit({opacity:0, y:'150px'}, 500, 'ease', function() {
		$('#keyboard').css({display:'none'});
	});
}

function changeFonts(s) {
	if (fontencours!=s && s>=0 && s<maxfonts) {

		$('#champstexte span').transit({opacity:0}, 300, 'ease', function() {
			$(this).css('font-family','"'+polices[s]+'"');
			contenu=polices[s];
			updateTexteEpod();
			$(this).transit({delay:300,opacity:1},300,'ease');
		});

		$('#icones_polices .icons').removeClass('active');
		$('#font_'+s).addClass('active');
	}
}

var txt="";
function afficheTexte() {
	txt=$("#letexte").val();

	if (txt=='') txt='&nbsp;';
	$('#champstexte span').html(txt);
	updateTexteEpod();
}

function updateTexteEpod() {
	try {
		fitty('#champstexte span', {
			minSize: 16,
			maxSize:70,
			multiLine: false
		});
	}
	catch(err) {
		alert(err.message);
	}

}


/// Le paramètr T défini si on avait deja un ePod d'affiché
var epodchangeOQP=0;
function changeEpod(s,iconz=0) {
	//if (epodchangeOQP==0) {
	//	epodchangeOQP=1;
		if (encours!=s && s>=0) {

			$('.icons').removeClass('active');
			if (iconz) $('#ico'+s).addClass('active');
			else $('#ic'+s).addClass('active');


			var a=-500,b=1080;
			if (encours>s && encours!=99) [a,b]=[b,a];
			encours=s;

			if (iconz==0) var temp=elementsEpod[encours];
			else var temp=miniicons[encours];

			contenu=temp;

			$('#epod').transit({'x':a}, 300, 'ease-in', function() {
				$('#epod .epodlayer').css({'background':'transparent url("/assets/icons/'+temp+'") center 235px/178px auto no-repeat', 'filter': 'invert(1)'});

				$(this).css({'x':b, 'display':'block'}).transit({delay:50,'x':290}, 300, 'ease-out', function() {
					//epodchangeOQP=0;
				});
			});
		}
//	}
}

var texteOrientation='H';
function switchOrientation(s) {
	if (s==0) {
		texteOrientation='V';
		$('#champstexte').addClass('vertical');
		$('#orientation_polices span.horizontal').hide();
		$('#orientation_polices span.vertical').show();
	} else {
		texteOrientation='H';
		$('#champstexte').removeClass('vertical');
		$('#orientation_polices span.vertical').hide();
		$('#orientation_polices span.horizontal').show();
	}
}

var texteSide='F';
function switchSide(s) {
	if (s==1 && texteSide=='F') {
		texteSide='B';
		$('body').addClass('back');
		$('#orientation_polices span.back').show();
		$('#orientation_polices span.front').hide();
		changeEpodSide(1);
	} else if (s==0 && texteSide=='B') {
		texteSide='F';
		$('body').removeClass('back');
		$('#orientation_polices span.front').show();
		$('#orientation_polices span.back').hide();
		changeEpodSide(0);
	}
}


function changeEpodSide(o) {

		$('#champstexte').css({opacity:0});
		$('#mainepod').transit({'rotateY':'90deg', 'opacity':.5}, 300, function() {
			if (o==1) var ajout='_back';
			else var ajout='';
			$(this).removeClass().addClass(couleurSelected+ajout).css({'rotateY':'-90deg'}).transit({'opacity':1,'rotateY':'0deg'}, 300);
			$('#champstexte').css({opacity:1});
		});
}

var compterreur=0;
function submitDESIGN() {

	if (navOQP==0) {
		navOQP=1;
		if (choix=='custom') contenu=customDesign.join(';');

		$('#textemenu, #orientation_polices, #miniicons, #icones_skin, #icones_polices, #boutonSUBMIT, #typedegravure, #custom').addClass('hide');

			$.ajax({
				url: '/_generators_/generate.id.php?epodversion=2&orientation='+texteOrientation+'&position='+texteSide+'&contenu='+encodeURIComponent(contenu)+'&texte='+encodeURIComponent(txt).trim()+'&choix='+choix+'&epod='+encodeURIComponent(couleurSelected)+'&token='+backtext+'&storeid='+storeid+'&unique='+Math.random()+'&directdownload=yes',
				success: function(data) {

					compterreur=0;
					$('#thanks span.code, #thanks_v2 span.code').html(data);
					$('#grille').css('display','none');

					if (directdownload=='yes') {
						if (data>0) {
							var file_path = './directdownload.php?id='+data;

							var a = document.createElement('a');
							a.href = file_path;
							a.download = file_path.substr(file_path.lastIndexOf('/') + 1);
							document.body.appendChild(a);
							a.click();
							document.body.removeChild(a);
						}
					}

					if (choix!='texte') {
						$('#thanks_v2').css({opacity:0,display:'block','scale':'1.3'}).transit({scale:1,opacity:1},300);
						backtext=data;
					} else {
						$('#thanks').css({opacity:0,display:'block','scale':'1.3'}).transit({scale:1,opacity:1},300);
						backtext='';
					}
					ScreenSaverCompteur=-60;
					navOQP=0;
				},
				error: function(d) {
					if (compterreur++>4) {
						navOQP=0;
						compterreur=0;
						Screensaver();
					} else {
						setTimeout(function() {
							navOQP=0;
							submitDESIGN();
						}, 500+(500*compterreur));
					}
				}
			});
	}
}
