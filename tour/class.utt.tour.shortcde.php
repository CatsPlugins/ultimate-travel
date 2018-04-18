<?php

class UTTTourShortcode
{
    public static function init()
    {
        $args = array(
            'utt_tours_by_tag' => __CLASS__ . '::UTTTourByTag',
            'utt_tours_by_region' => __CLASS__ . '::UTTTourByRegion'
        );

        foreach ($args as $key => $item ) {
            add_shortcode($key, $item);
        }
    }

    public static function UTTTourByTag($atts)
    {
        $atts = shortcode_atts( array(
            'limit' => 6,
            'order' => 'DESC', //https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
            'orderby' => 'ID', //https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
            'tags_name' => '',
            'columns' => 1,
        ), $atts, 'UTTTourByTag' );

        $tags = array();
        if (isset($atts['tags_name']) && $atts['tags_name'] != '') {
            $tags = explode(',', $atts['tags_name']);
        }

        $args = array(
            'post_type' => UTTTravelTour::$postType,
            'posts_per_page' => $atts['limit'],
            'order' => $atts['order'],
            'orderby' => $atts['orderby']
        );

        if (count($tags) > 0) {
            $args['tax_query'] = array(
                'taxonomy' => UTTTravelTour::tagTour,
                'field' => 'slug',
                'terms' => $tags,
                'operator' => 'IN'
            );
        }

        $optionsLayout = array(
            'columns' => $atts['columns']
        );

        return self::loopLayout(__CLASS__ . __FUNCTION__, $args, $optionsLayout);

    }

    public static function UTTTourByRegion($atts)
    {
        $atts = shortcode_atts( array(
            'limit' => 6,
            'order' => 'DESC', //https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
            'orderby' => 'ID', //https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
            'ids' => '',
            'columns' => 1,
        ), $atts, 'UTTTourByTag' );

        $regions = (isset($atts['ids']) ? implode(',', $atts['ids']) : array());

        $args = array(
            'post_type' => UTTTravelTour::$postType,
            'posts_per_page' => $atts['limit'],
            'order' => $atts['order'],
            'orderby' => $atts['orderby']
        );

        if (count($regions) > 0) {
            $args['tax_query'] = array(
                'taxonomy' => UTTTravelTour::regionTour,
                'field'    => 'term_id',
                'terms' => $regions,
                'operator' => 'IN'
            );
        }

        $optionsLayout = array(
            'columns' => $atts['columns']
        );

        return self::loopLayout(__CLASS__ . __FUNCTION__, $args, $optionsLayout);
    }

    public static function loopLayout($name_query, $query_args, $optionsLayout)
    {

        $transient_name = 'utttour' . md5(serialize(array(
            $name_query,
                $query_args,
            UTTConfig::CACHE_VERSION
        )));

        $tours = get_transient( $transient_name );

        if ( false === $tours || ! is_a( $tours, 'WP_Query' ) ) {

            if (is_singular()) {
                $query_args['post__not_in'] = array(get_the_ID());
            }

            $tours = new WP_Query( $query_args );
            set_transient( $transient_name, $tours, DAY_IN_SECONDS * 30 );
        }

        ob_start();

        if ($tours->have_posts()) {
            include  UTTIncludeTemplatePart('loop/widget-loop', 'start');

            while ($tours->have_posts()) {
                $tours->the_post();
                UTTLoadTemplatePart('loop/widget-item', 'content');
            }

            include  UTTIncludeTemplatePart('loop/widget-loop', 'end');
        }

        wp_reset_postdata();
        wp_reset_query();

        return ob_get_clean();
    }
}