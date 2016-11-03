window.addEventListener("load", function() {
    // recuperation des canvas.
    var canvas = document.getElementById('canvas');
    var canvas_upload = document.getElementById('canvas_upload');
    if (canvas)
    {
        var context = canvas.getContext('2d');
    }

    else if (canvas_upload) {
        var context_canvas_upload = canvas_upload.getContext('2d');
    }

    // creation canvas de backup (photo de base.)
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

    //recuperation des filtres.
    var f1 = document.getElementById('f1');
    var f2 = document.getElementById('f2');
    var f3 = document.getElementById('f3');


    if (f1.style.display != "none")
    {
        f1.addEventListener('click', function() {
            var filtre = new Image();
            filtre.src = "hatvert.png";

            if (canvas)
            {
                restore_image(context);
/*
                filtre.onload = function () {
                    context.drawImage(filtre, 280, 0, 150, 150);
                }
                */
            }
            else if (canvas_upload)
            {
                filtre.onload = function () {
                    context_canvas_upload.drawImage(context_canvas_copy, 0, 0);
                    context_canvas_upload.drawImage(filtre, 280, 0, 150, 150);
                }
            }
        });
    }

    if (f2.style.display != "none")
    {
        f2.addEventListener('click', function() {
            var filtre = new Image();
            filtre.src = "filtre/clementine.png";

            if (canvas)
            {
                filtre.onload = function () {
                    context.drawImage(filtre, 280, 0, 150, 150);
                }
            }
            else if (canvas_upload)
            {
                filtre.onload = function () {context_canvas_upload.drawImage(filtre, 280, 0, 150, 150);}
            }
        });
    }

    if (f3.style.display != "none")
    {
        f3.addEventListener('click', function() {
            var filtre = new Image();
            filtre.src = "filtre/banana.png";

            if (canvas) {
                filtre.onload = function () {
                    context.drawImage(filtre, 280, 0, 150, 150);
                }
            }
            else if (canvas_upload) {
                filtre.onload = function () {context_canvas_upload.drawImage(filtre, 280, 0, 150, 150);}
            }
        });
    }


}, false);
