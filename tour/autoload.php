<?php


require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/class.uttourpost.php';
require_once __DIR__ . '/class.option.admin.php';
require_once __DIR__ . '/ajax.php';
require_once __DIR__ . '/class.utt.tour.shortcde.php';
require_once __DIR__ . '/acf-tourdata/acf-tour-data.php';

if (get_option('utt_booking_tour', '') == 'on') {
    require_once __DIR__ . '/class.utt.tour.booking.php';
}

require_once __DIR__ . '/filters.php';
require_once __DIR__ . '/action.php';

require_once __DIR__ . '/function.template.hook.php';

require_once __DIR__ . '/widget/class.tour.filter.php';
require_once __DIR__ . '/widget/class.tour.list.php';

//Coming soon
//require_once __DIR__ . '/widget/class.tour.last-minutes.php';