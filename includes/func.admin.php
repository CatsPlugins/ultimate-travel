<?php


add_action( 'admin_enqueue_scripts', 'utttravel_add_script_admin' );
if (!function_exists('utttravel_add_script_admin')) {
    function utttravel_add_script_admin() {

        wp_register_style( 'utttravel_css_fonticon', plugin_dir_url(UTT_PATH) . 'asset/admin/font/flaticon.css' , array( ), '', 'all'  );
        wp_register_style( 'utttravel_css', plugin_dir_url(UTT_PATH) . 'asset/admin/css/admin.css' , array( ), '', 'all'  );

        wp_register_script( 'utttravel_js_select2', plugin_dir_url(UTT_PATH) . 'asset/admin/plugins/select2/js/select2.full.min.js' , array( 'jquery' ), '', true  );
        wp_register_style( 'utttravel_css_select2', plugin_dir_url(UTT_PATH) . 'asset/admin/plugins/select2/css/select2.min.css' , array( ), '', 'all'  );


        wp_register_script( 'utttravel_js', plugin_dir_url(UTT_PATH) . 'asset/admin/js/functions.js' , array( 'jquery', 'wp-color-picker', 'utttravel_js_select2' ), '', true  );
        wp_register_script( 'utttravel_js_metabox', plugin_dir_url(UTT_PATH) . 'asset/admin/js/metabox.js' , array( 'jquery', 'wp-color-picker', 'utttravel_js_select2' ), '', true  );


        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'utttravel_css_fonticon');
        wp_enqueue_style( 'utttravel_css');

        wp_enqueue_script( 'utttravel_js_select2');
        wp_enqueue_style( 'utttravel_css_select2');

        wp_enqueue_script('jquery-ui-sortable'); //load sortable
        wp_enqueue_media();
        wp_enqueue_script( 'utttravel_js');
        wp_enqueue_script( 'utttravel_js_metabox');
    }
}


add_action( 'wp_ajax_getpostype', 'UTTTravelGetPostType' );
if(!function_exists('UTTTravelGetPostType')) {
    function UTTTravelGetPostType()
    {
        $output = array();
        $numberPost = 0;

        $posttype = UTTTravelRequest::getQuery('posttype', '');
        $s = UTTTravelRequest::getQuery('term', '');
        if ($posttype) {
            $post = new WP_Query(array(
                'post_type' => $posttype,
                's' => $s
            ));

            if ($post->have_posts()) {
                while ($post->have_posts()) {
                    $post->the_post();
                    $output[] = array(
                        'id' => get_the_ID(),
                        'text' => get_the_title()
                    );
                }
            }

            $numberPost = $post->found_posts;
        }

        wp_send_json(array(
            'results' => $output,
        ));

        die;
    }
}

add_action( 'wp_ajax_saveoptions', 'UTTTravelSaveOption' );
if(!function_exists('UTTTravelSaveOption')) {
    function UTTTravelSaveOption()
    {
        $post = isset($_POST['options']) ? $_POST['options'] : array();

        if (UTTTravelRequest::getQuery('tab', 'general') == 'general') {
            $option = UTTTravel::optionFields();
            foreach($option as $key => $item){
                if (isset($post[$key])) {
                    update_option($key, sanitize_text_field($post[$key]));
                } else{
                    update_option($key, '');
                }
            }
        }

        do_action('utt_save_option');

        UTTFlasSession::success(__('Update success.', 'ultimate-travel'));

        wp_send_json_success(true);

        die;
    }
}
