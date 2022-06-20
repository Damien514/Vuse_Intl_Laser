var canvas = document.getElementById("can");
var canvasCtx = canvas.getContext('2d');

var leftchannel = [];
var recorder = null;
var recordingLength = 0;
var volume = null;
var mediaStream = null;
var sampleRate = 44100;
var context = null;
var soundBlob = null;
var track;

var HEIGHT=100;
var WIDTH=400;

var unix = 0;
var audiostopped=0;

var dataArray, bufferLength, drawVisual=0, increment=0;

	  function startAudioVis() {
		// Initialize recorder

		dataArray=[];
		soundBlob=null;
		context = null;
		leftchannel=[];
		mediaStream=null;
		recorder=null;
		audiostopped=0;
		recordingLength=0;

		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
		navigator.mediaDevices.getUserMedia(
		{
	   	   audio: {
		   	   echoCancellation: true,
		   	   noiseSuppression: true
		   	   }
		}).then(
		function (e) {
	   	   	console.log("user consent");
			track = e.getTracks()[0];

			setTimeout(function() {
				$('.boutonrondcount').css({'opacity':.5, 'scale':2 }).html('3').transit({opacity:1,scale:1},350);
			}, 1000);

			setTimeout(function() {
				$('.boutonrondcount').css({opacity:.5,scale:2}).html('2').transit({opacity:1,scale:1},350);
			}, 2000);

			setTimeout(function() {
				$('.boutonrondcount').css({opacity:.5,scale:2}).html('1').transit({opacity:1,scale:1},350);
			}, 3000);

			setTimeout(function() {
				$('.boutonrondcount').css({opacity:.5,scale:2}).html('0').transit({opacity:1,scale:1},350);
			}, 4000);



	   	    setTimeout(function() {
		   	    $('h4.ready').hide();
		   	    $('#can').css({'opacity':1, display:'inline-block'});
		   	    $('h4.recording').show();
		   	    audiostopped=0;

		   	   // creates the audio context
		   	   window.AudioContext = window.AudioContext || window.webkitAudioContext;
		   	   context = new AudioContext();

		   	   // creates an audio node from the microphone incoming stream
		   	   mediaStream = context.createMediaStreamSource(e);

		   	   // https://developer.mozilla.org/en-US/docs/Web/API/AudioContext/createScriptProcessor
		   	   // bufferSize: the onaudioprocess event is called when the buffer is full
		   	   var bufferSize = 2048;
		   	   bufferLength=bufferSize;
		   	   increment=bufferLength/WIDTH;

		   	   var numberOfInputChannels = 2;
		   	   var numberOfOutputChannels = 2;
		   	   if (context.createScriptProcessor) {
		   		recorder = context.createScriptProcessor(bufferSize, numberOfInputChannels, numberOfOutputChannels);
		   	   } else {
		   		recorder = context.createJavaScriptNode(bufferSize, numberOfInputChannels, numberOfOutputChannels);
		   	   }

		   	   recorder.onaudioprocess = function (e) {

			   	dataArray = new Float32Array(e.inputBuffer.getChannelData(0));

		   		leftchannel.push(dataArray);
		   		recordingLength += bufferSize;
		   		if (drawVisual==0) draw();

		   	   }


	   	   		$('.boutonrondcount').css({'display':'none'});
				$('.boutonrondstop').css({'display':'inline-block','opacity':1});
				unix = +new Date();

				// we connect the recorder
				mediaStream.connect(recorder);
				recorder.connect(context.destination);
			}, 5000);




		},
	   	   	   function (e) {
	   	   		console.error(e);
	   	   	   });
	   }

	  function stopAudio() {

	  	if (audiostopped==0) {
		  	audiostopped=1;
			// stop recording
			recorder.disconnect(context.destination);
			mediaStream.disconnect(recorder);

			track.stop();

			$('.boutonrondstop').css({'display':'none','opacity':0});

			$('#recordAudio').transit({'opacity':0},250, function() {
				cancelAnimationFrame(drawVisual);
				$('#can, h4.recording').css({'display':'none', 'opacity': 0});
				$('#recordAudio').hide().css('opacity',1);
				drawVisual=0;
				$('#processiongAudio').css({'opacity':0, scale:'.7',display:'block'}).transit({'opacity':1, scale:1},850);
			});


			var leftBuffer = flattenArray(leftchannel, recordingLength);

			// we create our wav file
			var buffer = new ArrayBuffer(44 + leftBuffer.length * 2);
			var view = new DataView(buffer);

			// RIFF chunk descriptor
			writeUTFBytes(view, 0, 'RIFF');
			view.setUint32(4, 44 + leftBuffer.length * 2, true);
			writeUTFBytes(view, 8, 'WAVE');
			// FMT sub-chunk
			writeUTFBytes(view, 12, 'fmt ');
			view.setUint32(16, 16, true); // chunkSize
			view.setUint16(20, 1, true); // wFormatTag
			view.setUint16(22, 1, true); // wChannels: stereo (2 channels)
			view.setUint32(24, sampleRate, true); // dwSamplesPerSec
			view.setUint32(28, sampleRate * 2, true); // dwAvgBytesPerSec
			view.setUint16(32, 4, true); // wBlockAlign
			view.setUint16(34, 16, true); // wBitsPerSample

			writeUTFBytes(view, 36, 'data');
			view.setUint32(40, leftBuffer.length * 2, true);

			var index = 44;
			var volume = 1;
			for (var i = 0; i < leftBuffer.length; i++) {
		   	   view.setInt16(index, leftBuffer[i] * (0x7FFF * volume), true);
		   	   index += 2;
			}

			// our final blob
			soundBlob = new Blob([view], { type: 'audio/wav' });

			saveAudio();
		}
	   }


	function saveAudio() {
		if (soundBlob == null) return;
		else {

			var filename = "test.wav";
			var data = new FormData();
			data.append('file', soundBlob);

			$.ajax({
				type: 'POST',
				url: './audio/generate.wave.epod2.php',
				data: data,
				processData: false,
				cache: false,
				contentType: false
			}).done(function(data) {
				finUploadAudio(data);

				dataArray=[];
				soundBlob=null;
				context = null;
				leftchannel= [];
				//rightchannel=[];
				mediaStream=null;
				recorder=null;

			});
		}
	   }

	   function flattenArray(channelBuffer, recordingLength) {
		var result = new Float32Array(recordingLength);
		var offset = 0;
		for (var i = 0; i < channelBuffer.length; i++) {
	   	   var buffer = channelBuffer[i];
	   	   result.set(buffer, offset);
	   	   offset += buffer.length;
		}
		return result;
	   }


	   function writeUTFBytes(view, offset, string) {
		for (var i = 0; i < string.length; i++) {
	   	   view.setUint8(offset + i, string.charCodeAt(i));
		}
	   }

var compteurSinus=0;
function draw() {

	compteurSinus+=.13;

	encoursTimer=+new Date()-unix;

	canvasCtx.fillStyle = 'rgb(0,0,0)';
	canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);

	var k=0;
	if (encoursTimer>4000) k=Math.floor(255/1000*(encoursTimer-4000));

	canvasCtx.fillStyle = 'rgb(255,'+(255-k)+','+(255-k)+')';
	canvasCtx.fillRect(0, 49, WIDTH/5000*encoursTimer, 3);

	if (k>0) canvasCtx.fillStyle = 'rgb(255,255,255)';
	canvasCtx.fillRect(0, 45, 1, 11);
	canvasCtx.fillRect(80, 45, 1, 11);
	canvasCtx.fillRect(160, 45, 1, 11);
	canvasCtx.fillRect(240, 45, 1, 11);
	canvasCtx.fillRect(320, 45, 1, 11);
	canvasCtx.fillRect(399, 45, 1, 11);

	var x=0,y=0,m=compteurSinus,incO=0,c=0,m2=compteurSinus/2;

	for(var i=0; i<bufferLength; i+=increment) {
		y=Math.round(HEIGHT*Math.abs(dataArray[Math.round(i)]));
		var h=Math.round(Math.cos(m2)*10)+20;

		incO=Math.round(Math.sin(m)*h);
		m+=.091;
		m2+=.016;

		canvasCtx.fillStyle = 'rgb(190,190,190)';
		canvasCtx.fillRect(x, incO+50,1, (h-18)/2);

		if (y>3) {
			canvasCtx.fillStyle = 'rgb(255,255,255)';
			canvasCtx.fillRect(x, HEIGHT-y,1, y);
		}
		x++;
	}

	if (encoursTimer>=5000) stopAudio();

	drawVisual = requestAnimationFrame(draw);
}
