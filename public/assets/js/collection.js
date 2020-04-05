// setup an "add a image" link
var $addImageLink = $('<a href="#" class="add_image_link w3-hide">Ajouter une image</a>');
var $newLinkLi = $('<div></div>').append($addImageLink);
var media = 0;

jQuery(document).ready(function () {
    // Get the ul that holds the collection of images
    var $collectionHolder = $('#trick_images');

    // add the "add a image" anchor and li to the images ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':text').length);

    $('.add_image_link').on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new image form (see code block below)
        addImageForm($collectionHolder, $newLinkLi);

        showImageName();
    });

    var $collectionHolderMov = $('#trick_movies');

    $collectionHolderMov.data('index', $collectionHolderMov.find(':text').length);

    $('.add_movie_link').on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        // add a new image form (see code block below)
        addMovieForm($collectionHolderMov);
    });
});

function addImageForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');
    var value = $collectionHolder.data('value');
    if (value) {
        var newForm = prototype.replace(/__name__/g, value);
        // increase the index with one for the next item
        $collectionHolder.data('value', value + 1);
    } else {
        var newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
    }
    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have

    // Display the form in the page in an li, before the "Add a image" link li
    var $newFormLi = $('<div class="w3-display-container w3-light-gray w3-col s12 m4 w3-padding w3-border formLi"></div>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-image w3-display-topright w3-padding"><i class="fa fa-times" aria-hidden="true"></i>\n</a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-image').click(function (e) {
        e.preventDefault();

        if (isChecked(this.parentElement)) {
            $('#cover').remove();
        }

        $(this).parent().remove();

        return false;
    });
}

function addMovieForm($collectionHolder) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');
    var value = $collectionHolder.data('value');
    if (value) {
        var newForm = prototype.replace(/__name__/g, value);
        // increase the index with one for the next item
        $collectionHolder.data('value', value + 1);
    } else {
        var newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
    }
    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have

    // Display the form in the page in an li, before the "Add a image" link li
    var $newFormLi = $('<div class="w3-display-container w3-light-gray w3-col s12 m4 w3-padding w3-border formLi"></div>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-movie w3-display-topright w3-padding"><i class="fa fa-times" aria-hidden="true"></i>\n</a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-movie').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

function showImageName() {
    $('.custom-file-input').on('change', function (event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });
}
