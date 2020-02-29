// setup an "add a image" link
var $addImageLink = $('<a href="#" class="add_image_link">Ajouter une image</a>');
var $newLinkLi = $('<li></li>').append($addImageLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of images
    var $collectionHolder = $('#trick_images');

    // add the "add a image" anchor and li to the images ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':text').length);

    $addImageLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new image form (see code block below)
        addImageForm($collectionHolder, $newLinkLi);
    });


});

function addImageForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('value');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a image" link li
    var $newFormLi = $('<li></li>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-image"><i class="fa fa-times" aria-hidden="true"></i>\n</a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-image').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}
