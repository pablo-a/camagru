window.addEventListener("DOMContentLoaded", function() {

    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video');
    // Tout mes boutons.
    var save_photo = document.getElementById('save_photo');
    var take_photo = document.getElementById('snap');
    var back_webcam = document.getElementById('back_webcam');
    var name = document.getElementById('name');
    var description = document.getElementById('description');

    var mediaConfig =  { video: true };
    var error_webcam = function(error) {
        console.log('Erreur afficher l\'upload', error);
        //document.getElementById('upload').display = "block";
        video.style.display = "none";
        take_photo.style.display = "none";
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
        // les champs de formulaires.
        name.style.display = "block";
        description.style.display = "block";
    });

    back_webcam.addEventListener('click', function() {
        // on remet la webcam et on fait disparaitre le canvas.
        canvas.style.display = "none";
        video.style.display = "block";
        // on affiche les bons boutons.
        save_photo.style.display = "none";
        back_webcam.style.display = "none";
        take_photo.style.display = "block";
        //les champs de formulaire.
        name.style.display = "none";
        description.style.display = "none";
    });

    save_photo.addEventListener('click', function() {
        var img = canvas.toDataURL();
        var output=img.replace(/^data:image\/(png|jpg);base64,/, "");
        document.getElementById('hidden').value = output;
    });

    if(window.location.href.indexOf("upload") > -1) {
       alert("your url contains the name franky");
    }
/*
    document.getElementById('submit_upload').addEventListener('click', function() {
        var image_uploaded = NewImage();
        image_uploaded.src = 'upload/photo20161031111152';
        context.drawImage(image_uploaded, 0, 0, 600, 450);
        canvas.style.display = "block";
        video.style.display = "none";
    });
*/
}, false);
