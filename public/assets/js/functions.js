function navBar() {
    let x = document.getElementById("navMenu");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}

function currentDiv(n) {
    showDivs(slideIndex = n);
}

function showDivs(n) {
    let i;
    let x = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("demo");
    if (n > x.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = x.length
    }
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" w3-opacity-off", "");
    }
    x[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " w3-opacity-off";
}

function scrollToTrick() {
    window.scrollTo(0, document.getElementById('tricks').offsetTop - 60);
}

function showMoreTrick() {

    let tricks = $('#tricks').find('.w3-third').length;
    $('#arrowup').toggleClass('w3-hide');
    $.post("showMoreTrick",
        {
            start: tricks
        },
        function (data, status) {
            $("#tricks").append(data);
            let n = $('#showMore').attr('n');
            let tricks = $('#tricks').find('.w3-third').length;
            if (n <= tricks) {
                $('#showMore').hide();
            }
        });
}

function pagination() {
    let page = this.event.toElement.id;
    let start = page * 10 - 9;
    $.post(document.location.href + "/pagination",
        {
            start: start
        },
        function (data, status) {
            document.getElementById("comments").innerHTML = data;
        });
}
