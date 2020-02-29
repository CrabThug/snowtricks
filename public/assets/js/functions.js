function navBar() {
    let x = document.getElementById("navMenu");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}

function scrollToTrick() {
    window.scrollTo(0, document.getElementById('tricks').offsetTop - 60);
}

function showMoreTrick(e) {
    let tricks = $('#tricks').find('.ntrick').length;
    $('#arrowup').toggleClass('w3-hide');
    $.post("show-more-trick",
        {
            start: tricks
        },
        function (data, status) {
            $("#tricks").append(data);
            let n = $("#showMore").attr('n');
            let tricks = $('#tricks').find('.ntrick').length;
            if (n <= tricks) {
                $('#showMore').hide();
            }
        });
}

function pagination() {
    let page = this.event.toElement.id;
    let start = page * 10 - 10;
    $.post(document.location.href + "/pagination",
        {
            start: start
        },
        function (data, status) {
            document.getElementById("comments").innerHTML = data;
        });
}

$(document).ready(function () {
    // definir le nombre a afficher ( pour savoir quand griser bouton )
    var t = $('#mediaSlide').attr('n');
    console.log(t);
    var n = $('#slideN');
    var p = $('#slideP');
    var c = $("#carousel");
    var cI = $('.carouselItem').width() + 16;
    showable();

    $('.carouselBtn').click(function () {
        if (Math.sign($(this).attr('v')) === 1 && $(this).attr('n') < t) {
            c0 = c.scrollLeft();
            c.scrollLeft(c0 + cI);
            n.attr('n', parseInt(n.attr('n')) + 1);
            p.attr('n', parseInt(p.attr('n')) + 1);
        } else if (Math.sign($(this).attr('v')) === -1 && $(this).attr('n') > 1) {
            c0 = c.scrollLeft();
            c.scrollLeft(c0 - cI);
            n.attr('n', n.attr('n') - 1);
            p.attr('n', p.attr('n') - 1);
        }
        carouselBtn();
    });
});

function showable() {
    // definir le nombre affichable
    var countItm = Math.round($('#carousel').width() / $('.carouselItem').width());
    // donner valeur au bouton +/-
    $('#slideP').attr('n', 1);
    $('#slideN').attr('n', countItm);
}

function carouselBtn() {
    var t = $('#mediaSlide').attr('n');
    var n = $('#slideN');
    var p = $('#slideP');
    if (n.attr('n') === t) {
        n.addClass('w3-disabled');
    } else {
        n.removeClass('w3-disabled');
    }
    if (p.attr('n') > 1) {
        p.removeClass('w3-disabled');
    } else {
        p.addClass('w3-disabled');
    }
}
