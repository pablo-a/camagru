window.addEventListener("DOMContentLoaded", function() {
    // recuperation des canvas.
    var canvas = document.getElementById('canvas');
    var canvas_upload = document.getElementById('canvas_upload');
    var canvas_backup = document.getElementById('canvas_backup');

    if (canvas)
    {
        var context = canvas.getContext('2d');
    }

    else if (canvas_upload) {
        var context_canvas_upload = canvas_upload.getContext('2d');
    }

    // creation canvas de backup (photo de base.)
    /*
    var image_backup = new Image();
    if (canvas) {
        image_backup.src = canvas.toDataURL("image/png");
    }
    else if (canvas_upload) {
        image_backup.src = canvas_upload.toDataURL("image/png");
    }


    function restore_image(ctx) {
        //restore the original image
        ctx.drawImage(image_backup, 0, 0);
    }
*/
    //recuperation des filtres.
    //var f1 = document.getElementById('f1');
    //var f2 = document.getElementById('f2');
    //var f3 = document.getElementById('f3');


    function change_filtre(filtre) {
        if (canvas) {
            canvas.drawImage(canvas_backup, 0, 0, 600, 450);
            canvas.drawImage(filtre, 250, 0, 120, 120);
        }
        else {
            canvas_upload.drawImage(canvas_backup, 0, 0, 600, 450);
            canvas_upload.drawImage(filtre, 250, 0, 120, 120);
        }
    }


    var radios = document.getElementsByName('filtre');
    var image = document.getElementsByName('image_filtre');
    for (var i = 0; i < image.length; i++) {
        console.log(image[i].src);
    }



    for (var i = 0; i < radios.length; i++) {
        radios[i].addEventListener('click', function() {
            //var selector_img = 'img[alt=f' + radios[i].id + ']';
            //console.log('img[alt=f' + radios[i].id + ']');
            //var image_filtre = document.querySelector(selector_img);
            /*
            for (var i = 0; i < image.length; i++) {
                console.log(image[i].alt);
            }
            */
            var filtre = new Image();
            filtre.src = image[1].src;
            filtre.onload = change_filtre(filtre);

        })
    }


/*
    if (f1.style.display != "none")
        f1.addEventListener('click', function() {
            var filtre = new Image();
            filtre.src = "hatvert.png";
            filtre.onload = change_filtre(filtre);
        });
    }

    if (f2.style.display != "none")
    {
        f2.addEventListener('click', function() {
            var filtre = new Image();
            filtre.src = "hatvert.png";
            filtre.onload = change_filtre(filtre);
        });
    }

    if (f3.style.display != "none")
    {
        f3.addEventListener('click', function() {
            var filtre = new Image();
            filtre.src = "hatvert.png";
            filtre.onload = change_filtre(filtre);
        });
    }
*/
}, false);
