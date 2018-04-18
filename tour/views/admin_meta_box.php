<?php


global $wpdb;
global $post;

$postMeta = array();
$postMetaTourData = array();
$postMetaBookingData = array();

$postIdRequest = UTTTravelRequest::getQuery('post_id', '');
if (!is_admin() && !empty($postIdRequest) ) {
    $id = $postIdRequest;
} else if(is_a($post, 'WP_Post')) {
    $id = $post->ID;
}

$postMeta = get_post_meta($id, UTTTravelTour::$keyMeta, true);
$postMetaTourData = get_post_meta($id, UTTTravelTour::$keyMetaTourDetail, true);
$postMetaBookingData = get_post_meta($id, UTTTravelTour::metaKeyBooking, true);

$table_name = $wpdb->prefix . UTTConfig::TABLE_ATTRIBUTE_TAXONOMIE;
$attributes = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY `attribute_id` DESC', OBJECT );

$attributesSelected = get_post_meta($id, 'attributesSelected', true);
if (empty($attributesSelected)) {
    $attributesSelected = array();
}
?>


<div class="rowInside">
    <div class="tabVertical">
        <div class="headerTab">
            <a href="" onclick="UTTOpenTab(event, '#general')" class="item active">
                <?php _e('General') ?>
            </a>

            <a href="" onclick="UTTOpenTab(event, '#attributes')" class="item">
                <?php _e('Attributes') ?>
            </a>

            <a href="" onclick="UTTOpenTab(event, '#tourdetail')" class="item">
                <?php _e('Tour detail') ?>
            </a>

            <a href="" onclick="UTTOpenTab(event, '#booking')" class="item">
                <?php _e('Booking') ?>
            </a>

        </div>

        <div class="bodyTab" id="bodyTabTour">
            <div class="contentTab active" id="general">
                <?php
                    $region = get_terms(array(
                        'taxonomy' => UTTTravelTour::regionTour,
                        'hide_empty' => false,
                    ));

                    $regionOptions = array();
                    foreach ($region as $item) {
                        $regionOptions[$item->term_id] = $item->name;
                    }

                    $options =  array(
                        'departure' => array(
                            'type' => 'select',
                            'label' => __('Starting Line'),
                            'select2' => true,
                            'multiple' => true,
                            'options' => $regionOptions,
                            'attributes' => array(
                                'style' => 'width: 100%'
                            )
                        ),
                        'journey' => array(
                            'type' => 'select',
                            'label' => __('Journey'),
                            'select2' => true,
                            'multiple' => true,
                            'options' => $regionOptions,
                            'attributes' => array(
                                'style' => 'width: 100%',
                                'onchange' => 'uttChangeJourney(event)',
                            ),
                            'after' => uttRengerJourneySort($id, UTTTravelTour::$keyMeta . '[journey_reorder][]')
                        ),
                        'sku' => array(
                            'type' => 'text',
                            'label' => __('Tour code'),
                        ),
                        'time' => array(
                            'type' => 'text',
                            'label' => __('Tour duration'),
                        ),
                        'rest' => array(
                            'type' => 'text',
                            'label' => __('Tour capacity'),
                        ),
                        'customertype' => array(
                            'type' => 'text',
                            'label' => __('Customer Type'),
                        ),

                        'text_feature' => array(
                            'type' => 'text',
                            'label' => __('Text feature')
                        ),

                        UTTTravelTour::$keyMetaGallery => array(
                            'type' => 'multiple_image',
                            'label' => __('Galleries')
                        )
                    );


                    echo renderHtmlOption($options, $postMeta, UTTTravelTour::$keyMeta);
                ?>
            </div>
            <div class="contentTab" id="attributes">
                <p>
                    <?php _e('Select Attributes') ?>
                    <select name="attributesSelected[]" id="selectAttributes" class="en-select2" style="width: 200px;">
                        <option value=""><?php _e('Select a Attribute') ?></option>
                        <?php foreach ($attributes as $attribute): ?>
                            <option <?php echo (in_array($attribute->attribute_name, $attributesSelected) ? 'disabled' : '') ?> value="<?php echo $attribute->attribute_name ?>"><?php echo $attribute->attribute_label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="UTTMetaBox.UTTaddOptionAttribute(event, '#selectAttributes')" class="button">Add</button>
                </p>

                <div class="areaSelectedAttributes">
                    <table class="top uttTable tableAttributes">
                        <?php if ( count($attributesSelected) > 0 ) : ?>
                            <?php foreach ($attributesSelected as $key => $item): ?>
                                <tr data-taxonomy="<?php echo $key ?>">
                                    <td><?php echo $key ?></td>
                                    <td>
                                        <select multiple style="width: 100%" name="tax_input[<?php echo $key ?>][]" id="" class="en-select2">
                                            <?php
                                                $term = get_terms(array(
                                                    'taxonomy' => $key,
                                                    'hide_empty' => false,
                                                ));

                                                foreach ($term as $_item) {
                                                    echo '<option '. (is_array($item) && in_array($_item->term_id, $item) ? 'selected' : '') .' value="'. $_item->name .'">'. $_item->name .'</option>';
                                                }
                                            ?>
                                        </select>
                                        <br>
                                        <button type="button" onclick="UTTMetaBox.UTTselectAlltr(event)" class="button selectAlltr">Select all</button>
                                        <button type="button" onclick="UTTMetaBox.UTTselectNonetr(event)" class="button selectNonetr">Select none</button>
                                        <a href="" onclick="UTTMetaBox.removeTr(event)" class="button button-link right text-danger">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>

            </div>




            <?php
            /*
             * Booking TAB
             */
            ?>
            <div class="contentTab" id="booking">
                <?php

                $hotelAvali = (isset($postMetaBookingData['booking_hotels']) ? (array) $postMetaBookingData['booking_hotels'] : array());
                $hotels = array(
                    '' => __('Select a option', 'ultimate-travel')
                );
                $hotelAvali = array_filter($hotelAvali);
                if (count($hotelAvali) > 0) {
                    foreach ($hotelAvali as $item) {
                        $hotels[$item] = get_the_title($item);
                    }
                }

                $options =  array(
                    'booking_hotels' => array(
                        'type' => 'select',
                        'label' => __('Hotels'),
                        'select2' => true,
                        'select2_ajax' => admin_url('/admin-ajax.php') . '?action=getpostype&posttype=hotel',
                        'options' => $hotels,
                        'attributes' => array(
                            'style' => 'width: 100%'
                        )
                    ),
                    'price' => array(
                        'type' => 'number',
                        'label' => __('Price for Adults'),
                    ),
                    'price_sale' => array(
                        'type' => 'number',
                        'label' => __('Price for Adults on sale'),
                    ),
                    'price_children' => array(
                        'type' => 'number',
                        'label' => __('Price for Children'),
                    ),
                    'price_children_sale' => array(
                        'type' => 'number',
                        'label' => __('Price for Children on sale'),
                    ),
                    'schedule' => array(
                        'type' => 'uttdatepicker',
                        'label' => __('Departure Schedule')
                    )
                );

                echo renderHtmlOption($options, $postMetaBookingData, UTTTravelTour::metaKeyBooking);
                ?>
            </div>
            <?php
            /*
             * Booking TAB
             */
            ?>




            <?php
            /*
             * TOUR DETAIL TAB
             */
            ?>
            <div class="contentTab" id="tourdetail">
                <div class="groupTourData en_sort " id="groupTourData">

                    <?php
                    if (!is_array($postMetaTourData) || count($postMetaTourData) == 0) {
                        $postMetaTourData = array(array());
                    }

                    foreach ($postMetaTourData as $key => $item) { ?>
                        <div class="blockTourData uttfield itemsort">
                            <button class="removeFieldGroup" onclick="$(this).closest('.blockTourData').remove()" type="button"><?php _e('Delete', 'ultimate-travel') ?></button>
                    <?php $options =  array(
                            'icon' => array(
                                'type' => 'icontravel',
                                'label' => __('Icon'),
                            ),
                            'title' => array(
                                'type' => 'text',
                                'label' => __('Title'),
                            ),
                            'desc' => array(
                                'type' => 'text',
                                'label' => __('Description'),
                            ),
                            'content' => array(
                                'type' => 'editor',
                                'label' => __('Content'),
                                'layout' => 'basic',
                                'rows' => 10
                            )
                        );
                        $options =  array(
                            $key => array(
                                'type' => 'group',
                                'element' => $options
                            )
                        );
                        echo renderHtmlOption($options, $postMetaTourData, UTTTravelTour::$keyMetaTourDetail);

                        echo '</div>';
                    }
                    ?>
                </div>

                <div class="cloneField">
                    <div class="blockTourData uttfield itemsort">
                        <button class="removeFieldGroup" onclick="$(this).closest('.blockTourData').remove()" type="button"><?php _e('Delete', 'ultimate-travel') ?></button>
                        <?php
                        $options =  array(
                            'icon' => array(
                                'type' => 'icontravel',
                                'label' => __('Icon'),
                            ),
                            'title' => array(
                                'type' => 'text',
                                'label' => __('Title'),
                            ),
                            'desc' => array(
                                'type' => 'text',
                                'label' => __('Description'),
                            ),
                            'content' => array(
                                'type' => 'textarea',
                                'label' => __('Content'),
                                'layout' => 'basic',
                                'class' => 'en-editor-js',
                                'id' => 'editor___name__',
                            )
                        );
                        $options =  array(
                            '__name__' => array(
                                'type' => 'group',
                                'element' => $options
                            )
                        );
                        echo renderHtmlOption($options, array(), UTTTravelTour::$keyMetaTourDetail);
                        ?>
                    </div>

                    <div class="text-right">
                        <button onclick="cloneFieldGroup(event, '#groupTourData')" class="button button-primary"><?php _e('Add row'); ?></button>
                    </div>
                </div>
            </div>
            <?php
            /*
             * TOUR DETAIL TAB
             */
            ?>

        </div>

        <div class="clear"></div>
    </div>
</div>

<div class="template" id="templateJs" style="display: none">
    <table class="" id="tableAttribute">
        <tr>
            <td>__label__</td>
            <td>
                <select multiple style="width: 100%" name="tax_input[__tax_input__][]" id="" class="">__options__</select>
                <br>
                <button type="button" onclick="UTTMetaBox.UTTselectAlltr(event)" class="button selectAlltr">Select all</button>
                <button type="button" onclick="UTTMetaBox.UTTselectNonetr(event)" class="button selectNonetr">Select none</button>
                <a href="" onclick="UTTMetaBox.removeTr(event)" class="button button-link right text-danger">Remove</a>
            </td>
        </tr>
    </table>
</div>


<script>
    var UTTMetaBox = {
    };
    var $ = jQuery;

    UTTMetaBox.UTTselectAlltr = function(event) {
        $(event.target).closest('td').find('select').find('option').prop('selected', true);
        $(event.target).closest('td').find('select').trigger('change');
    };

    UTTMetaBox.UTTselectNonetr = function(event) {
        $(event.target).closest('td').find('select').find('option').prop('selected', false);
        $(event.target).closest('td').find('select').trigger('change');
    };

    UTTMetaBox.removeTr = function(event) {
        event.preventDefault();
        var tax = $(event.target).closest('tr').data('taxonomy');
        $('#selectAttributes').find('[value="'+ tax +'"]').removeAttr('disabled');
        $('#selectAttributes').select2();
        $(event.target).closest('tr').remove();
    };

    UTTMetaBox.UTTaddOptionAttribute = function(event, target) {
        var val = $(target).val();
        var oldT = $(event.target).text();
        $(event.target).text('Adding...');
        $.post(ajaxurl + '?action=getoptionattribute', {'taxonomy': val}, function(res){
            if (res.success === true) {
                $(target).find(':selected').attr('disabled', true);
                $(target).select2();

                var html = $('#tableAttribute').html();
                html = html.replace('__label__', val);

                var options = '';
                $.each(res.data, function(i, v) {
                    options += '<option value="'+ v.name +'">'+ v.name +'</option>';
                });
                html = html.replace('__options__', options);
                html = html.replace('__tax_input__', val);
                html = $(html);
                html.find('tr').data('taxonomy', val);
                html.find('select').select2();

                html.appendTo('.areaSelectedAttributes table');
            }

        }).done(function () {
            $(target).val('').trigger('change');
            $(event.target).text(oldT);
        });
    };
</script>