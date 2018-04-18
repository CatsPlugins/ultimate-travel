<?php

class UTTravelAdminPage
{
    public static $slugMenu = 'uttravel';
    function __construct()
    {
        add_action( 'admin_menu', 'UTTravelAdminPage::dashboard');
    }

    public static function dashboard()
    {
        add_submenu_page(
            'edit.php?post_type=' . UTTTravelTour::$postType,
            'Settings',
            'Settings',
            'manage_options',
            'utt-tools',
            'UTTravelAdminPage::settingPage'
        );
    }

    public static function  settingPage() {
        $options = UTTTravel::optionFields();
        
        $headerTab = apply_filters('utt_tab_setting_header', array('General'));
        if (!isset($_GET['tab'])) $_GET['tab'] = sanitize_title('General');

        $contentTab = array();
        if (@$_GET['tab'] == sanitize_title('General')) {
            $contentTab = $options;
        }
        $contentTab = apply_filters('utt_tab_setting_content', $contentTab);


        ?>
        <div class="wrap">
            <h3><?php _e('UTTravel setting', 'ultimate-travel') ?></h3>

            <?php UTTFlasSession::output() ?>

            <form action="" method="post" onsubmit="optionPluginSubmit(event, '<?php echo admin_url( 'admin-ajax.php' ) ?>?action=saveoptions&tab=<?php echo @$_GET['tab'] ?>')">
                <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
                    <?php
                    foreach ($headerTab as $item) {
                        $active = '';
                        if (UTTTravelRequest::getQuery('tab', '') != '' && UTTTravelRequest::getQuery('tab', '') == sanitize_title($item)) {
                            $active = 'nav-tab-active';
                        } else {
                            if ($item == sanitize_title('General') ) {
                                $active = 'nav-tab-active';
                            }
                        }

                        if (UTTTravelRequest::getQuery('tab', '') == '') {
                            if ($item == sanitize_title('General') ) {
                                $active = 'nav-tab-active';
                            }
                        } else if(UTTTravelRequest::getQuery('tab', '') == sanitize_title($item)) {
                            $active = 'nav-tab-active';
                        }
                        echo '<a href="'. admin_url('admin.php') .'?page=utt-tools&tab='. sanitize_title($item) .'" class="nav-tab '. $active .'">'. $item .'</a>';
                    }
                    ?>
                </nav>

                <table class="tableOption">
                <?php
                $unikey = 'options';
                foreach ($contentTab as $key => $_value) : ?>
                    <tr>
                        <?php
                        if ($_value['type'] == 'title') {
                            echo "<tr><td colspan='2'><h3>{$_value['title']}</h3></td></tr>";
                        } else {
                            @$_value['default'] = get_option($key, $_value['default']);
                            $__key = $unikey . '['.$key.']';
                            $_function = 'utttravel_'.$_value['type'];
                            ?>
                            <td>
                                <?php echo $_value['title'] ?>
                            </td>
                            <td>
                                <?php call_user_func($_function, $__key, $_value) ; ?>
                                <?php
                                    if (isset($_value['desc'])) {
                                        echo '<p><i>' . $_value['desc'] . '</i></p>';
                                    }
                                ?>
                            </td>
                        <?php }

                        ?>
                    </tr>
                <?php endforeach; ?>


                <tr class="">
                    <td colspan="2">
                        <button class="button button-primary" type="submit"><?php _e('Save change', 'ultimate-travel') ?></button>
                    </td>
                </tr>
                </table>

            </form>
        </div>

        <?php
    }
}

new UTTravelAdminPage();