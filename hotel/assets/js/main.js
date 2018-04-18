var $ = jQuery;

function bookingHotel(event) {
    event.preventDefault();

    var data = $(event.target).serialize();
    var url = $(event.target).attr('action');

    $.ajax({
        url: url,
        data: data,
        method: 'post',
        success: function(res){
            console.log(res);
        }
    });
}

jQuery(document).ready(function () {

    $('.ui-datepicker_show').datetimepicker({
        format:'d/m/Y H:i',
        minDate: 0
    });



    (function($) {
        "use strict";


        /**
         * [isMobile description]
         * @type {Object}
         */
        window.isMobile = {
            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
            }
        }
        window.isIE = /(MSIE|Trident\/|Edge\/)/i.test(navigator.userAgent);
        window.windowHeight = window.innerHeight;
        window.windowWidth = window.innerWidth;


        /**
         * Match height
         */
        $('.row-eq-height > [class*="col-"]').matchHeight();

        var myEfficientFn = debounce(function() {
            $('.row-eq-height > [class*="col-"]').matchHeight();
        }, 250);

        window.addEventListener('resize', myEfficientFn);

        /**
         * [debounce description]
         * @param  {[type]} func      [description]
         * @param  {[type]} wait      [description]
         * @param  {[type]} immediate [description]
         * @return {[type]}           [description]
         */
        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }

        /**
         * Popup
         */
        $('[data-init="magnificPopup"]').each(function(index, el) {
            var $el = $(this);

            var magnificPopupDefault = {
                removalDelay: 500, //delay removal by X to allow out-animation
                callbacks: {
                    beforeOpen: function() {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            }

// Merge settings.
            var settings = $.extend(true, magnificPopupDefault, $el.data('options'));

            $el.magnificPopup(settings);
        });

        /**
         * Swiper
         */
        $('.swiper__module').each(function() {
            var self = $(this),
                wrapper = $('.swiper-wrapper', self),
                optData = eval('(' + self.attr('data-options') + ')'),
                optDefault = {
                    paginationClickable: true,
                    pagination: self.find('.swiper-pagination-custom'),
                    nextButton: self.find('.swiper-button-next-custom'),
                    prevButton: self.find('.swiper-button-prev-custom'),
                    spaceBetween: 30
                },
                options = $.extend(optDefault, optData);
            wrapper.children().wrap('<div class="swiper-slide"></div>');
            var swiper = new Swiper(self, options);

            function thumbnails(selector) {

                if (selector.length > 0) {
                    var wrapperThumbs = selector.children('.swiper-wrapper'),
                        optDataThumbs = eval('(' + selector.attr('data-options') + ')'),
                        optDefaultThumbs = {
                            spaceBetween: 10,
                            centeredSlides: true,
                            slidesPerView: 3,
                            touchRatio: 0.3,
                            slideToClickedSlide: true,
                            pagination: selector.find('.swiper-pagination-custom'),
                            nextButton: selector.find('.swiper-button-next-custom'),
                            prevButton: selector.find('.swiper-button-prev-custom'),
                        },
                        optionsThumbs = $.extend(optDefaultThumbs, optDataThumbs);
                    wrapperThumbs.children().wrap('<div class="swiper-slide"></div>');
                    var swiperThumbs = new Swiper(selector, optionsThumbs);
                    swiper.params.control = swiperThumbs;
                    swiperThumbs.params.control = swiper;
                }

            }
            thumbnails(self.next('.swiper-thumbnails__module'));
        });


    })(jQuery);

});