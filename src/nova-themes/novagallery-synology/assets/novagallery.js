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