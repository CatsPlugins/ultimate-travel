<?php

class UTTTravelHotel
{
    public $postType = 'hotel';

    const postType = 'hotel';
    const keyMeta = 'utt_hotel_data';
    const keyGallery = 'galleries';

    function run()
    {
        add_action('init', array($this, 'registerPostTypeHotel'));
        add_action('add_meta_boxes', array($this, 'metaBoxHotel'));

        if (get_option('uttinclude_asset', '') == 'on') {
            add_action('wp_enqueue_scripts', array($this, 'addStyle'), 10);
        }

        add_action("save_post_{$this->postType}", array($this, 'saveMetaBox') );

    }

    public function saveMetaBox($post_id)
    {
        $utthotelroms = isset($_POST['utthotelroms']) ? $_POST['utthotelroms'] : array();
        $utthotelservice = isset($_POST['utthotelservice']) ? $_POST['utthotelservice'] : array();
        $metabooking = isset($_POST['metabooking']) ? $_POST['metabooking'] : array();

        $dataView = array();

        unset($utthotelroms['__name__']);
        foreach ($utthotelroms as $key => $item) {
            $dataView['utthotelroms'][] = array(
                'title' => sanitize_text_field($item['title']),
                'price' => (float) $item['price'],
                'image' => (int)$item['image'],
                'image_url' => esc_url(wp_get_attachment_image_url($item['image'])),
                'image_url_full' => esc_url(wp_get_attachment_image_url($item['image'], 'full')),
            );
        }

        unset($utthotelservice['__name__']);
        foreach ($utthotelservice as $key => $item) {
            $dataView['utthotelservice'][] = array(
                'title' => sanitize_text_field($item['title']),
                'image' => (int)$item['image'],
                'image_url' => esc_url(wp_get_attachment_image_url($item['image'])),
                'image_url_full' => esc_url(wp_get_attachment_image_url($item['image'], 'full')),
            );
        }

        $location = array(
            'address' => '',
            'lng' => '',
            'lat' => '',
        );
        if (isset($metabooking['contact_address'])) {
            $location['address'] = sanitize_text_field( $metabooking['contact_address']['address'] );
            $location['lng'] = sanitize_text_field( $metabooking['contact_address']['lng'] );
            $location['lat'] = sanitize_text_field( $metabooking['contact_address']['lat'] );
        }

        $dataView['metabooking'] = array(
            'contact_name' => sanitize_text_field(@$metabooking['contact_name']),
            'contact_address' => $location['address'],
            'contact_address_lng' => $location['lng'],
            'contact_address_lat' => $location['lat'],
            'contact_phone' => sanitize_text_field(@$metabooking['contact_phone']),
            'contact_email' => sanitize_email(@$metabooking['contact_email']),
            'contact_website' => esc_url(@$metabooking['contact_website']),
            'contact_google' => esc_url(@$metabooking['contact_google']),
            'contact_facebook' => esc_url(@$metabooking['contact_facebook']),
            'contact_twitter' => esc_url(@$metabooking['contact_twitter']),
        );

        update_post_meta($post_id, 'lng', $dataView['metabooking']['contact_address_lng']);
        update_post_meta($post_id, 'lat', $dataView['metabooking']['contact_address_lat']);
        update_post_meta($post_id, 'utt_hotel_data', $dataView);

        $attachment_ids = isset($_POST[self::keyMeta][self::keyGallery]) ? array_filter(explode(',', utt_clean($_POST[self::keyMeta][self::keyGallery]))) : array();
        update_post_meta($post_id, self::keyGallery, implode(',', $attachment_ids));
    }

    public function registerPostTypeHotel()
    {
        register_post_type($this->postType,
            [
                'labels'      => [
                    'name'          => __('Hotel'),
                    'singular_name' => __('Hotel'),
                ],
                'taxonomies' => array('region'),
                'public'      => true,
                'has_archive' => true,
                'supports' => array('thumbnail', 'title', 'editor', 'custom-fields', 'comments'),
                'menu_icon' => plugin_dir_url(UTT_PATH ) . 'asset/admin/img/hotel.svg'
            ]
        );
    }

    public function metaBoxHotel()
    {
        add_meta_box( 'utt_car', __('Car data'), array(&$this, 'metaBoxHtml'), UTTTravelHotel::postType, 'advanced', 'high');
    }

    public function metaBoxHtml($post)
    {
        include dirname(__FILE__) . '/views/meta_box.php';
    }

    public  function addStyle()
    {
    }
}

$UTTTravelHotel = new UTTTravelHotel();
$UTTTravelHotel->run();