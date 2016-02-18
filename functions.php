<?php

if (!function_exists('now')) {
    function now() {
        return \Staffim\TimestampableBundle\DateTime\Calendar::now();
    }
}

if (!function_exists('today')) {
    function today() {
        return \Staffim\TimestampableBundle\DateTime\Calendar::today();
    }
}

if (!function_exists('monday')) {
    function monday(\DateTimeInterface $date = null) {
        return \Staffim\TimestampableBundle\DateTime\Calendar::monday($date);
    }
}
