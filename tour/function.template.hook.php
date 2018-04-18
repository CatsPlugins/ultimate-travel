<?php

if(!is_admin()) {
    if(get_option('uttinclude_breadcrumbs') == 'on') {
        add_action('utt_before_page_tour', 'uttBreadcrumbs', 10);
    }
}
