<?php

class UTTTemplateLoad
{
    public static function getTemplateLoader($postType)
    {
        $postType = UTTTravelRequest::getQuery('post_type', $postType);

        if ( is_singular( $postType ) ) {
            $default_file = 'single-'. $postType .'.php';
        } elseif ( is_tax( get_object_taxonomies( $postType )) ) {
            $default_file = 'archive-'. $postType .'.php';
        } elseif ( is_post_type_archive( $postType ) ) {
            $default_file = 'archive-'. $postType .'.php';
        } else {
            $default_file = '';
        }
        return $default_file;
    }

    public static function getTemplateSearch( $default_file, $dirTemplate, $postType) {
        $search_files = array();

        if ( is_tax(get_object_taxonomies( $postType )) ) {
            $term   = get_queried_object();
            $search_files[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $search_files[] = $dirTemplate . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $search_files[] = 'taxonomy-' . $term->taxonomy . '.php';
            $search_files[] =  $dirTemplate . 'taxonomy-' . $term->taxonomy . '.php';
            $search_files[] = 'archive-' . $postType . '.php';
            $search_files[] =  $dirTemplate . 'archive-' . $postType . '.php';
        }

        $search_files[] = $default_file;
        $search_files[] = $dirTemplate . $default_file;

        return array_unique( $search_files );
    }
}