var $ = jQuery;

function tourSubmitBooking(event) {
    event.preventDefault();

    var data = $(event.target).serialize();

    $.ajax({
        url: dataJsUTT.ajaxurl + '?action=form-booking',
        data: data,
        method: 'post',
        dataType: 'json',
        success: function (res) {
            $('#contact-form_frame').html(res.data.html);
            $('.opentourBooking').trigger('click');
        }
    });
}

$(document).ready(function(){

    $('.ut-product__image-list').owlCarousel({
        items: 3,
        margin: 15,
        dots: false,
        loop: true
    });
    $('.ut-product__image-list img').on('click', function() {

        var href = $(this).attr('src');
        $('.ut-product__main-image img').attr('src', href);
    });
    $('.ut-product__photos__content').owlCarousel({
        items: 1,
        loop: true,
        dots: false,
        nav: true,
        navText: ["<i class='ut-icon-angle-left'></i>","<i class='ut-icon-angle-right'></i>"],
    });
    window.addEventListener('load', function() {
        if ($('#myNavbar').length > 0) {
            var offsetTop = $('#myNavbar').offset().top;
            $("#myNavbar").affix({offset: {top: offsetTop} });
        }
    });

// fix sidebar
    if($(window).width > 767) {
        window.addEventListener('load', function() {
            var lastScrollTop = 0;
            var sidebarWrap = document.getElementsByClassName('ut-product-sidebar')[0];
            var sidebarWrapWidth = sidebarWrap.clientWidth;
            var sidebarWrapTop = sidebarWrap.offsetTop;
            var sidebar = document.getElementsByClassName('ut-product-sidebar__content')[0];
            var sidebarTop = sidebar.getBoundingClientRect().top;
            var sidebarHeight = sidebar.clientHeight;
            var sidebarBottom = sidebarHeight + sidebar.scrollTop;
            var footer = document.getElementsByTagName('footer')[0];
            var docHeight = document.body.scrollHeight;
            var wHeight = window.innerHeight;
            var scrollTop = 0;
            var offsetTop = 0;
            var bodyHeight = document.body.clientHeight;

            sidebar.classList.remove('affix');
            sidebar.style.maxWidth = (sidebarWrapWidth - 30) + 'px';
            if(window.innerWidth >= 576) {
                window.onscroll = function() {
                    st = window.pageYOffset;
                    var bottomPos = docHeight - st - 30 - Math.abs(sidebarHeight - wHeight) - 50;
                    if(st >= (sidebarWrapTop - 70)) {
                        sidebar.classList.add('affix');
                        offsetTop = 70;
                        if(bottomPos <= wHeight) {
                            //reach bottom
                            offsetTop = offsetTop - (st - scrollTop)
                        }
                        else {
                            scrollTop = st;
                        }
                        sidebar.style.top = offsetTop + 'px';
                    }
                    else {
                        sidebar.classList.remove('affix');
                        sidebar.style.top = 0 + 'px';

                    }

                    lastScrollTop = st
                }
            }
        })
    }

    window.addEventListener('load', function() {
        var headerHeight = parseInt($('#myNavbar').outerHeight(false));
        gumshoe.init();
        $("#myNavbar a").on('click', function(event) {
            if (this.hash !== "") {
                event.preventDefault();
                var hash = this.hash;
                var position = $(hash).offset().top - headerHeight;
                $('html, body').animate({
                    scrollTop: position
                }, 800, function() {
                    window.location.hash = hash;
                });
            }
        })
    })
});