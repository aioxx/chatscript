var slideshow_interval = null;
var slideshow_timeout = 4000;
var baseurl = site_url = $.trim($('base').attr('href'));
var api_request_url = site_url+'api_request/';
var user_csrf_token = null;
var user_login_session_id = WebStorage('get', 'login_session_id');
var user_access_code = WebStorage('get', 'access_code');
var user_session_time_stamp = WebStorage('get', 'session_time_stamp');

if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
    $('body').addClass('d-none');
}


function isLocalStorageAvailable() {
    var test = 'test';
    try {
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch(e) {
        return false;
    }
}

$('.refresh_page').on('click', function() {
    location.reload(true);
});

function WebStorage(todo, name, value) {

    if (isLocalStorageAvailable() && typeof(Storage) !== "undefined") {
        if (todo == 'get') {
            value = localStorage.getItem(name);
            if (value) {
                return value;
            } else {
                return null;
            }
        } else if (todo == 'set') {
            localStorage.setItem(name, value);
        } else if (todo == 'remove') {
            localStorage.removeItem(name);
        } else if (todo == 'clear') {
            localStorage.clear();
        }
    } else {
        console.log('No Web Storage Support');
        return null;
    }
}

function isJSON (data) {
    var IS_JSON = true;
    try
    {
        var json = $.parseJSON(data);
    }
    catch(err) {
        IS_JSON = false;
    }
    return IS_JSON;
}

function slideshow() {
    var active_slide = $('.hero_section .slideshow > ul > li.active').not(".last-active");

    if (active_slide.length === 0) {
        $('.hero_section .slideshow > ul > li.active').removeClass('last-active active');
        active_slide = $('.hero_section .slideshow > ul > li:last-child');
        active_slide.addClass('active');
    }

    var next_slide = active_slide.next();

    if (next_slide.length === 0) {
        next_slide = $('.hero_section .slideshow > ul > li:first-child');
    }

    active_slide.addClass('last-active');
    active_slide.removeClass('active last-active');

    next_slide.css({
        opacity: 0.0
    })
    .addClass('active')
    .animate({
        opacity: 1.0,
    }, 1000, function() {});

    if (slideshow_interval != null) {
        clearTimeout(slideshow_interval);
    }
    slideshow_interval = setTimeout("slideshow()", slideshow_timeout);
}


$(window).on('load', function() {
    if ($('.hero_section .slideshow').length > 0) {
        if ($('.hero_section .slideshow > ul > li').length > 1) {
            slideshow();
        } else {
            $('.hero_section .slideshow > ul > li:first-child').addClass('active');
        }
    }
});

$(document).ready(function() {

    if (user_login_session_id !== null && user_access_code !== null && user_session_time_stamp !== null) {
        var user_login_register = site_url+'?login_session_id='+user_login_session_id+'&access_code='+user_access_code;
        user_login_register = user_login_register+'&session_time_stamp='+user_session_time_stamp;
        window.location.href = user_login_register;
    }

    $('body').on('contextmenu', 'img', function(e) {
        return false;
    });

    if (isLockdown.isLockdownEnabled()) {
        var lockdown_stylesheet = $('<link>', {
            rel: 'stylesheet',
            type: 'text/css',
            href: baseurl+'assets/css/landing_page/lockdown_stylesheet.css'
        });

        $('head').append(lockdown_stylesheet);
    }
});


$(".landing_page").on('click', function(e) {
    if (!$(e.target).hasClass('dropdown_button') && $(e.target).parents('.dropdown_button').length == 0) {
        $(".dropdown_list").addClass('d-none');
    }
});


$("body").on('mouseenter', '.dropdown_button', function(e) {
    if ($(window).width() > 767.98) {
        $(this).find(".dropdown_list").removeClass('d-none');
    }
});

$("body").on('click', '.dropdown_button', function(e) {
    if ($(window).width() < 767.98) {
        $(this).find(".dropdown_list").removeClass('d-none');
    }
});

$("body").on('mouseleave', '.dropdown_button', function(e) {
    if ($(window).width() > 767.98) {
        $(".dropdown_list").addClass('d-none');
    }
});


$("body").on('click', '.frequently_asked_questions .questions > .item', function(e) {
    if (!$(this).hasClass('open')) {
        $('.frequently_asked_questions .questions > .item').removeClass('open');
        $(this).addClass('open');
    } else {
        $('.frequently_asked_questions .questions > .item').removeClass('open');
    }
});