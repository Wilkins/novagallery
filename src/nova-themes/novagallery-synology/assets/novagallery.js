document.addEventListener('DOMContentLoaded', function () {
    var $gallery = new SimpleLightbox('.gallery a', {showCounter: false});
});

$('.favorite-clickable').click(function () {
    star = $(this);
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'GET',
        dataType: 'json',
    })
        .done(function (msg) {
            $("i", star).toggleClass("icon-favorite-off icon-favorite-on");
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            $("i", star).toggleClass("icon-favorite-off icon-favorite-error");
        });

});
$('.cover-clickable').click(function () {
    star = $(this);
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'GET',
        dataType: 'json',
    })
        .done(function (msg) {
            $("i", star).toggleClass("icon-cover-off icon-cover-on");
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            $("i", star).toggleClass("icon-cover-off icon-cover-error");
        });

});
$('.trash-clickable').click(function () {
    star = $(this);
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'GET',
        dataType: 'json',
    })
        .done(function (msg) {
            console.log($(star).closest('.element'));
            $(star).closest('.element').remove();
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            $("i", star).toggleClass("icon-trash-off icon-trash-error");
        });

});