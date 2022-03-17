$(document).ready(function () {

    // set the image-map width and height to match the img size
    $('#image-map').css({
        'width': $('#image-map img').width(),
        'height': $('#image-map img').height()
    })

    //infobulle direction
    var infobulleDirection;

    for (i = 0; i < $(".pin").length; i++) {
        // set infobulle direction type - up or down             
        if ($(".pin").eq(i).hasClass('pin-down')) {
            infobulleDirection = 'infobulle-down';
        } else if ($(".pin").eq(i).hasClass('pin-right')) {
            infobulleDirection = 'infobulle-right';
        }
        else {
            infobulleDirection = 'infobulle-up';
        }

        // append the infobulle
        $("#image-map").append("<div style='left:" + $(".pin").eq(i).data('xpos') + "px;top:" + $(".pin").eq(i).data('ypos') + "px' class='" + infobulleDirection + "'>\
                                            <div class='infobulle'>" + $(".pin").eq(i).html() + "</div>\
                                    </div>");
    }

    // show/hide the infobulle
    $('.infobulle-up, .infobulle-down, .infobulle-right').mouseenter(function () {
        $(this).children('.infobulle').fadeIn(100);
    }).mouseleave(function () {
        $(this).children('.infobulle').fadeOut(100);
    })
});