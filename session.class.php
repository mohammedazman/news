<?php

abstract class Session
{
    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value, $key2, $value2)
    {
            $_SESSION[$key] = $value;
            $_SESSION[$key2] = $value2;
    }

    /**
     * @param $key
     * @return null
     */
    public static function get($key)
    {
        if (self::has($key)) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     */
    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * destroy
     */
    public static function destroy()
    {
        session_destroy();
    }
}