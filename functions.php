<?php

if (!function_exists('now')) {
    function now() {
        return \Staffim\TimestampableBundle\DateTime\Calendar::now();
    }
}
