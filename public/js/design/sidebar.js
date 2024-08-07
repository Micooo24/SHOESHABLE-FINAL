$(document).ready(function() {
    var pathname = window.location.pathname;
    $('.sidebar li a').removeClass('active');
    $('.sidebar li a').each(function() {
        var href = $(this).attr('href');
        if (pathname === href) {
            $(this).addClass('active');
            return false;
        }
    });
});
