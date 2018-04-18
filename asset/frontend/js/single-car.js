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
                        offsetTop = 20;
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

    $('.ut-toggle__heading').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('toggle');
        $(this).toggleClass('active');
        $(this).find('i').toggleClass('ut-icon-plus ut-icon-minus')
        $('#' + target).toggle('300');
    })

// Date picker
    var today = new Date();
    var pickerGet = new Pikaday({
        field: document.getElementById('get-date'),
        defaultDate: today,
        setDefaultDate: today,
        format: 'DD-MM-YYYY',
        minDate: today
    })
    var pickerReturn = new Pikaday({
        field: document.getElementById('return-date'),
        defaultDate: today,
        setDefaultDate: today,
        format: 'DD-MM-YYYY',
        minDate: today
    })
});