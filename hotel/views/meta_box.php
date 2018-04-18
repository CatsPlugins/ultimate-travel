<?php
global $wpdb;

$postIdRequest = UTTTravelRequest::getQuery('post_id', '');
$postMeta = array();
$galleryHotel = '';
$id = '';
if (!is_admin() && !empty($postIdRequest) ) {
    $id = $postIdRequest;
} else if(isset($post) && is_a($post, 'WP_Post')) {
    $id = $post->ID;
    $postMeta = get_post_meta($id, UTTTravelHotel::keyMeta, true);
}

if (!empty($id)) {
    $galleryHotel = get_post_meta($id,UTTTravelHotel::keyGallery, true);
}


?>

<div class="rowInside">


    <div class="tabVertical">
        <div class="headerTab">
            <a href="" onclick="UTTOpenTab(event, '#general')" class="item active">
                <?php _e('General', 'ultimate-travel') ?>
            </a>
            <a href="" onclick="UTTOpenTab(event, '#contact')" class="item">
                <?php _e('Contact', 'ultimate-travel') ?>
            </a>
        </div>

        <div class="bodyTab" id="bodyTabTour">

            <div class="contentTab active" id="general">
                <?php
                /*
                 * Roms setting
                 */
                ?>

                <div class="m-b-5">
                    <?php
                    $options =  array(
                        UTTTravelHotel::keyGallery  => array(
                            'type' => 'multiple_image',
                            'label' => __('Gallery', 'ultimate-travel'),
                        )
                    );

                    echo renderHtmlOption($options, array(
                            UTTTravelHotel::keyGallery => $galleryHotel
                    ), UTTTravelHotel::keyMeta);
                    ?>
                </div>

                <div class="m-b-5">
                    <b><?php _e('Our Rooms') ?></b>
                </div>

                <div class="groupCarData en_sort" id="groupCarData">

                    <?php if (isset($postMeta['utthotelroms'])) : ?>
                        <?php foreach ($postMeta['utthotelroms'] as $key => $item) : ?>
                            <div class="blockCarData itemsort uttfield">
                                <button class="removeFieldGroup" onclick="$(this).closest('.blockCarData').remove()" type="button"><?php _e('Delete', 'ultimate-travel') ?></button>
                                <?php
                                $options =  array(
                                    'image' => array(
                                        'type' => 'image',
                                        'label' => __('Image'),
                                        'default' => @$item['image']
                                    ),
                                    'title' => array(
                                        'type' => 'text',
                                        'label' => __('Title'),
                                        'default' => @$item['title']
                                    ),
                                    'price' => array(
                                        'type' => 'text',
                                        'label' => __('Price'),
                                        'default' => @$item['price']
                                    ),
                                );
                                $options =  array(
                                    uniqid() => array(
                                        'type' => 'group',
                                        'element' => $options
                                    )
                                );
                                echo renderHtmlOption($options, array(), 'utthotelroms');
                                ?>
                            </div>
                        <?php endforeach; ?>
                    <?php  endif; ?>
                </div>

                <div class="cloneField">
                    <div class="blockCarData itemsort uttfield">
                        <button class="removeFieldGroup" onclick="$(this).closest('.blockCarData').remove()" type="button"><?php _e('Delete', 'ultimate-travel') ?></button>
                        <?php
                        $options =  array(
                            'image' => array(
                                'type' => 'image',
                                'label' => __('Image'),
                            ),
                            'title' => array(
                                'type' => 'text',
                                'label' => __('Title'),
                            ),
                            'price' => array(
                                'type' => 'text',
                                'label' => __('Price'),
                            ),
                        );
                        $options =  array(
                            '__name__' => array(
                                'type' => 'group',
                                'element' => $options
                            )
                        );
                        echo renderHtmlOption($options, array(), 'utthotelroms');
                        ?>
                    </div>


                    <div class="text-right">
                        <button onclick="cloneFieldGroup(event, '#groupCarData')" class="button button-primary"><?php _e('Add room'); ?></button>
                    </div>
                </div>









                <hr>








                <?php
                /*
                 * Services setting
                 */
                ?>

                <div class="m-b-5">
                    <b><?php _e('Services') ?></b>
                </div>
                <div class="groupCarService en_sort" id="groupCarService">
                    <?php if (isset($postMeta['utthotelservice'])) : ?>
                        <?php foreach ($postMeta['utthotelservice'] as $key => $item) : ?>
                            <div class="groupCarService itemsort uttfield">
                                <button class="removeFieldGroup" onclick="$(this).closest('.groupCarService').remove()" type="button"><?php _e('Delete', 'ultimate-travel') ?></button>
                                <?php
                                $options =  array(
                                    'image' => array(
                                        'type' => 'image',
                                        'label' => __('Image'),
                                        'default' => @$item['image']
                                    ),
                                    'title' => array(
                                        'type' => 'text',
                                        'label' => __('Title'),
                                        'default' => @$item['title']
                                    )
                                );
                                $options =  array(
                                    uniqid() => array(
                                        'type' => 'group',
                                        'element' => $options
                                    )
                                );
                                echo renderHtmlOption($options, array(), 'utthotelservice');
                                ?>
                            </div>
                        <?php endforeach; ?>
                    <?php  endif; ?>
                </div>
                <div class="cloneField">
                    <div class="groupCarService itemsort uttfield">
                        <button class="removeFieldGroup" onclick="$(this).closest('.groupCarService').remove()" type="button"><?php _e('Delete', 'ultimate-travel') ?></button>
                        <?php
                        $options =  array(
                            'image' => array(
                                'type' => 'image',
                                'label' => __('Image'),
                            ),
                            'title' => array(
                                'type' => 'text',
                                'label' => __('Title'),
                            )
                        );
                        $options =  array(
                            '__name__' => array(
                                'type' => 'group',
                                'element' => $options
                            )
                        );
                        echo renderHtmlOption($options, array(), 'utthotelservice');
                        ?>
                    </div>


                    <div class="text-right">
                        <button onclick="cloneFieldGroup(event, '#groupCarService')" class="button button-primary"><?php _e('Add service'); ?></button>
                    </div>
                </div>





            </div>

            <div class="contentTab" id="contact">
                <?php

                if (!isset($postMeta['metabooking'])) $postMeta['metabooking'] = array();

                $options =  array(
                    'contact_name' => array(
                        'type' => 'text',
                        'label' => __('Contact name'),
                        'default' => utt_get_array_value($postMeta['metabooking'], 'contact_name')
                    ),
                    'contact_address' => array(
                        'type' => 'locationpicker',
                        'label' => __('Contact address'),
                        'lng' => utt_get_array_value($postMeta['metabooking'], 'contact_address_lng'),
                        'lat' => utt_get_array_value($postMeta['metabooking'], 'contact_address_lat'),
                        'address' => utt_get_array_value($postMeta['metabooking'], 'contact_address'),
                        'placeholder' => 'Contact address',
                    ),
                    'contact_phone' => array(
                        'type' => 'text',
                        'label' => __('Phone'),
                        'default' => utt_get_array_value($postMeta['metabooking'], 'contact_phone')
                    ),
                    'contact_email' => array(
                        'type' => 'text',
                        'label' => __('Email'),
                        'default' => utt_get_array_value($postMeta['metabooking'], 'contact_email')
                    ),
                    'contact_website' => array(
                        'type' => 'text',
                        'label' => __('Website'),
                        'default' => utt_get_array_value($postMeta['metabooking'], 'contact_website')
                    ),
                    'contact_google' => array(
                        'type' => 'text',
                        'label' => __('Google link'),
                        'default' => utt_get_array_value($postMeta['metabooking'], 'contact_google')
                    ),
                    'contact_facebook' => array(
                        'type' => 'text',
                        'label' => __('Facebook link'),
                        'default' => utt_get_array_value($postMeta['metabooking'], 'contact_facebook')
                    ),
                    'contact_twitter' => array(
                        'type' => 'text',
                        'label' => __('Twitter link'),
                        'default' => utt_get_array_value($postMeta['metabooking'], 'contact_twitter')
                    )
                );

                echo renderHtmlOption($options, $postMeta, 'metabooking');
                ?>
            </div>

        </div>

        <div class="clear"></div>
    </div>
</div>

