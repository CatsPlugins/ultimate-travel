<?php

if(!function_exists('UTTGetImageTerm')) {
    function UTTGetImageTerm($id, $key) {
        $src = get_term_meta($id, $key, true);
        $src = wp_get_attachment_url($src);
        return $src;
    }
}

if (!function_exists('UTTIncludeTemplatePart'))
{
    function UTTIncludeTemplatePart( $slug, $name = '', $default_path = '/tour/templates/') {
        $template = '';

        $template = locate_template( array( "{$slug}-{$name}.php", UTTConfig::TEMPLATE . "/{$slug}-{$name}.php" ) );

        $fileTemplatePlugin = dirname(UTT_PATH) . "{$default_path}{$slug}-{$name}.php";
        if ( ! $template && $name && file_exists( $fileTemplatePlugin ) ) {
            $template = $fileTemplatePlugin;
        }

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
        if ( ! $template ) {
            $template = locate_template( array( "{$slug}.php", UTTConfig::TEMPLATE . "/{$slug}.php" ) );
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters( 'utt_get_template_part', $template, $slug, $name );

        return $template;

    }
}

if (!function_exists('UTTLoadTemplatePart'))
{
    function UTTLoadTemplatePart( $slug, $name = '', $default_path = '/tour/templates/') {
        $template = '';

        $template = locate_template( array( "{$slug}-{$name}.php", UTTConfig::TEMPLATE . "/{$slug}-{$name}.php" ) );

        $fileTemplatePlugin = dirname(UTT_PATH) . "{$default_path}{$slug}-{$name}.php";
        if ( ! $template && $name && file_exists( $fileTemplatePlugin ) ) {
            $template = $fileTemplatePlugin;
        }

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
        if ( ! $template ) {
            $template = locate_template( array( "{$slug}.php", UTTConfig::TEMPLATE . "/{$slug}.php" ) );
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters( 'utt_get_template_part', $template, $slug, $name );

        if ( $template ) {
            load_template( $template, false );
        }

    }
}
if(!function_exists('utt_clean')) {
    function utt_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'utt_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }
}


if (!function_exists('UTTCurrency')) {
    function UTTCurrency() {
        $symbol = get_option('uttcurrency', '$');
        return apply_filters('utt_currency', $symbol);
    }
}

if (!function_exists('UTTCurrencyFormat')) {
    function UTTCurrencyFormat($number) {
        if (!is_numeric($number)) return 0;
        $decimals = 0;
        $decimals_point = UTTDecimalPoint();
        $thousands_point = UTTThousandPoint();
        return number_format($number, $decimals, $decimals_point, $thousands_point);
    }
}
if (!function_exists('UTTThousandPoint')) {
    function UTTThousandPoint() {
        return apply_filters('utt_thousand_point', get_option('uttthousand', ','));
    }
}
if (!function_exists('UTTDecimalPoint')) {
    function UTTDecimalPoint() {
        return apply_filters('utt_decimal_point', get_option('uttdecimal', '.'));
    }
}

if(!function_exists('uttNiceWord')) {
    function uttNiceWord($str, $number) {
        $text = trim(strip_tags($str));
        $max_char = (int) $number;
        $end = trim('...');

        if ($text != '') {
            $text = array_filter(explode(' ', trim($text)));
            $text = trim(implode(' ', $text));
        }

        $output = '';

        if (mb_strlen($text, 'UTF-8') > $max_char) {
            $words = explode(' ', $text);
            $i = 0;

            while (1) {
                $length = mb_strlen($output, 'UTF-8') + mb_strlen($words[$i], 'UTF-8');

                if ($length > $max_char) {
                    break;
                } else {
                    $output .= ' ' . $words[$i];
                    ++$i;
                }
            }

            $output .= $end;
        } else {
            $output = $text;
        }

        return trim($output);
    }
}

if (!function_exists('uttBreadcrumbs')) {
    function uttBreadcrumbs(){
        $link = array();

        $link[] = array(
            'label' => __('Home' , 'ultimate-travel'),
            'url' => get_home_url()
        );


        if (is_tax()) {
            $termId = get_queried_object()->term_id;
            $term = get_term($termId);
            $link[] = array(
                'label' => $term->name,
                'url' => get_term_link($term)
            );
        } else if (is_archive()) {
            $link[] = array(
                'label' => get_the_archive_title(),
                'url' => ''
            );
        } else if (is_single()) {
            $id_post = get_the_ID();
            $link[] = array(
                'label' => get_post_type($id_post),
                'url' => get_post_type_archive_link(get_post_type($id_post))
            );
            $link[] = array(
                'label' => get_the_title($id_post),
                'url' => get_permalink($id_post)
            );
        }


        $link = apply_filters('UTTravel_breadcrumbs', $link);

        $html = '<nav class="utt-breadcrumbs"><ul>';
        foreach ($link as $item) {
            $html .= "<li><a href='{$item['url']}'>{$item['label']}</a></li>";
        }
        $html .= '</ul></nav>';

        echo $html;
    }
}


if (!function_exists('UTTCurrencyPosition')) {
    function UTTCurrencyPosition($price)
    {
        return UTTConfig::beforePrice() . $price . UTTConfig::afterPrice();
    }
}


// Copy from twentyfifteen_comment_nav theme twentyfifteen. Thanks.
if ( ! function_exists( 'cats_comment_nav' ) ) :
    /**
     * Display navigation to next/previous comments when applicable.
     *
     * @since Twenty Fifteen 1.0
     */
    function cats_comment_nav() {
        // Are there comments to navigate through?
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfifteen' ); ?></h2>
                <div class="nav-links">
                    <?php
                    if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'twentyfifteen' ) ) ) :
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    endif;

                    if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'twentyfifteen' ) ) ) :
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    endif;
                    ?>
                </div><!-- .nav-links -->
            </nav><!-- .comment-navigation -->
            <?php
        endif;
    }
endif;



if (!function_exists('utt_options_sort')) {
    function utt_options_sort($keyName, $postType) {
        $currentSort = UTTTravelRequest::getQuery($keyName, '');
        $sorts = UTTConfig::getSortOption();
        $sorts = apply_filters("utt_option_sort_{$postType}", $sorts);
        foreach ($sorts as $k => $it) {
            $selected ='';
            if ($currentSort == $k) {
                $selected = 'selected';
            }
            echo "<option {$selected} value='{$k}'>{$it}</option>";
        }
    }
}


if (!function_exists('utt_general_form_request')) {
    function utt_general_form_request(array $exclude) {
        $get = $_GET;

        foreach ($get as $key => $item) {
            if (!is_array($item)) {
                if (!in_array($key, $exclude)) {
                    echo "<input name='{$key}' value='{$item}' type='hidden' />";
                }
            } elseif (is_array($item)) {

                foreach ($item as $s => $sv) {
                    if (!is_array($sv)) {
                        if (!in_array($key . '_' . $s, $exclude)) {
                            echo "<input name='{$key}[{$s}]' value='{$sv}' type='hidden' />";
                        }
                    }elseif (is_array($sv)) {

                        foreach ($sv as $_s => $_sv) {
                            if (!is_array($_sv)) {
                                if (!in_array($key . '_' . $s . '_' . $_s, $exclude)) {
                                    echo "<input name='{$key}[{$s}][{$_s}]' value='{$_sv}' type='hidden' />";
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}





if (!function_exists('utt_get_array_value')) {
    function utt_get_array_value(array $wrap, $key = '', $default = '') {
        if (is_array($wrap) && isset($wrap[$key])) {
            return $wrap[$key];
        } else {
            return '';
        }
    }
}


