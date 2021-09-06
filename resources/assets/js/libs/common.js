$(document).ready(function () {
    // mobile navigation 
    $('.nav-icon').click(function () {
        $(this).toggleClass('open');
        $(this).children('#nav-icon').toggleClass('open');
        $('#menu').toggleClass('active');
    });
    $('#menu li span').click(function () {
        $(this).parent('li').children('ul').toggleClass('active');
    });

    $('#usr-ref-link').click(function () {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($('#usr-ref-link').html()).select();
        document.execCommand("copy");
        $temp.remove();
    });
});