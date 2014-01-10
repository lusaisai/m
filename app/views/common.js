$('button').focus(function () {
    that = this;
    setTimeout( function () {
        $(that).blur();
    }, 600 );
});
