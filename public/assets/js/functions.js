// Declaration
let flashId = 0;

function scrollToTrick() {
    window.scrollTo(0, document.getElementById('tricks').offsetTop - 60);
}

function showMoreTrick(n) {
    let tricks = $('#tricks').find('.ntrick').length;
    $('#arrowup').toggleClass('w3-hide');
    $.post("show-more-trick",
        {
            start: tricks
        },
        function (data, status) {
            $("#tricks").append(data);
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
    // defini le nombre a afficher ( pour savoir quand griser bouton )
    var t = $('#mediaSlide').attr('n');
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
    makeMainImg();
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

function deleteMedia(e) {
    let id = $(e).attr('id');
    let token = $('#tokenDel' + id).attr('value');
    let url = $(e).attr('link');
    let media = $(e).attr('media');
    $.ajax({
        url: url,
        type: 'DELETE',
        data: {
            _token: token
        },
        success: function (result) {
            if (result.search(".w3-pale-red") === -1) {
                if ($('#' + id).css('display') === 'none') {
                    idImg = $('.carouselItem img').parent().find('[token]').attr('id');
                    $('#cover').attr('src', $('#image-' + idImg).attr('src'));
                }
                $('#' + media).remove();
                carouselToogle();
                makeMainImg();
            }
            flash(result);
        },
    });
}

function deleteTrick(e) {
    let id = $(e).attr('id');
    let token = $('#tokenTrick' + id).attr('value');
    let url = $(e).attr('link');
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'html',
        data: {
            _token: token
        },
        success: function (result, status) {
            $('#trick' + id).remove();
            flash(result);
        }
    });
}

function mainImg(e) {
    let id = $(e).attr('id');
    let token = $(e).attr('token');
    let url = $(e).attr('link');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'html',
        data: {
            _token: token
        },
        success: function (result, status) {
            $('#cover').attr('src', $('#image-' + id).attr('src'));
            flash(result);
            makeMainImg();
        }
    });
}

function flash(value) {
    flashId += 1;
    $('#flash-container').append(value);
    $(".flash").last().attr('id', 'flash' + flashId);
    flashDelete(flashId);
}

function flashDelete(flashId) {
    setTimeout(function () {
        $('#flash' + flashId).remove();
    }, 5000);
}

function titleTag(title) {
    tag = '<tag class="w3-display-middle w3-black w3-tag w3-round padding-lr w3-xxlarge"></tag>';
    if (!$('tag').length) {
        $('.div-image').append(tag);
    }
    $('tag').text($(title).val());
}

function check(e) {
    var value = false;
    if (e.checked) {
        var value = true;
    }
    $("input:checkbox").each(function (index) {
        this.checked = false;
    });
    e.checked = value;

    input = e.parentElement.parentElement.lastChild.firstChild;
    if (input.value && e.checked) {
        return mainShow(input);
    }
    if (input.value && !e.checked) {
        idImg = $('.carouselItem img').parent().find('[token]').attr('id');
        $('#cover').attr('src', $('#image-' + idImg).attr('src'));
    }
}

function isChecked(value) {
    return $(value).find('.w3-check')[0].checked;
}

function mainShow(input) {
    image = '<img id="cover" class="image w3-image" src="" alt="image principale">';
    if (!$('#cover').length) {
        $('.div-image').append(image);
    }
    element = input.parentElement.parentElement;

    if ($('#cover').attr('src') === "") {
        $(element).find('.w3-check')[0].checked = true;
    }

    if (isChecked(element)) {
        readURL(input);
    }
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cover').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function upArea(textarea) {
    $("*").click(function () {
        if ($(this).not($(textarea))) {
            return $(textarea).height('44px');
        }
    });
}

function makeMainImg() {
    c = $('#cover').attr('src');
    $('.carouselItem img').each(function () {
        i = $(this).attr('src');
        if (c === i) {
            return $(this).parent().find('[token]').hide();
        }
        $(this).parent().find('[token]').show();
    });
}

function carouselToogle() {
    var carousel = $('.carouselItem');
    var countItm = Math.round($('#carousel').width() / carousel.width());
    var xmedia = carousel.length;
    if (xmedia <= countItm) {
        $('#mediaSlide').hide();
    } else {
        $('#mediaSlide').show();
    }
}

function carouselbtnToogle() {
    $('#carousel').toggleClass('w3-hide-small');
}
