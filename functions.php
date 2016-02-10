<?php

if (!function_exists('now')) {
    function now() {
        return \Staffim\TimestampableBundle\DateTime\Calendar::now();
    }
}

if (!function_exists('today')) {
    function today() {
        return now()->setTime(0, 0, 0);
    }
}
