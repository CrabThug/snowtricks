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
    $.post("show-more-trick",
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
    let start = page * 10 - 10;
    $.post(document.location.href + "/pagination",
        {
            start: start
        },
        function (data, status) {
            document.getElementById("comments").innerHTML = data;
        });
}

// setup an "add a tag" link
var $addImageLink = $('<a href="#" class="add_image_link w3-button w3-blue w3-round"">Ajouter une Image</a>');
var $newLinkLi = $('<li class="w3-col s12"></li>').append($addImageLink);

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('ul.images');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addImageLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addImageForm($collectionHolder, $newLinkLi);
    });
});

function addImageForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $(
        '<div class="w3-col s3 w3-padding w3-display-container w3-border w3-margin"></div>'
    ).append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-image w3-display-topright"><i class="fa fa-times w3-text-red w3-padding" aria-hidden="true"></i></a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-image').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

// setup an "add a tag" link
var $addMovieLink = $('<a href="#" class="add_movie_link w3-button w3-blue w3-round">Ajouter une video</a>');
var $newMovieLinkLi = $('<li class="w3-col s12"></li>').append($addMovieLink);

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    var $collectionMovies = $('ul.movies');

    // add the "add a tag" anchor and li to the tags ul
    $collectionMovies.append($newMovieLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionMovies.data('index', $collectionMovies.find(':input').length);

    $addMovieLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addMovieForm($collectionMovies, $newMovieLinkLi);
    });


});

function addMovieForm($collectionMovies, $newMovieLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionMovies.data('prototype');

    // get the new index
    var index = $collectionMovies.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionMovies.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $(
        '<div class="w3-col s3 w3-padding w3-display-container w3-border w3-margin"></div>'
    ).append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-image w3-display-topright"><i class="fa fa-times w3-text-red w3-padding" aria-hidden="true"></i></a>');

    $newMovieLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-movie').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

