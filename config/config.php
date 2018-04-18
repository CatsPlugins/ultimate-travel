<?php

class UTTConfig
{
    const TABLE_ATTRIBUTE_TAXONOMIE = 'utt_attribute_taxonomies';
    const TEMPLATE = 'ultimate-travel';
    const PLUGIN_VERSION = '0.1';
    const CACHE_VERSION = '0.1';
    const SIZE_THUMBNAIL = 'utmImage';
    const SIZE_BIG = 'utmImageBig';

    const SORT_DATE_DESC = 1;
    const SORT_DATE_ASC = 2;
    const SORT_NAME_DESC = 4;
    const SORT_NAME_ASC = 3;

    public static function beforePrice()
    {
        return get_option('uttcurrency_position', '') == 'before' ? UTTCurrency() : '';
    }

    public static function afterPrice()
    {
        return get_option('uttcurrency_position', '') == 'after' ? UTTCurrency() : '';
    }

    public static function getSortOption()
    {
        return apply_filters('utt_sort_data', array(
            self::SORT_DATE_DESC => __('Release date DESC', 'ultimate-travel'),
            self::SORT_DATE_ASC => __('Release date ASC', 'ultimate-travel'),
            self::SORT_NAME_ASC => __('Name A-Z', 'ultimate-travel'),
            self::SORT_NAME_DESC => __('Name Z-A', 'ultimate-travel'),
        ));
    }

    public static function getPostTypeFilter()
    {
        return array();
    }

    public static function getIconTravel()
    {
        $args = array(
            'flaticon-bus-side-view',
            'flaticon-ocean-transportation',
            'flaticon-delivery-truck-with-circular-clock',
            'flaticon-planet-earth',
            'flaticon-departures',
            'flaticon-delivery-truck-with-packages-behind',
            'flaticon-sedan-car-model',
            'flaticon-restaurant',
            'flaticon-travel-1',
            'flaticon-car-compact',
            'flaticon-airplane-around-earth',
            'flaticon-air-transport',
            'flaticon-black-plane',
            'flaticon-airplane'
        );

        return apply_filters('utt_icon_travel', $args);
    }
}