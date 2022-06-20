	<div id="audiosignature">

		<div id="recordAudio" class="center">
			<div class='startaudio'>
				<h4>Hit the record button to start</h4>
			</div>
			<h4 class='ready'><span class="en">Get ready</span><span class="fr">Tenez-vous prêt</span></h4>
			<h4 class='recording'><span class="en">Recording now</span><span class="fr">Enregistrement en cours</span></h4>
			<canvas id="can" width="400" height="100"></canvas>
			<br>
			<div id="boutons">
				<span class="boutonrondstart" onclick="startAudioVis2();"><img src="audio/micro.svg" /></span>
				<span class="boutonrondcount"></span>
				<span class="boutonrondstop" onclick="stopAudio();"><img src="audio/stop.svg" /></span>
			</div>
		</div>

		<div id="pickAudio" class="center">


				<!-- Collection Core -->
				<div id="AScorecollection">
						<div class='carousel-cell icons' style='background:none!important'></div>
						<div class='carousel-cell icons' style='background:none!important'></div>

						<div id="audioPP1" class="icons">
							<div class='icons_skin2' onclick="audioP(1)" id="audioP1"></div>
						</div>
						<div id="audioPP2" class="icons">
							<div class='icons_skin2' onclick="audioP(2)" id="audioP2"></div>
						</div>
						<div id="audioPP3" class="icons">
							<div class='icons_skin2' onclick="audioP(3)" id="audioP3"></div>
						</div>
						<div id="audioPP4" class="icons">
							<div class='icons_skin2' onclick="audioP(4)" id="audioP4"></div>
						</div>
						<div id="audioPP5" class="icons">
							<div class='icons_skin2' onclick="audioP(5)" id="audioP5"></div>
						</div>
						<div id="audioPP6" class="icons">
							<div class='icons_skin2' onclick="audioP(6)" id="audioP6"></div>
						</div>
						<div id="audioPP7" class="icons">
							<div class='icons_skin2' onclick="audioP(7)" id="audioP7"></div>
					</div>
					<div class='carousel-cell icons' style='background:none!important'></div>
					<div class='carousel-cell icons' style='background:none!important'></div>
				</div>
			</div>

		</div>

	</div>


<script>

	function startAudio() {
		choix="audio";
		$('#can').css({'opacity':0, 'display':'none'});
		$("#chooseaudio, #recordAudio").show();
		$('#pickAudio, h4.ready, h4.recording').hide().css('opacity',1);
		$('.startaudio').show();
		selectedAUDIOid='';
		$('.boutonrondcount').css({'display':'none','opacity':0});
		$('.boutonrondstop').css({'display':'none','opacity':0});
		$('.boutonrondstart').css({'display':'inline-block','opacity':1});
		$("#layerAUDIO").html('');
		$('#backAUDIO').removeClass('select');
		$('#frontAUDIO').addClass('select');
		$('#backepodAUDIO').css({'opacity':1,'rotateY': '0deg'});

		$('#backAUDIO, #frontAUDIO').show();
		collection='core';
		$('#AScorecollection').css({opacity:1,display:'block'});

		oldaudioid=0;

	}

	function startAudioVis2() {
		$('.boutonrondstart').transit({'opacity':0},250, function() {
			$('.startaudio').hide();
			$('.boutonrondcount').html('').css({'opacity':0, 'display':'inline-block'});
			$('h4.ready').show();
			$('.boutonrondstart').css({'display':'none','opacity':0});
			startAudioVis();

		});
	}

	var AUDIOid='', selectedAUDIOid='', oldaudioid=0;

	function finUploadAudio(d) {
		AUDIOid=d;

		$audiocarousel.flickity('select', 0);

		$('#audioP1').css({'background':'transparent url(./audio/generated/'+d+'_1-F.png) center -212px/114px  auto no-repeat', filter: 'invert(1)'});
		$('#audioP2').css({'background':'transparent url(./audio/generated/'+d+'_2-F.png) center -212px/114px  auto no-repeat', filter: 'invert(1)'});
		$('#audioP3').css({'background':'transparent url(./audio/generated/'+d+'_3-F.png) center -212px/114px  auto no-repeat', filter: 'invert(1)'});
		$('#audioP4').css({'background':'transparent url(./audio/generated/'+d+'_4-F.png) center -212px/114px  auto no-repeat', filter: 'invert(1)'});

		$('#audioP5').css({'background':'transparent url(./audio/generated/'+d+'_5-F.png) center -212px/114px  auto no-repeat', filter: 'invert(1)'});
		$('#audioP6').css({'background':'transparent url(./audio/generated/'+d+'_6-F.png) center -212px/114px  auto no-repeat', filter: 'invert(1)'});
		$('#audioP7').css({'background':'transparent url(./audio/generated/'+d+'_7-F.png) center -212px/114px  auto no-repeat', filter: 'invert(1)'});

		$("#AScorecollection").css({display:'block',opacity:1});

		$('#pickAudio').css({'opacity':0, 'display':'block'}).transit({opacity:1},250);
		audioP(1);

		setTimeout("$('#boutonSUBMIT').removeClass('hide')",300);

	}


	function audioP(i) {
		if (oldaudioid!=i) {


			$('.icons').removeClass('active');
			$('#audioPP'+i).addClass('active');


			var a=-500,b=1080;

			selectedAUDIOid=AUDIOid+"_"+i;
			contenu='audio/generated/'+selectedAUDIOid+'-F.png';

			$('#epod').transit({'x':a}, 300, 'ease-in', function() {
				$('#epod .epodlayer').css({'background':'transparent url("'+contenu+'") center 235px/178px auto no-repeat', 'filter': 'invert(1)'});

				$(this).css({'x':b, 'display':'block'}).transit({delay:50,'x':290}, 300, 'ease-out', function() {
					//epodchangeOQP=0;
				});
			});


			oldaudioid=i;
		}
	}

	var collection='';

	function changeCollection(d) {
		if (d!=collection) {
			$('#AS'+collection+'collection').transit({opacity:0},200, function() {
				$('#collection_'+collection).removeClass('selected');

				collection=d;

				if (collection=="valentine") {
					$('#backAUDIO, #frontAUDIO').hide();
					audioP(5);
					if (face=='B') {
							face='F';
							$('#backAUDIO').removeClass('select');
							$('#frontAUDIO').addClass('select');
							$('#backepodAUDIO').transit({opacity:0,perspective:'500px',rotateY: '90deg'}, 150, function() {
								$('body').removeClass('back');
								$(this).transit({opacity:1,perspective:'500px',rotateY: '0deg'},250);
							});
						}
				} else {
					$('#backAUDIO, #frontAUDIO').show();
					audioP(1);
				}

				$(this).css('display','none');
				$('#AS'+collection+'collection').css({opacity:0,display:'block'}).transit({opacity:1},200);
				$('#collection_'+collection).addClass('selected');
			})
		}
	}

	$audiocarousel=$('#AScorecollection').flickity({
		cellAlign: 'center',
		contain: true,
		freeScroll: true,
		freeScrollFriction: 0.03,
		prevNextButtons: false,
		pageDots: false,
		selectedAttraction: 0.2,
		friction: 0.30
	});

</script>

<script src="audio/audiolib_epod2.js?<?=$debug?>"></script>

<style>
	#boutons img {
		width: 80px;
		height: 80px;
	}

	#audiosignature h4 {
		text-align: center;
		font-size: 50px;
		font-family: 'TrimWeb';
		font-weight: 900;
		color: #fff;
		line-height: 70px;
		text-transform: uppercase;
		padding: 0;
		margin: 0;
	}

	.boutonrondcount {
		text-align: center;
		font-size: 75px;
		font-family: 'TrimWeb';
		font-weight: 900;
		color: #fff;
		text-transform: uppercase;
		padding: 0;
		margin: 0;
		display: inline-block;
	}

	#AScorecollection {
		margin-top:20px;
	}
</style>
