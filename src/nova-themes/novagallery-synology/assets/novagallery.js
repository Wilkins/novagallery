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
            let remainingElements = $(star).closest('.gallery').children().length - 1;
            $(star).closest('.element').remove();
            if (remainingElements === 0) {
                document.location.reload();
            }
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            $("i", star).toggleClass("icon-trash-off icon-trash-error");
        });

});
$('.rotateleft-clickable').click(function () {
    star = $(this);
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'GET',
        dataType: 'json',
    })
        .done(function (msg) {
            document.location.reload();
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            $("i", star).toggleClass("icon-rotateleft-off icon-rotateleft-error");
        });
});
$('.rotateright-clickable').click(function () {
    star = $(this);
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'GET',
        dataType: 'json',
    })
        .done(function (msg) {
            //console.log($(star).closest('.element'));
            //$(star).closest('.element').remove();
            document.location.reload();
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            $("i", star).toggleClass("icon-rotateright-off icon-rotateright-error");
        });

});
$('.download-clickable').click(function () {
    window.open($(this).attr('data-url'), '_blank');
});
$('.deletealbum').click(function () {
    button = $(this);
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'GET',
        dataType: 'json',
    })
    .done(function (msg) {
        $("a.link-back")[0].click();
    })
    .fail(function (msg) {
        console.log('fail');
        console.log(msg);
        alert(msg);
    });
});

$('.moveto-clickable').click(function () {
    star = $(this);
    $.ajax({
        url: $(this).attr('data-url'),
        type: 'GET',
        dataType: 'json',
    })
        .done(function (msg) {
            let remainingElements = $(star).closest('.gallery').children().length - 1;
            $(star).closest('.element').remove();
            if (remainingElements === 0) {
                document.location.reload();
            }
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            alert('Fail : '+msg);
            //$("i", star).toggleClass("icon-trash-off icon-trash-error");
        });

});

$('.rename-folder').click(function (){
    let newName = prompt("Nouveau nom :", $(this).attr('data-name'));
    $.ajax({
        url: $(this).attr('data-url')+'?newName='+newName,
        type: 'GET',
        dataType: 'json',
    })
        .done(function (msg) {
            document.location.reload();
        })
        .fail(function (msg) {
            console.log('fail');
            console.log(msg);
            alert('Fail : '+msg);
        });

});
