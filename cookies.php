<?php
const COOKIE_LIFESPAN = 30 * 24 * 60 * 60 * 1000; // a month, default cookie lifespan

function set_cookie($name, $value)
{
    setcookie($name, $value, time() + COOKIE_LIFESPAN, '/');
}

function remove_cookie($name)
{
    setcookie($name, '', time() - 10000, '/');
}

function get_cookie($name)
{
    if (isset($_COOKIE[$name]))
        return $_COOKIE[$name];
    else
        return false;
}
?>