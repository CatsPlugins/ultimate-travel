// show more / show less list
var $ = jQuery;

var UTTnumberFormat;
if (typeof dataJsUTT === 'object') {
    UTTnumberFormat = wNumb({
        mark: dataJsUTT.decimalPoint,
        thousand: dataJsUTT.thousandPoint,
        decimals: 0
    });
} else {
    UTTnumberFormat = wNumb({
        mark: '.',
        thousand: ',',
        decimals: 0
    });
}

function UTTBookingTour(event, data) {
    var numberChildren, numberAdults;

    if (!$.isNumeric($(data.numberChildren).val())) {
        numberChildren = 0;
        $(data.numberChildren).val('');
    }  else {
        numberChildren = parseInt($(data.numberChildren).val());
    }

    if (!$.isNumeric($(data.numberAdults).val())) {
        numberAdults = 1;
        $(data.numberAdults).val('');
    }  else {
        numberAdults = parseInt($(data.numberAdults).val());
    }

    var totalChildren = numberChildren * data.tourBooking.price_children_booking;
    var totalAdults = numberAdults * data.tourBooking.price_adults_booking;

    var totalMoney = totalChildren + totalAdults;

    totalChildren = UTTnumberFormat.to(totalChildren);
    totalAdults = UTTnumberFormat.to(totalAdults);
    totalMoney =  UTTnumberFormat.to(totalMoney);

    $(data.totalChildren).text(totalChildren);
    $(data.totalAdults).text(totalAdults);
    $(data.totalMoney).text(totalMoney);
}

function uttSetRating(event, value) {
    event.preventDefault();
    var $parent = $(event.target).parent();
    $parent.find('.valuerating').val(value);

    for (var $i = 0; $i < 5; $i++) {
        if ($i < value) {
            $parent.find('.item').eq($i).removeClass('ut-icon-star-o').addClass('ut-icon-star');
        } else {
            $parent.find('.item').eq($i).removeClass('ut-icon-star').addClass('ut-icon-star-o');
        }

    }
}

$(document).ready(function(){
    $("a.ut-show-more").click(function (e) {
        e.preventDefault();
        var list = $(this).closest('.ut-list');
        list.find("[data-show='" + $(this).attr('href') + "']").show();
        list.find('a.ut-show-less[href="' + $(this).attr('href') + '"]').show();
        $(this).hide();
    });

    $("a.ut-show-less").click(function (e) {
        e.preventDefault();
        var list = $(this).closest('.ut-list');
        list.find("[data-show='" + $(this).attr('href') + "']").hide();
        list.find("a.ut-show-more[href='" + $(this).attr('href') + "']").show();
        $(this).hide();
    });

// filter on mobile
    if($(window).width() < 767) {
        var filterControlBtn = $('#ut-filter-apply, #ut-filter-cancel');
        var showFilter = $('#ut-filter-show');
        var applyFilter = $('#ut-filter-apply');
        var cancelFilter = $('#ut-filter-cancel');
        var filterDiv = $('.ut-filter')

        showFilter.click(function(e) {
            e.preventDefault();
            $(this).addClass('ut-hidden');
            filterControlBtn.removeClass('ut-hidden');
            filterDiv.addClass('show');
        });

        applyFilter.click(function(e) {
            e.preventDefault();
            showFilter.removeClass('ut-hidden');
            filterControlBtn.addClass('ut-hidden');
            filterDiv.removeClass('show');
            doSearch();
        });

        cancelFilter.click(function(e) {
            e.preventDefault();
            filterControlBtn.addClass('ut-hidden');
            showFilter.removeClass('ut-hidden');
            filterDiv.removeClass('show');
        })
    }

    $('.uttInputRating .item').hover(function(event){
        var indexTarget = $(event.target).index();
        var $parent = $(event.target).parent();
        for(var $i = 0; $i < 5; $i ++) {
            if ($i <= indexTarget) {
                $parent.find('.item').eq($i).addClass('active');
            }
        }

    }, function (event) {
        var $parent = $(event.target).parent();
        $parent.find('.item').removeClass('active');
    });


    var slidePrice = document.getElementById('utt_filter_price');
    if (slidePrice){
        var data = slidePrice.dataset;
        var currentMin = parseInt($('.utt-filter-price-from').find('input').val());
        var currentMax = parseInt($('.utt-filter-price-to').find('input').val());

        if (currentMax >= 0 && currentMin >= 0 && currentMax > currentMin) {

        } else {
            currentMin = data.min;
            currentMax = data.max;
        }

        noUiSlider.create(slidePrice, {
            start: [currentMin, currentMax],
            connect: true,
            step: parseInt(data.steprange),
            format: wNumb({
                decimals: 0
            }),
            range: {
                'min': parseInt(data.min),
                'max': parseInt(data.max)
            }
        });


        slidePrice.noUiSlider.on('end', function(values) {
            $(slidePrice).closest('form').trigger('submit');
        });

        slidePrice.noUiSlider.on('update', function(values) {
            var min = parseInt(values[0]);
            var max = parseInt(values[1]);

            $('.utt-filter-price-from').find('input').val(min);
            $('.utt-filter-price-to').find('input').val(max);

            $('.utt-filter-price-from').find('.value').text(UTTnumberFormat.to(min));
            $('.utt-filter-price-to').find('.value').text(UTTnumberFormat.to(max));
        });
    }

    var verticalSlider = document.getElementById('ut-filter-rating-slider');
    if(verticalSlider) {
        var reviewIcons = document.getElementsByClassName('tour_filter_review_icon')[0];
        var dataRating = verticalSlider.dataset;

        noUiSlider.create(verticalSlider, {
            start: [parseInt(dataRating.currentmin), parseInt(dataRating.currentmax)],
            step: parseInt(dataRating.step),
            orientation: 'vertical',
            connect: true,
            direction: 'rtl',
            tooltips: true,
            format: wNumb({
                decimals: 0
            }),
            range: {
                'min': parseInt(dataRating.min),
                'max': parseInt(dataRating.max)
            }
        });

        verticalSlider.noUiSlider.on('end', function(values) {
            $(verticalSlider).closest('form').trigger('submit');
        });

        verticalSlider.noUiSlider.on('update', function(values) {
            var min = values[0];
            var max = values[1];

            $(dataRating.targetmin).val(min);
            $(dataRating.targetmax).val(max);
        });
    }

});


jQuery(document).on('catsInitMap', function () {

    if ($('#mapHotel').length > 0) {
        var map;

        var data = $('#mapHotel').data();
        var options = {
            zoom: 14,
            center: {
                lat: data.lat,
                lng: data.lng
            }
        };
        map = new google.maps.Map(document.getElementById('mapHotel'), options);

        var marker = new google.maps.Marker({
            position: options.center,
            map: map
        });
    }

});


if (typeof catsInitMap === 'undefined') {
    function catsInitMap(){
        $(document).ready(function(){
            $(document).trigger('catsInitMap');
        });
    }
}


// SCRTIP FOR RATING TOOL
var CatSetRating = {};
CatSetRating.wrapInput = '.ratingInput';
CatSetRating.startbtn = '.startbtn';
CatSetRating.wrapItem = '.itemCol';
CatSetRating.groupStartBtn = '.groupStartBtn';
CatSetRating.recieverRatingValue = '.recieverRatingValue';
CatSetRating.activeStartClass = 'active';
CatSetRating.deActiveStartClass = 'non-active';
$(document).ready(function(){
    CatSetRating.setRatingToInput = function(event){
        var $btn = $(event.target);
        var currentValue = $btn.index();
        var $wrap = $btn.closest(CatSetRating.groupStartBtn);

        $btn.closest(CatSetRating.wrapItem).find(CatSetRating.recieverRatingValue).val(currentValue + 1);

        $wrap.find(CatSetRating.startbtn).removeClass(CatSetRating.activeStartClass);
        $wrap.find(CatSetRating.startbtn).each(function(index, el){
            if (index <= currentValue) {
                $(el).addClass(CatSetRating.activeStartClass);
            }
        });
    };

});