<?php

namespace Staffim\TimestampableBundle\DateTime;

class DateTime extends \DateTime implements \JsonSerializable
{
    /**
     * @var int
     */
    private $milliseconds = 0;

    /**
     * ISO 8601 supported. Complete date plus hours, minutes and seconds:
     * <code>
     * YYYY-MM-DDThh:mm:ssTZD (eg. 1997-07-16T19:20:30+01:00)
     * </code>
     *
     * Where:
     * <code>
     * YYYY = four-digit year
     * MM   = two-digit month (01 = January, etc.)
     * DD   = two-digit day of month (01 through 31)
     * hh   = two digits of hour (00 through 23) (am/pm not allowed)
     * mm   = two digits of minute (00 through 59)
     * ss   = two digits of second (00 through 59)
     * TZD  = time zone designator (Z or +hh:mm or -hh:mm)
     * </code>
     *
     *
     * @see http://www.w3.org/TR/NOTE-datetime
     * @see \Clock\DateTime::toIsoString()
     *
     * @throws \InvalidArgumentException When date and time format is wrong.
     *
     * @param null|string|\DateTime $dt
     * @param null|\DateTimeZone    $tz
     */
    public function __construct($dt = null, \DateTimeZone $tz = null)
    {
        if (!is_null($dt)) {
            if ($dt instanceof \DateTime) {
                $tz = $dt->getTimezone();
                $dt = $dt->format(static::ATOM);
            } elseif (is_string($dt)) {
                $dt = $this->normalizeDateTimeString($dt);
            } elseif (is_int($dt)) {
                // Timestamp.
                $dt = '@'.$dt;
            } elseif (is_float($dt)) {
                $this->setMilliseconds($this->getMillisecondsFromTimestamp($dt));
                $dt = '@'.floor($dt);
            } else {
                throw new \InvalidArgumentException('Wrong argument type.');
            }
        }
        try {
            parent::__construct($dt, $tz);
            if (is_null($dt)) {
                $this->setMilliseconds($this->getMillisecondsFromTimestamp(microtime(true)));
            }
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('Wrong date and time format.', 0, $exception);
        }
    }

    private function getMillisecondsFromTimestamp($timestamp)
    {
        $milliseconds = 0;
        if (is_float($timestamp)) {
            $milliseconds = floor(($timestamp - floor($timestamp)) * 1000);
        }
        return $milliseconds;
    }

    private function normalizeDateTimeString($string)
    {
        // Milliseconds.
        $pattern = '#\.(\d{1,})#';
        preg_match($pattern, $string, $matches);
        if ($matches) {
            // Clear milliseconds.
            $this->setMilliseconds($matches[1]);
            $string = preg_replace('#\.\d{1,}#', '', $string);
        }
        return str_replace('Z', '+00:00', $string);
    }

    /**
     * Complete date plus hours, minutes, seconds and milliseconds in UTC timezone:
     * <code>
     * 1997-07-16T19:20:30.231Z
     * </code>
     *
     * @return string
     */
    public function toIsoString()
    {
        $utcDateTime = $this->setTimezone(new \DateTimeZone('UTC'));
        $milliseconds = str_pad($this->getMilliseconds(), 3, '0', STR_PAD_LEFT);
        $formattedTime = str_replace('+00:00', '.'.$milliseconds.'Z', $utcDateTime->format(static::ATOM));

        return $formattedTime;
    }

    /**
     * @param int $milliseconds
     */
    public function setMilliseconds($milliseconds)
    {
        $this->milliseconds = $milliseconds;
    }

    /**
     * @return int
     */
    public function getMilliseconds()
    {
        return $this->milliseconds;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toIsoString();
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->toIsoString();
    }
}
