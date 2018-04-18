<?php

add_action( 'wp_ajax_createattributetax', 'UTT_createattributetax' );
if (!function_exists('UTT_createattributetax')) {
    function UTT_createattributetax()
    {
        $output = array();

        global $wpdb;
        $table_name = $wpdb->prefix . UTTConfig::TABLE_ATTRIBUTE_TAXONOMIE;

        $data = isset($_POST['form']) ? $_POST['form'] : array();
        $mess = [];
        $output['status'] = 400;

        if (count($data) > 0) {
            if (
                isset($data['attribute_label']) &&
                !empty($data['attribute_label'])
            ) {

                if (isset($data['attribute_label']) && !empty($data['attribute_label'])) {
                    $slug = sanitize_title($data['attribute_label']);
                } else {
                    $slug = sanitize_title($data['attribute_name']);
                }

                if (isset($data['attribute_public']) && !empty($data['attribute_public'])) {
                    $public = sanitize_title($data['attribute_public']);
                } else {
                    $public = 0;
                }

                $attrTax = $wpdb->get_var( "SELECT COUNT(`attribute_name`) FROM ". $table_name ." WHERE `attribute_name` = '" . $slug . "'" );
                if ($attrTax > 0) {
                    $mess['error'][] = __('Attribute exites.');
                } else {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'attribute_label' => $data['attribute_label'],
                            'attribute_name' => $slug,
                            'attribute_public' => $public
                        ),
                        array(
                            '%s',
                            '%s',
                            '%d'
                        )
                    );

                    $mess['success'][] = __('Create success.');

                    UTTFlasSession::success('Create attribute success');

                    $output['status'] = 200;
                    $output['result'] = array(
                        'attribute_label' => $data['attribute_label'],
                        'attribute_name' => $slug,
                        'attribute_public' => $public,
                        'terms' => ''
                    );
                }
            } else {
                $mess['error'][] = __('Name is require.');
            }
        }

        $output['message'] = $mess;
        wp_send_json($output);

        die;
    }
}


add_action( 'wp_ajax_getoptionattribute', 'UTT_getoptionattribute' );
add_action( 'wp_ajax_nopriv_getoptionattribute', 'UTT_getoptionattribute' );
if (!function_exists('UTT_getoptionattribute')) {
    function UTT_getoptionattribute()
    {
        $post = $_POST;
        $terms = $post;

        if (isset($post['taxonomy']) && !empty($post['taxonomy'])) {
            $terms = get_terms( array(
                'taxonomy' => $post['taxonomy'],
                'hide_empty' => false,
            ) );
        }


        if (empty($terms)) {
            wp_send_json_error($terms);
        } else {
            wp_send_json_success($terms);
        }
        die;
    }
}




add_action( 'wp_ajax_form-booking', 'UTT_bookingForm' );
add_action( 'wp_ajax_nopriv_form-booking', 'UTT_bookingForm' );
if (!function_exists('UTT_bookingForm')) {
    function UTT_bookingForm()
    {

        ob_start();
        UTTLoadTemplatePart('single/form', 'booking-ajax');
        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html,
            'post' => $_POST
        ));
    }
}