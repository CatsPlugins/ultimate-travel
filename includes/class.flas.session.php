<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class UTTFlasSession
{
    public static $class = array(
        'success' => 'ut-alert ut-alert-success notice notice-success',
        'error' => 'ut-alert ut-alert-danger notice notice-error',
    );

    public static function success($mess)
    {
        $_SESSION['UTTFlasSession']['success'][] = $mess;
    }

    public static function error($mess)
    {
        $_SESSION['UTTFlasSession']['error'][] = $mess;
    }

    public static function output()
    {
        if (isset($_SESSION['UTTFlasSession']) && is_array($_SESSION['UTTFlasSession']) && count($_SESSION['UTTFlasSession'])> 0) {
            foreach ($_SESSION['UTTFlasSession'] as $key => $item) {
                $class = self::$class[$key];
                if (is_array($item) && count($item)> 0) {
                    foreach ($item as $_key => $_item) {
                        echo "<div class='{$class}'><p>{$_item}</p></div>";
                    }
                }
            }

            $_SESSION['UTTFlasSession'] = array();
        }
    }

    public static function destroy()
    {
        $_SESSION['UTTFlasSession'] = array();
    }
}