<?php
function set_cookie($name, $value)
{
    setcookie($name, $value, time() + (86400 * 30), '/');
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