(function( $ ) {
    $(function() {
        $( '.utttravel-color-picker' ).wpColorPicker();

    });

    $('.en-select2').each(function(index, val){
        var ajaxUrl = $(this).data('ajax');

        if (typeof ajaxUrl !== 'undefined' && ajaxUrl != '') {
            $(this).select2({
                ajax: {
                    url: ajaxUrl,
                    dataType: 'json',
                    processResults: function (data) {
                        console.log(data.results);
                        return {
                            results: data.results
                        };
                    }
                }
            });
        } else {
            $(this).select2({

            });
        }

        $(this).on('select2:select', function(e){
            var id = e.params.data.id;
            var option = $(e.target).children('[value='+id+']');
            option.detach();
            $(e.target).append(option).change();
        });

    });

    $('.UTTorderList').sortable({
        items: 'li',
        cursor: 'move'
    });

})( jQuery );

function UTTOpenTab(event, target)
{
    event.preventDefault();
    $(event.target).addClass('active').siblings('a').removeClass('active');
    $(target).addClass('active').siblings('.contentTab').removeClass('active');
}

function uttAddTime(event) {
    event.preventDefault();

    var $parent  = $(event.target).closest('.timestamp-wrap');

    var d = parseInt($parent.find('input.day').val());
    var m = parseInt($parent.find('select.month').val());
    var y = parseInt($parent.find('input.year').val());
    var h = parseInt($parent.find('input.hour').val());
    var min = parseInt($parent.find('input.minute').val());

    var value = m + '_' + d + '_' + y + '_' + h + '_' + min;
    var html = $parent.find('.templateSelected').html();

    var dObj = new Date();
    var uniId = dObj.getTime();

    var patt;
    patt=new RegExp('__index__', "g");
    html = html.replace(patt, uniId);

    patt=new RegExp('__value__', "g");
    html = html.replace(patt, value);

    patt=new RegExp('__date__', "g");
    html = html.replace(patt, m + '/'+ d + '/' + y);

    patt=new RegExp('__time__', "g");
    html = html.replace(patt, h + ':' + min);

    $parent.find('.areaSelected').prepend(html);
}

function uttRemoveTime(event) {
    event.preventDefault();
    $(event.target).closest('.itemTime').remove();
}


function optionPluginSubmit(event, url) {
    if (typeof tinyMCE  != 'undefined') {
        tinyMCE.triggerSave();
    }

    event.preventDefault();
    var data = $(event.target).serialize();
    $.post(url, data, function(res){
        location.reload();
    })
}

function uttChangeJourney(event) {
    var $el = $(event.target);
    var dataOption = [];
    var name = $el.attr('name');
    name = name.replace('journey', 'journey_reorder');

    $el.find('option:selected').each(function (index, val) {
        dataOption.push({
            'label' : $(val).text(),
            'value' : $(val).attr('value')
        });
    });

    var html = '<ul class="UTTorderList">';
    dataOption.map(function (value, index) {
        html += '<li><input name="'+ name +'" value="'+ value.value +'" type="hidden">'+ value.label +'</li>';
    });
    html += '</ul>';

    $el.closest('td').find('.UTTorderList').remove();
    $el.closest('td').append(html);

    $el.closest('td').find('.UTTorderList').sortable({
        items: 'li',
        cursor: 'move'
    });
}
