<?php

class UTTTravelCar
{
    const postType = 'car';
    const keyMeta = 'utttravelcar';
    const keyMetaPrice = '_price_rent';
    const keyMetaPriceMonth = '_price_rent_month';
    const keyMetaGallery = 'uttcargallery';

    const trademark = 'trademark';
    const date = 'date';
    const seats = 'seats';

    function run()
    {
        add_action('init', array($this, 'registerPostTypeCar'));
        if (class_exists('UTTCarBooking')) {
            add_action('init', 'UTTCarBooking::init');
        }

        add_action( 'add_meta_boxes', array($this, 'metaBoxCar'), 10, 1);
        add_action( 'save_post', array($this, 'saveMetaBoxCar') );

        add_action( 'widgets_init', array($this, 'addWidget') );
    }


    public function saveMetaBoxCar( $post_id ) {
        $post = isset($_POST[UTTTravelCar::keyMeta]) ? $_POST[UTTTravelCar::keyMeta] : array();
        if (count($post) > 0) {
            update_post_meta($post_id, UTTTravelCar::keyMeta, $post);
            update_post_meta($post_id, UTTTravelCar::keyMetaPrice, (isset($post['day_rent']) ? $post['day_rent'] : 0 ));
            update_post_meta($post_id, UTTTravelCar::keyMetaPriceMonth, (isset($post['monthly_rent']) ? $post['monthly_rent'] : 0 ));
        }

        $attachment_ids = isset( $_POST[UTTTravelCar::keyMetaGallery] ) ? array_filter( explode( ',', utt_clean( $_POST[UTTTravelCar::keyMetaGallery] ) ) ) : array();
        update_post_meta( $post_id, UTTTravelCar::keyMetaGallery, implode( ',', $attachment_ids ) );
    }

    public function addWidget()
    {
        register_sidebar( array(
            'name'          => __( 'Car List Area' ),
            'id'            => 'carlist-sidebar',
            'description'   => __( 'Add widgets here to appear in your sidebar Car.' ),
            'before_widget' => '',
            'after_widget'  => '</aside>',
            'before_title'  => '',
            'after_title'   => '<aside id="%1$s" class="%2$s ">',
        ) );
    }

    public static function metaBoxHtml($post) {
        $postMeta = array();

        $postIdRequest = UTTTravelRequest::getQuery('post_id', '');
        if (!is_admin() && !empty($postIdRequest) ) {
            $id = $postIdRequest;
        } else if(is_a($post, 'WP_Post')) {
            $id = $post->ID;
        }

        if ($post) {
            $id = $post->ID;
            $postMeta = get_post_meta($id, UTTTravelCar::keyMeta, true);
        }


        $options =  array(
            'color' => array(
                'type' => 'text',
                'label' => __('Color'),
            ),
            'day_rent' => array(
                'type' => 'text',
                'label' => __('Day rent')
            ),
            'extra_hours' => array(
                'type' => 'text',
                'label' => __('Extra hours')
            ),
            'extra_km' => array(
                'type' => 'text',
                'label' => __('Extra km')
            ),
            'car_code' => array(
                'type' => 'text',
                'label' => __('SKU')
            ),
            'monthly_rent' => array(
                'type' => 'text',
                'label' => __('Monthly rent')
            ),
            'shortcode_form' => array(
                'type' => 'textarea',
                'label' => __('Shortcode Form book'),
                'attributes' => array(
                    'style' => 'width: 100%',
                    'rows' => 5
                )
            ),
            self::keyMetaGallery => array(
                'type' => 'multiple_image',
                'label' => 'Gallery'
            )
        );

        if(!is_admin()) {
            unset($options['shortcode_form']);
        }

        echo renderHtmlOption($options, $postMeta, UTTTravelCar::keyMeta);

    }

    public function metaBoxCar() {
        add_meta_box( 'utt_car', 'Settings Car', 'UTTTravelCar::metaBoxHtml', UTTTravelCar::postType, 'advanced', 'high');
    }

    public function  registerPostTypeCar()
    {
        $label = __('Car', 'ultimate-travel');
        $labels = array(
            'name'               => __($label, 'ultimate-travel'),
            'singular_name'      => sprintf(__("%s", "ultimate-travel"), $label),
            'menu_name'          => sprintf(__("%ss", "ultimate-travel"), $label),
            'name_admin_bar'     => sprintf(__("%s", "ultimate-travel"), $label),
            'add_new'            => sprintf(__("Add New", "ultimate-travel"), $label),
            'add_new_item'       => sprintf(__("Add New %s", "ultimate-travel"), $label),
            'new_item'           => sprintf(__("New %s", "ultimate-travel"), $label),
            'edit_item'          => sprintf(__("Edit %s", "ultimate-travel"), $label),
            'view_item'          => sprintf(__("View %s", "ultimate-travel"), $label),
            'all_items'          => sprintf(__("All %ss", "ultimate-travel"), $label),
            'search_items'       => sprintf(__("Search %ss", "ultimate-travel"), $label),
            'parent_item_colon'  => sprintf(__("Parent %ss:", "ultimate-travel"), $label),
            'not_found'          => sprintf(__("No %ss found.", "ultimate-travel"), $label),
            'not_found_in_trash' => sprintf(__("No %ss found in Trash.", "ultimate-travel"), $label),
        );
        register_post_type(UTTTravelCar::postType,
            [
                'labels'      => $labels,
                'public'      => true,
                'has_archive' => true,
                'supports' => array('thumbnail', 'title', 'editor', 'custom-fields', 'comments'),
                'menu_icon' => plugin_dir_url(UTT_PATH ) . '/asset/admin/img/automobile.svg'
            ]
        );

        $label = __('Trademark', 'ultimate-travel');
        $labels = array(
            'name'              => "{$label}",
            'singular_name'     => "{$label}",
            'search_items'      => "Search {$label}",
            'all_items'         => "All {$label}",
            'parent_item'       => "Parent {$label}",
            'parent_item_colon' => "Parent {$label}",
            'edit_item'         => "Edit {$label}",
            'update_item'       => "Update {$label}",
            'add_new_item'      => "Add {$label} New",
            'new_item_name'     => "New {$label}",
            'menu_name'         => "{$label}",
            'back_to_items'     => "Back to {$label}",
        );

        register_taxonomy( UTTTravelCar::trademark, UTTTravelCar::postType, array(
            'labels' => $labels,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
        ));

        $label = __('Date', 'ultimate-travel');
        $labels = array(
            'name'              => "{$label}",
            'singular_name'     => "{$label}",
            'search_items'      => "Search {$label}",
            'all_items'         => "All {$label}",
            'parent_item'       => "Parent {$label}",
            'parent_item_colon' => "Parent {$label}",
            'edit_item'         => "Edit {$label}",
            'update_item'       => "Update {$label}",
            'add_new_item'      => "Add {$label} New",
            'new_item_name'     => "New {$label}",
            'menu_name'         => "{$label}",
            'back_to_items'     => "Back to {$label}",
        );
        register_taxonomy( UTTTravelCar::date, UTTTravelCar::postType, array(
            'labels' => $labels,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
        ));

        $label = __('Seats', 'ultimate-travel');
        $labels = array(
            'name'              => "{$label}",
            'singular_name'     => "{$label}",
            'search_items'      => "Search {$label}",
            'all_items'         => "All {$label}",
            'parent_item'       => "Parent {$label}",
            'parent_item_colon' => "Parent {$label}",
            'edit_item'         => "Edit {$label}",
            'update_item'       => "Update {$label}",
            'add_new_item'      => "Add {$label} New",
            'new_item_name'     => "New {$label}",
            'menu_name'         => "{$label}",
            'back_to_items'     => "Back to {$label}",
        );
        register_taxonomy( UTTTravelCar::seats, UTTTravelCar::postType, array(
            'labels' => $labels,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
        ));

    }
}


$IgoTravelCar = new UTTTravelCar();
$IgoTravelCar->run();
