var verticalSlider = document.getElementById('ut-filter-rating-slider');
if(verticalSlider) {
    var reviewIcons = document.getElementsByClassName('tour_filter_review_icon')[0];

    noUiSlider.create(verticalSlider, {
        start: [1, 5],
        step: 1,
        orientation: 'vertical',
        connect: true,
        direction: 'rtl',
        tooltips: true,
        format: {
            to: function ( value ) {
                return value;
            },
            from: function ( value ) {
                return value.replace(',-', '');
            }
        },
        range: {
            'min': [1],
            'max': [5]
        }
    });
    verticalSlider.noUiSlider.on('change', function(values) {
        var review = reviewIcons.getElementsByClassName('ut-checkbox-filter');
        for(var i = 0; i < review.length; i++) {
            review[i].checked = false;
            if(review[i].dataset.value == values[0] || review[i].dataset.value == values[1]) {
                review[i].checked = true;
            }
        }
        reviewCheck(values[0], values[1]);
    });
}