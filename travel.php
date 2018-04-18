<?php
/**
 * Plugin Name: Ultimate travel
 * Plugin URI: https://catsplugins.com/ultimate-travel
 * Description: A plugin to help webmaster create tour website easily
 * Version: 1.3.3
 * Author: Cat's Plugins
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define('UTT_PATH', __FILE__);

/*
*	Bootstrap
*/

require_once __DIR__ . '/config/config.php';

require_once __DIR__ . '/includes/class.init.php';
require_once __DIR__ . '/includes/class.form.element.php';
require_once __DIR__ . '/includes/class.request.php';
require_once __DIR__ . '/includes/class.template.load.php';
require_once __DIR__ . '/includes/class.flas.session.php';
require_once __DIR__ . '/includes/class.admin.menu.page.php';
require_once __DIR__ . '/includes/class.comment.php';

if(is_admin()) {
    require_once __DIR__ . '/includes/func.admin.php';
} else {
    require_once __DIR__ . '/includes/func.frontend.php';
    require_once __DIR__ . '/includes/class.frontend.filter.php';
}
//
require_once __DIR__ . '/includes/func.helper.php';
require_once __DIR__ . '/includes/func.form.option.php';
require_once __DIR__ . '/includes/class.booking.php';
require_once __DIR__ . '/includes/class.UTTTravel.php';

require_once __DIR__ . '/tour/autoload.php';
require_once __DIR__ . '/hotel/autoload.php';
require_once __DIR__ . '/car/autoload.php';


/*
 * Action Init
 */

register_activation_hook( __FILE__, array('IgoTravelInit', 'active') );
register_deactivation_hook( __FILE__, array('IgoTravelInit', 'deactive') );

add_action('init', 'UTTTravel::initPlugin');
