<!DOCTYPE HTML>
<html>
	<head>
		<title>webcam</title>
	</head>
	<body>
		<video id="video" width="640" height="480" autoplay></video>
		<button id="snap">Snap Photo</button>
		<canvas id="canvas" width="640" height="480"></canvas>
		<script>


			// Put event listeners into place
			window.addEventListener("DOMContentLoaded", function() {
				// Grab elements, create settings, etc.
				var canvas = document.getElementById('canvas');
				var context = canvas.getContext('2d');
				var video = document.getElementById('video');
				var mediaConfig =  { video: true };
				var errBack = function(e) {
					console.log('Erreur', e)
				};
/*
				// Put video listeners into place
				if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)
				{
					navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream)
					{
						video.src = window.URL.createObjectURL(stream);
						video.play();
					});
				}
*/
				if(navigator.getUserMedia)
				{ // Standard
					navigator.getUserMedia(mediaConfig, function(stream) {video.src = stream; video.play();}, errBack);
				}
				else if(navigator.webkitGetUserMedia)
				{ // WebKit-prefixed
					navigator.webkitGetUserMedia(mediaConfig, function(stream){
						video.src = window.webkitURL.createObjectURL(stream);
						video.play();
					}, errBack);
				}
				else if(navigator.mozGetUserMedia)
				{ // Mozilla-prefixed
					navigator.mozGetUserMedia(mediaConfig, function(stream){
						video.src = window.URL.createObjectURL(stream);
						video.play();
					}, errBack);
				}

				// Trigger photo take
				document.getElementById('snap').addEventListener('click', function() {
					context.drawImage(video, 0, 0, 640, 480);
				});
			}, false);
		</script>
	</body>
</html>
