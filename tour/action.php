<?php

class UTTTravelTour
{
    public static $postType = 'tour';
    public static $keyMeta = 'utttraveltour';
    public static $keyMetaGallery = '_tour_image_gallery';
    public static $keyTermImage = 'thumbnailTax';
    public static $keyTermIcon = 'iconTax';
    public static $keyMetaTourDetail = 'tourdetail';
    public static $keyMetaTourRating = 'commenttourrating';

    const tagTour = 'tag-tour';
    const regionTour = 'region';

    const metaKeyBooking = 'bookingdata';
    const postType = 'tour';
    
    function run()
    {
        global $wpdb;

        add_action('init', array($this, 'registerPostTypeTour'));
        add_action('init', 'UTTTourShortcode::init');

        if (class_exists('UTTTourBooking')) {
            add_action('init', 'UTTTourBooking::init');
        }

        add_action('add_meta_boxes', array($this, 'metaBoxTour'));
        add_action('save_post', array($this, 'saveMetaBoxTour') );

        add_action('admin_menu', array($this, 'createMenuAttribute'), 1);

        $table_name = $wpdb->prefix . UTTConfig::TABLE_ATTRIBUTE_TAXONOMIE;

        $attributes = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY `attribute_id` DESC', OBJECT );
        foreach ($attributes as $item) {
            add_action($item->attribute_name . '_edit_form_fields', array($this, 'edit_tax_image'), 40, 2);
            add_action($item->attribute_name . '_add_form_fields', array($this, 'add_tax_image'), 40, 2);
            add_action( 'edited_' . $item->attribute_name, array ( $this, 'updateImageTax' ), 10, 2 );
            add_action( 'create_' . $item->attribute_name, array ( $this, 'updateImageTax' ), 10, 2 );
        }

        add_action('region_edit_form_fields', array($this, 'edit_tax_image'), 40, 2);
        add_action('region_add_form_fields', array($this, 'add_tax_image'), 40, 2);
        add_action( 'edited_region', array ( $this, 'updateImageTax' ), 10, 2 );
        add_action( 'create_region', array ( $this, 'updateImageTax' ), 10, 2 );

        /*
         * WIDGET
         */
        add_action( 'widgets_init', array($this, 'addWidget') );

        add_action( 'pre_get_posts', array($this, 'editQueryArchive') );
    }

    public function editQueryArchive(WP_Query $query)
    {

        if ((is_post_type_archive(UTTTravelTour::$postType)) || (is_archive() &&  $query->is_main_query() && is_tax( get_object_taxonomies( UTTTravelTour::$postType ) ))) {
            $order = UTTTravelRequest::getQuery('order', 1);
            switch ($order) {
                case 1:
                    $query->set('order', 'ASC');
                    $query->set('orderby', 'modified');
                    break;
                case 2:
                    $query->set('order', 'DESC');
                    $query->set('orderby', 'modified');
                    break;
                case 3:
                    $query->set('order', 'DESC');
                    $query->set('orderby', 'title');
                    break;
                case 4:
                    $query->set('order', 'ASC');
                    $query->set('orderby', 'title');
                    break;
                default:
                    $query->set('order', 'DESC');
                    $query->set('orderby', 'modified');
                    break;
            }

            if (!isset($_GET['post_type'])) {
                $query->set('post_type', UTTTravelTour::$postType);
            }

            $filter  = UTTTravelRequest::getQuery('f', array());

            $taxQuery = array();
            $cfQuery = array();
            if (isset($filter['rating'])) {
                $filterRating = $filter['rating'];
                if (!isset($filterRating['min'])) {
                    $filterRating['min'] = 0;
                } else {
                    if ($filterRating['min'] > 5) {
                        $filterRating['min'] = 5;
                    } elseif($filterRating['min'] < 0) {
                        $filterRating['min'] = 0;
                    }
                }

                if (!isset($filterRating['max'])) {
                    $filterRating['max'] = 5;
                } else {
                    if ($filterRating['max'] < $filterRating['min']) {
                        $filterRating['max'] = $filterRating['min'];
                    } else {
                        if ($filterRating['max'] > 5) {
                            $filterRating['max'] = 5;
                        } elseif($filterRating['max'] < 0) {
                            $filterRating['max'] = 0;
                        }
                    }

                }

                $cfQuery[]= array(
                    'key' => 'cats_avg_rating',
                    'compare' => 'BETWEEN',
                    'value' => array((int)$filterRating['min'], (int)$filterRating['max']),
                    'type' => 'NUMERIC'
                );
            }

            if (isset($filter['price'])) {
                $filterPrice = $filter['price'];
                if (!isset($filterPrice['min']) || (int)$filterPrice['min'] < 0) {
                    $filterPrice['min'] = 0;
                }
                $cfQuery[]= array(
                    'key' => '_regular_price',
                    'compare' => '>=',
                    'value' => (int)$filterPrice['min'],
                    'type' => 'NUMERIC'
                );


                if (isset($filterPrice['max']) && $filterPrice['max'] >= $filterPrice['min']) {
                    $cfQuery[]= array(
                        'key' => '_regular_price',
                        'compare' => '<=',
                        'value' => (int)$filterPrice['max'],
                        'type' => 'NUMERIC'
                    );
                }


            }

            if (isset($filter['departure']) && is_array($filter['departure'])) {

                $cfQuery[]= array(
                    'key' => '_departure',
                    'compare' => 'IN',
                    'value' => (array)$filter['departure']
                );
            }

            if (isset($filter['journey']) && is_array($filter['journey'])) {
                $taxQuery[] = array(
                    'taxonomy' => self::regionTour,
                    'terms' => (array)$filter['journey'],
                    'operator' => 'IN'
                );
            }

            if(count($cfQuery) > 0) {
                $cfQuery['relation'] = 'AND';
            }

            if(count($taxQuery) > 0) {
                $taxQuery['relation'] = 'AND';
            }

            $query->set('tax_query', $taxQuery);
            $query->set('meta_query', $cfQuery);
        }

        return $query;

    }

    function addWidget() {
        register_sidebar( array(
            'name'          => __( 'Tour Detail Area' ),
            'id'            => 'tourdetail-sidebar',
            'description'   => __( 'Add widgets here to appear in your sidebar tour.' ),
            'before_widget' => '',
            'after_widget'  => '</aside>',
            'before_title'  => '<h2 class="ut-product-sidebar__heading">',
            'after_title'   => '</h2><aside id="%1$s" class="%2$s ut-product-sidebar__content">',
        ) );
        register_sidebar( array(
            'name'          => __( 'Tour List Area' ),
            'id'            => 'tourlist-sidebar',
            'description'   => __( 'Add widgets here to appear in your sidebar tour.' ),
            'before_widget' => '',
            'after_widget'  => '</aside>',
            'before_title'  => '',
            'after_title'   => '<aside id="%1$s" class="%2$s ">',
        ) );
    }


    public function updateImageTax ( $term_id, $tt_id ) {
        $keyImage = self::$keyTermImage;
        $keyIcon = self::$keyTermIcon;

        if( isset( $_POST[$keyImage] ) && '' !== $_POST[$keyImage] ){
            $image = $_POST[$keyImage];
            update_term_meta ( $term_id, $keyImage, $image );
        } else {
            update_term_meta ( $term_id, $keyImage, '' );
        }

        if( isset( $_POST[$keyIcon] ) && '' !== $_POST[$keyIcon] ){
            $image = $_POST[$keyIcon];
            update_term_meta ( $term_id, $keyIcon, $image );
        } else {
            update_term_meta ( $term_id, $keyIcon, '' );
        }
    }

    public function add_tax_image($taxonomy) {
        $keyImage = self::$keyTermImage;
        $keyIcon = self::$keyTermIcon;
        ?>
        <div class="form-field term-slug-wrap">
            <div class="wrapImageSingle">
                <label for="tag-slug">Icon</label>

                <input class="inputRecieverImage" type="hidden" id="<?php echo $keyIcon ?>" name="<?php echo $keyIcon ?>" value="">
                <div id="" class="image-wrapper"></div>
                <p>
                    <input type="button" class="button button-secondary utt_tax_media_button" id="utt_tax_media_button" name="utt_tax_media_button" value="<?php _e( 'Add Image'); ?>" />
                    <input type="button" class="button button-secondary utt_tax_media_remove" id="utt_tax_media_remove" name="utt_tax_media_remove" value="<?php _e( 'Remove Image'); ?>" />
                </p>
            </div>
        </div>
        <?php
    }
    public function edit_tax_image($term, $taxonomy)
    {
        $keyImage = self::$keyTermImage;
        $keyIcon = self::$keyTermIcon;
        ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="<?php echo $keyIcon ?>"><?php _e( 'Icon' ); ?></label>
            </th>
            <td class="wrapImageSingle">
                <?php $image_id = get_term_meta ( $term -> term_id, $keyIcon, true ); ?>
                <input class="inputRecieverImage" type="hidden" id="<?php echo $keyIcon ?>" name="<?php echo $keyIcon ?>" value="<?php echo $image_id; ?>">
                <div id="" class="image-wrapper">
                    <?php if ( $image_id ) { ?>
                        <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
                    <?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary utt_tax_media_button" id="utt_tax_media_button" name="utt_tax_media_button" value="<?php _e( 'Add Image'); ?>" />
                    <input type="button" class="button button-secondary utt_tax_media_remove" id="utt_tax_media_remove" name="utt_tax_media_remove" value="<?php _e( 'Remove Image'); ?>" />
                </p>
            </td>
        </tr>
        <tr>
            <th></th>
            <td><a href="<?php echo esc_url( wp_validate_redirect( admin_url( 'term.php?taxonomy=' . $taxonomy ) ) ); ?>" class=""><?php _e("Back to {$taxonomy}", 'ultimate-travel') ?></a></td>
        </tr>
        <?php
    }

    public function createMenuAttribute()
    {
        add_submenu_page(
            'edit.php?post_type=' . self::$postType,
            __('Attributes','tour-attributes'),
            __('Attributes','tour-attributes'),
            'manage_options',
            'tour-attributes',
            array($this, 'attributesPage')
        );
    }

    public function attributesPage()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . UTTConfig::TABLE_ATTRIBUTE_TAXONOMIE;

        if (isset($_GET['typeaction']) && $_GET['typeaction'] == 'delete' && isset($_GET['id'])) {
            $wpdb->delete( $table_name, array(
                'attribute_id' => $_GET['id']
            ));
        }

        if (isset($_GET['typeaction']) && $_GET['typeaction'] == 'edit' && isset($_GET['id'])) {

            $data = isset($_POST['form']) ? $_POST['form'] : array();

            if (count($data) > 0) {
                if (
                    isset($data['attribute_label']) &&
                    !empty($data['attribute_label'])
                ) {

                    if (isset($data['attribute_name']) && !empty($data['attribute_name'])) {
                        $slug = $data['attribute_name'];
                    } else {
                        $slug = sanitize_title($data['attribute_label']);
                    }

                    if (isset($data['attribute_public']) && !empty($data['attribute_public'])) {
                        $public = sanitize_title($data['attribute_public']);
                    } else {
                        $public = 0;
                    }

                    $wpdb->update(
                        $table_name,
                        array(
                            'attribute_label' => $data['attribute_label'],
                            'attribute_name' => $slug,
                            'attribute_public' => $public
                        ),
                        array(
                            'attribute_id' => $data['attribute_id']
                        ),
                        array(
                            '%s',
                            '%s',
                            '%d'
                        )
                    );

                    if (isset($data['origin_taxonomy']) && $data['origin_taxonomy'] != $slug) {
                        global $wpdb;
                        $wpdb->update($wpdb->term_taxonomy,
                            array('taxonomy' => $slug),
                            array('taxonomy' => $data['origin_taxonomy'])
                        );
                    }
                    $url = '?post_type=tour&page=tour-attributes&';
                    UTTFlasSession::success(__('Update success. </p><p><a href="'. $url .'">Back to list</a>', 'ultimate-travel'));

                } else {
                    $mess['error'][] = __('Name is require.');
                }
            }

            $attibuteDetail = $wpdb->get_row( "SELECT * FROM ". $table_name ." WHERE `attribute_id` = " . $_GET['id'] );
            include __DIR__ . '/views/admin_page_attribute_edit.php';
        } else {
            $attributes = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY `attribute_id` DESC', OBJECT );
            include __DIR__ . '/views/admin_page_attribute.php';
        }
    }

    public function saveMetaBoxTour( $post_id ) {
        if (get_post_type($post_id) == self::$postType) {
            $post = isset($_POST[self::$keyMeta]) ? $_POST[self::$keyMeta] : array();
            if (count($post) > 0) {

                delete_post_meta($post_id, '_departure');
                if (isset($post['departure']) && count($post['departure']) > 0) {
                    foreach ($post['departure'] as $item) {
                        $item = sanitize_text_field($item);
                        update_post_meta($post_id, '_departure', $item);
                    }
                }

                update_post_meta($post_id, '_journey', $post['journey_reorder']);

                $journey = $post['journey_reorder'];
                wp_set_post_terms($post_id, $journey, UTTTravelTour::regionTour, false);

                update_post_meta($post_id, self::$keyMeta, $post);


                // Save Galleries

                $attachment_ids = isset($post[self::$keyMetaGallery]) ? array_filter(explode(',', utt_clean($post[self::$keyMetaGallery]))) : array();
                update_post_meta($post_id, self::$keyMetaGallery, implode(',', $attachment_ids));
            }

            $post = isset($_POST[self::$keyMetaTourDetail]) ? $_POST[self::$keyMetaTourDetail] : array();
            unset($post['__name__']);
            if (count($post) > 0) {
                update_post_meta($post_id, self::$keyMetaTourDetail, $post);
            }


            // RENEW POST
            $post = isset($_POST[self::metaKeyBooking]) ? $_POST[self::metaKeyBooking] : array();
            if (isset($post['schedule'])) {
                unset($post['schedule']['__index__']);
                $post['schedule'] = array_unique($post['schedule']);
            }
            update_post_meta($post_id, '_regular_price', (isset($post['price']) ? (int)$post['price'] : 0));
            update_post_meta($post_id, '_sale_price', (isset($post['price_sale']) ? (int)$post['price_sale'] : ''));

            $_schedule = array();
            if (isset($post['schedule'])) {
                foreach ($post['schedule'] as $key => $value) {

                    if (is_numeric($value)) {
                        $_schedule[] = $value;
                    } else {
                        $value = explode('_', $value);
                        if (count($value) == 5) {
                            $time = mktime($value[3], $value[4], 0, $value[0], $value[1], $value[2]);
                            if ($time > strtotime('now')) {
                                $_schedule[] = $time;
                            }
                        }
                    }
                }
            }

            $post['schedule'] = array();

            $_schedule = array_unique($_schedule);
            sort($_schedule);

            delete_post_meta($post_id, 'schedule_time');
            foreach ($_schedule as $item) {
                add_post_meta($post_id, 'schedule_time', $item);
                $post['schedule'][] = $item;
            }

            if (count($post) > 0) {
                update_post_meta($post_id, self::metaKeyBooking, $post);
            }


            // Save Region
            if (isset($_POST['tax_input'])) {
                $attribute = array();
                foreach ($_POST['tax_input'] as $key => $item) {
                    if (!in_array($key, array('region', 'tag-tour'))) {
                        $attribute[$key] = $item;
                    }
                }

                update_post_meta($post_id, 'attributesSelected', $attribute);
            }
        }
    }

    public function metaBoxHtml($post) {
        include __DIR__ . '/views/admin_meta_box.php';
    }

    public function metaBoxTour() {
        add_meta_box( 'utt_tour', __('Tour data'), array(&$this, 'metaBoxHtml'), self::$postType, 'advanced', 'high');
    }

    public function  registerPostTypeTour()
    {
        global $wpdb;

        $label = __('Tour', 'ultimate-travel');
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
        register_post_type(self::$postType,
            [
                'labels'      => $labels,
                'public'      => true,
                'has_archive' => true,
                'supports' => array('thumbnail', 'title', 'editor', 'custom-fields', 'comments'),
                'menu_icon' => plugin_dir_url(UTT_PATH ) . 'asset/admin/img/travel.svg'
            ]
        );

        $regionArgs = array(
            'labels' => [
                'name'              => __('Region', 'ultimate-travel'),
                'singular_name'     => __('Region', 'ultimate-travel'),
                'search_items'      => __('Search Region', 'ultimate-travel'),
                'all_items'         => __('All Region', 'ultimate-travel'),
                'parent_item'       => __('Parent Region', 'ultimate-travel'),
                'parent_item_colon' => __('Parent Region', 'ultimate-travel'),
                'edit_item'         => __('Edit Region', 'ultimate-travel'),
                'update_item'       => __('Update Region', 'ultimate-travel'),
                'add_new_item'      => __('Add New Region', 'ultimate-travel'),
                'new_item_name'     => __('New Region', 'ultimate-travel'),
                'menu_name'         => __('Region', 'ultimate-travel'),
                'back_to_items'     => __("Back to Region", 'ultimate-travel'),
            ],
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
        );

        if (@$_GET['post_type'] == self::$postType || get_post_type(@$_GET['post']) == self::$postType) {
            $regionArgs['meta_box_cb'] = false;
        }
        register_taxonomy( 'region', self::$postType, $regionArgs);

        register_taxonomy( 'tag-tour', self::$postType, array(
            'labels' => [
                'name' => __('Tags'),
                'singular_name' => __('Tag', 'ultimate-travel'),
                'search_items'      => __('Search Tag', 'ultimate-travel'),
                'all_items'         => __('All Tag', 'ultimate-travel'),
                'parent_item'       => __('Parent Tag', 'ultimate-travel'),
                'parent_item_colon' => __('Parent Tag', 'ultimate-travel'),
                'edit_item'         => __('Edit Tag', 'ultimate-travel'),
                'update_item'       => __('Update Tag', 'ultimate-travel'),
                'add_new_item'      => __('Add New Tag', 'ultimate-travel'),
                'new_item_name'     => __('New Tag', 'ultimate-travel'),
                'menu_name'         => __('Tag Tour', 'ultimate-travel'),
                'back_to_items'     => __("Back to Tag", 'ultimate-travel')
            ],
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
        ));

        $table_name = $wpdb->prefix . UTTConfig::TABLE_ATTRIBUTE_TAXONOMIE;
        $attributes = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY `attribute_id` DESC', OBJECT );

        foreach ($attributes as $item) {
            $taxonomy = $item->attribute_name;

            $labels = array(
                'name'              => $item->attribute_label,
                'singular_name'     => $item->attribute_name,
                'search_items'      => __('Search '. $item->attribute_label , 'ultimate-travel'),
                'all_items'         => __('All '. $item->attribute_label , 'ultimate-travel'),
                'parent_item'       => __('Parent '. $item->attribute_label , 'ultimate-travel'),
                'parent_item_colon' => __('Parent '. $item->attribute_label, 'ultimate-travel'),
                'edit_item'         => __('Edit '. $item->attribute_label, 'ultimate-travel'),
                'update_item'       => __('Update '. $item->attribute_label , 'ultimate-travel'),
                'add_new_item'      => __('Add New '. $item->attribute_label , 'ultimate-travel'),
                'new_item_name'     => __('New '. $item->attribute_label .' Name', 'ultimate-travel'),
                'menu_name'         => __(''. $item->attribute_label , 'ultimate-travel'),
                'back_to_items'     => __("Back to {$item->attribute_name}", 'ultimate-travel'),
            );

            $args = array(
                'hierarchical'      => false,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_in_menu'      => false,
                'show_admin_column' => false,
                'query_var'         => true,
                'meta_box_cb'        => false,
                'show_in_quick_edit' => false,
                'show_in_nav_menus' => true,
                'rewrite'           => array( 'slug' => $item->attribute_name ),
            );

            register_taxonomy( $taxonomy, self::$postType, $args );
        }
    }
}


$uttTravelTour = new UTTTravelTour();
$uttTravelTour->run();


