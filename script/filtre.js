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


    function change_filtre(filtre) {
        if (canvas) {
            context.drawImage(canvas_backup, 0, 0, 600, 450);
            context.drawImage(filtre, 250, 0, 120, 120);
        }
        else {
            context_canvas_upload.drawImage(canvas_backup, 0, 0, 600, 450);
            context_canvas_upload.drawImage(filtre, 250, 0, 120, 120);
        }
    }


    var radios = document.getElementsByName('filtre');
    var image = document.getElementsByName('image_filtre');

    for (var i = 0; i < radios.length; i++) {
        radios[i].addEventListener('click', (function(i) {
            return function () {
                var filtre = new Image();
                filtre.src = image[i].src;
                filtre.onload = change_filtre(filtre);
            };
            })(i));
    }

}, false);
