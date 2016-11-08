window.addEventListener('load', function() {

    var carroussel = document.getElementById('carroussel');
    var images = document.getElementsByClassName('carroussel_photos');
    var nb_image = images.length;
    var prev = document.getElementById('previous');
    var next = document.getElementById('next');
    var last_index = nb_image - 1;
    var current_index = 0;
    var opacity = 10;

    //mise en place du style des images.
    for (var i = 0; i < images.length; i++) {
        console.log(images[i].src);
        images[i].style.opacity = 0;
    }
    images[0].style.opacity = 1;


    function carroussel_slide() {
        setTimeout(function() {
            images[current_index].style.display = "none";
            current_index++;
            if (current_index == nb_image) {
                current_index = 0;
            }
            images[current_index].style.display = "block";
            carroussel_slide();
        }, 5000);
    }

    prev.addEventListener('click', function() {
        clearInterval(prev);
        last_index = current_index;
        current_index--;
        if (current_index < 0) {
            current_index = nb_image - 1;
        }
        prev = setInterval(frame_prev, 100);
        function frame_prev() {
            if (opacity == 0) {//quand c'est fini.
                opacity = 10;
                clearInterval(prev);
            }
            else {//en cours d'animation.
                opacity -= 1;
                images[last_index].style.opacity = opacity / 10;
                images[current_index].style.opacity = 1 - (opacity / 10);
            }
        }
        console.log(current_index);
    });

    next.addEventListener('click', function() {
        clearInterval(next);
        last_index = current_index;
        current_index++;
        if (current_index == nb_image) {
            current_index = 0;
        }
        images[current_index].style.display = "block";
        next = setInterval(frame_next, 100);
        function frame_next() {
            if (opacity == 0) {//quand c'est fini.
                opacity = 10;
                clearInterval(next);
            }
            else {//en cours d'animation.
                opacity -= 1;
                images[last_index].style.opacity = opacity / 10;
                images[current_index].style.opacity = 1 - (opacity / 10);
                console.log("last : " + images[last_index].style.opacity);
                console.log("new : " + images[current_index].style.opacity);
            }
        }
    });

    //carroussel_slide();

}, false);
