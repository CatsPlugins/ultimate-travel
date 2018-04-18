<?php

class IgoTravelInit
{
    public static function active()
    {
        self::createTable();

        $option = UTTTravel::optionFields();
        foreach($option as $key => $item){
            if (isset($item['default'])) {
              update_option($key, get_option($key, $item['default']));  
            }
        }
    }

    public static function deactive()
    {

    }

    private static function createTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . UTTConfig::TABLE_ATTRIBUTE_TAXONOMIE;

        $sql = "CREATE TABLE $table_name (
                      attribute_id bigint(20) NOT NULL AUTO_INCREMENT,
                      attribute_name varchar(255) NOT NULL,
                      attribute_label varchar(255) NOT NULL,
                      attribute_type varchar(20) DEFAULT NULL,
                      attribute_public int(1) NOT NULL DEFAULT '1',
                      PRIMARY KEY  (attribute_id)
                    ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
};