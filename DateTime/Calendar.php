<?php

namespace Staffim\TimestampableBundle\DateTime;

class Calendar
{
    public static function now()
    {
        return new \Staffim\TimestampableBundle\DateTime\DateTime;
    }

    public static function today()
    {
        return self::now()->setTime(0, 0, 0);
    }

    public static function monday(\DateTimeInterface $date = null)
    {
        $today = $date ?: self::today();

        if ($today->format('w') < 1) {
            $monday = $today->modify('-6 day');
        } else {
            $monday = $today->modify('-' . ($today->format('w') - 1) . ' day');
        }

        return $monday;
    }
}
