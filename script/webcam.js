window.addEventListener("DOMContentLoaded", function() {

    var canvas = document.getElementById('canvas');
    var canvas_upload = document.getElementById('canvas_upload');
    var canvas_backup = document.getElementById('canvas_backup');
    var context_backup = canvas_backup.getContext('2d');
    var video = document.getElementById('video');

    // Tout mes boutons.
    var save_photo = document.getElementById('save_photo');
    var take_photo = document.getElementById('snap');
    var back_webcam = document.getElementById('back_webcam');
    var back_upload = document.getElementById('back_upload');

    // mes filtres.
    var filtres = document.getElementById('filtre');

    // Champs formulaires.
    var name = document.getElementById('name');
    var description = document.getElementById('description');

    if (canvas)
    {
        var context = canvas.getContext('2d');
        canvas_backup.width = canvas.width;
        canvas_backup.height = canvas.height;
    }

    else if (canvas_upload) {
        var context_canvas_upload = canvas_upload.getContext('2d');
        canvas_backup.width = canvas_upload.width;
        canvas_backup.height = canvas_upload.height;
    }


    // Partie config WEBCAM avec le flux video et fonction en cas d'erreur.
    var mediaConfig = {video: true};
    var error_webcam = function(error) {
        console.log('Webcam non disponible, afficher l\'upload', error);
        if (!canvas_upload)// si on est pas sur la partie 2 de l'upload (save et back to webcam)
        {
            video.style.display = "none";
            take_photo.style.display = "none";
            document.getElementById('upload_form').style.display = "block";

        }
    };

    // Recuperation de la webcam.
    if(navigator.getUserMedia)
    { // Standard
        navigator.getUserMedia(mediaConfig, function(stream) {
            video.src = window.URL.createObjectURL(stream);
             video.play();
         }, error_webcam);
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

    if (canvas_upload)// Dans le cas ou l'upload a ete fait.
    {
        filtres.style.display = "block";
        save_photo.style.display = "block";
        back_upload.style.display = "block";
        name.style.display = "block";
        description.style.display = "block";

        var context_canvas_upload = canvas_upload.getContext('2d');
        var img_upload = new Image();
        img_upload.src =  "upload/image";
        img_upload.onload = function(){
            context_canvas_upload.drawImage(img_upload, 0, 0, 600, 450);
            context_backup.drawImage(img_upload, 0, 0, 600, 450);
        }

    }


    // Prendre la photo.
    if (take_photo)
    {
        take_photo.addEventListener('click', function() {
            // On remplace la webcam par le canvas avec la photo prise.
            var context = canvas.getContext('2d');
            var context_backup = canvas_backup.getContext('2d');

    /*
            var filtre = new Image();
            filtre.src = "hatvert.png";

            filtre.onload = function () {context.drawImage(filtre, 280, 0, 150, 150);}
    */
            context.drawImage(video, 0, 0, 600, 450);
            context_backup.drawImage(video, 0, 0, 600, 450);
            filtres.style.display = "block";
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
    }

/*
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
*/
    save_photo.addEventListener('click', function() {
        if (!canvas_upload)
        {
            var img = canvas.toDataURL();
            var output=img.replace(/^data:image\/(png|jpg);base64,/, "");
            document.getElementById('hidden').value = output;
        }
        else {
            var img = canvas_upload.toDataURL();
            var output=img.replace(/^data:image\/(png|jpg);base64,/, "");
            document.getElementById('hidden').value = output;
        }
    });

}, false);
