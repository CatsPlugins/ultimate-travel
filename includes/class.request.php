<?php
class UTTTravelRequest
{
    public static function getQuery($key, $default = '')
    {
        return (isset($_GET[$key]) ? trim($_GET[$key]) : $default);
    }

    public static function getPost($key, $default  = '') {
        return (isset($_POST[$key]) ? trim($_POST[$key]) : $default);
    }
}