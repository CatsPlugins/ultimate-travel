var $ = jQuery;

$(document).on('click', '.overlayPopup', function(){
    $(this).closest('.contentPopup').toggle();
})
.on('click', '.iconTravelSelect .button', function(){
    var nameinput = $(this).data('nameinput');
    var icon = $(this).data('iconclass');
    var newHtml ='<button class="button">';
    var $selectedArea = $(this).closest('.wrapPopupinline').find('.valueSelected');

    newHtml += '<i class="'+icon+'"></i>';
    newHtml += "<input type='hidden' name='"+ nameinput +"' value='"+ icon +"'>";
    newHtml += "</button>";
    $selectedArea.html(newHtml);
})
.ready(function(){
    // Product gallery file uploads.
    var product_gallery_frame;




    $( '.add_galleries' ).on( 'click', 'a', function( event ) {
        var $el = $( this );
        var $product_images = $(this).closest('.uttGalleries').find('.ul_images');
        var $image_gallery_ids = $(this).closest('.uttGalleries').find('.recieverGalleries');

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( product_gallery_frame ) {
            product_gallery_frame.open();
            return;
        }

        // Create the media frame.
        product_gallery_frame = wp.media.frames.product_gallery = wp.media({
            // Set the title of the modal.
            title: $el.data( 'choose' ),
            button: {
                text: $el.data( 'update' )
            },
            states: [
                new wp.media.controller.Library({
                    title: $el.data( 'choose' ),
                    filterable: 'all',
                    multiple: true
                })
            ]
        });

        // When an image is selected, run a callback.
        product_gallery_frame.on( 'select', function() {
            var selection = product_gallery_frame.state().get( 'selection' );
            var attachment_ids = $image_gallery_ids.val();

            selection.map( function( attachment ) {
                attachment = attachment.toJSON();

                if ( attachment.id ) {
                    attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                    var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                    $product_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>' );
                }
            });
            $image_gallery_ids.val( attachment_ids );
        });

        // Finally, open the modal.
        product_gallery_frame.open();
    });

// Image ordering.
    $('.ul_images').sortable({
        items: 'li.image',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        start: function( event, ui ) {
            ui.item.css( 'background-color', '#f6f6f6' );
        },
        stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
        },
        update: function(event, ui) {

            var attachment_ids = '';
            var $image_gallery_ids =  $(this).closest('.uttGalleries').find('.recieverGalleries');

            $( event.target ).find('li.image').css( 'cursor', 'default' ).each( function() {
                var attachment_id = $( this ).attr( 'data-attachment_id' );
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $image_gallery_ids.val( attachment_ids );
        }
    });

    $('.en_sort').sortable({
        items: '.itemsort',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        start: function( event, ui ) {
            ui.item.css( 'background-color', '#f6f6f6' );
        },
        stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
        }
    });

// Remove images.
    $('.ul_images').on( 'click', 'a.delete', function() {
        var $wrap = $(this).closest('ul.ul_images');
        var dataWrap = $wrap.data();

        $( this ).closest( 'li.image' ).remove();

        var attachment_ids = '';
        var $image_gallery_ids =  $wrap.closest('.uttGalleries').find('.recieverGalleries');

        $wrap.find( 'li.image' ).css( 'cursor', 'default' ).each( function() {
            var attachment_id = $( this ).attr( 'data-attachment_id' );
            attachment_ids = attachment_ids + attachment_id + ',';
        });

        $image_gallery_ids.val( attachment_ids );

        return false;
    });



// Upload Button in Taxonomy
    $('body').on('click','.utt_tax_media_remove',function(){
        var $tr = $(this).closest('tr');
        $tr.find('.inputRecieverImage').val('')
        $tr.find('.image-wrapper').html('');
    });
    $('.utt_tax_media_button.button').click(function(e) {
        var $buttonClicking = $(this);
        e.preventDefault();

        var custom_uploader;

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            var $tr = $buttonClicking.closest('.wrapImageSingle');
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $tr.find('.inputRecieverImage').val(attachment.id)
            $tr.find('.image-wrapper').html('<img class="custom_media_image" src="'+attachment.url+'" style="margin:0;padding:0;max-height:100px;float:none; display: block" />');
        });

        //Open the uploader dialog
        custom_uploader.open();

        return true;
    });
});


function UTTuploadImageReset(event, target, preview, deletebtn) {
    event.preventDefault();
    $(target).val('')
    $(preview).html('');
    $(deletebtn).css({'display': 'none'});
}

function UTTuploadImage(event, target, preview, deletebtn) {
    var $buttonClicking = $(this);
    event.preventDefault();

    var custom_uploader;

    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false
    });

    //When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
        var $tr = $buttonClicking.closest('tr');
        attachment = custom_uploader.state().get('selection').first().toJSON();
        $(target).val(attachment.id)
        $(preview).html('<img class="custom_media_image" src="'+attachment.url+'" style="margin:0;padding:0;max-height:100px;float:none; display: block" />');
        $(deletebtn).css({'display': 'inline-block'});
    });

    //Open the uploader dialog
    custom_uploader.open();
}

function cloneFieldGroup(event, target)
{
    event.preventDefault();
    var $wrap = $(event.target).closest('.cloneField');
    var d = new Date();
    var n = d.getTime();
    var html = $wrap.find('.uttfield').prop('outerHTML');

    var patt=new RegExp('__name__', "g");
    html = html.replace(patt, n);
    $(target).append(html);
    $('#' + 'editor_' + n).wp_editor();
}


var apiUrl = 'http://maps.googleapis.com/maps/api/geocode/json?address=';
function UttSearchMap(event) {
    var v = $(event.target).val();
    var $wrap = $(event.target).closest('.locationPicker');
    $wrap.find('.resultMap').html('');
    $.ajax({
        url: apiUrl + v,
        success: function(data){
            if(data.status == 'OK') {
                $.each(data.results, function(index, value) {
                    var html = '<a \
                                    href="#"\
                                    onclick="uttBindMapValue(event)"\
                                    data-lng="'+value.geometry.location.lng+'\"\
                                    data-lat="'+value.geometry.location.lat+'\"\
                                    >\
                                    '+value.formatted_address+'\
                                    </a>';
                    $wrap.find('.resultMap').append(html);
                });
            }
        }
    });
}

function uttBindMapValue(event){
    event.preventDefault();
    var data = $(event.target).data();
    var $wrap = $(event.target).closest('.locationPicker');
    var text = $.trim($(event.target).text());
    
    $wrap.find('.mainInput').val(text);
    $wrap.find('.mainInputLng').val(data.lng);
    $wrap.find('.mainInputLat').val(data.lat);
    $wrap.find('.resultMap').html('');
}


$(document).ajaxComplete(function(event, xhr, settings) {
    if (typeof  settings.data != 'undefined') {
        var queryStringArr = settings.data.split('&');
        if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
            var xml = xhr.responseXML;
            $response = $(xml).find('term_id').text();
            if($response!=""){
                $('.wrapImageSingle').find('.inputRecieverImage').val('');
                $('.wrapImageSingle').find('.image-wrapper').html('');
            }
        }
    }
});