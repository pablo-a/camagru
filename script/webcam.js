window.addEventListener("DOMContentLoaded", function() {

    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video');
    // Tout mes boutons.
    var save_photo = document.getElementById('save_photo');
    var take_photo = document.getElementById('snap');
    var back_webcam = document.getElementById('back_webcam');

    var mediaConfig =  { video: true };
    var error_webcam = function(error) {
        console.log('Erreur afficher l\'upload', error);
    };

    if(navigator.getUserMedia)
    { // Standard
        navigator.getUserMedia(mediaConfig, function(stream) {video.src = stream; video.play();}, error_webcam);
    }
    else if(navigator.webkitGetUserMedia)
    { // WebKit-prefixed
        navigator.webkitGetUserMedia(mediaConfig, function(stream){
            video.src = window.URL.createObjectURL(stream);
            video.play();
        }, error_webcam);
    }
    else if(navigator.mozGetUserMedia)
    { // Mozilla-prefixed
        navigator.mozGetUserMedia(mediaConfig, function(stream){
            video.src = window.URL.createObjectURL(stream);
            video.play();
        }, error_webcam);
    }

        // Prendre la photo.
    take_photo.addEventListener('click', function() {
        // On remplace la webcam par le canvas avec la photo prise.
        context.drawImage(video, 0, 0, 600, 450);
        canvas.style.display = "block";
        video.style.display = "none";
        //On affiche les boutons correspondant.
        save_photo.style.display = "block";
        back_webcam.style.display = "block";
        take_photo.style.display = "none";
    });

    back_webcam.addEventListener('click', function() {
        canvas.style.display = "none";
        video.style.display = "block";
        save_photo.style.display = "none";
        back_webcam.style.display = "none";
        take_photo.style.display = "block";
    });

    save_photo.addEventListener('click', function() {
        var img = canvas.toDataURL();
        document.getElementById('cam').src = img;
    });
}, false);
