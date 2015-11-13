<?php

namespace Staffim\TimestampableBundle\MongoDB\Type;

use Doctrine\ODM\MongoDB\Types\DateType as BaseDateType;

/**
 * The Date type with milliseconds.
 */
class DateType extends BaseDateType
{
    public function convertToDatabaseValue($value)
    {
        if (!($value instanceof \Staffim\Crab\DateTime\DateTime)) {
            return parent::convertToDatabaseValue($value);
        }

        $timestamp = $value->format('U');
        $milliseconds = (int) $value->getMilliseconds();

        // The 2nd argument to MongoDate is in µ-seconds
        return new \MongoDate($timestamp, $milliseconds * 1000);
    }

    public function convertToPHPValue($value)
    {
        if (!($value instanceof \MongoDate)) {
            return parent::convertToPHPValue($value);
        }

        $date = new \Staffim\Crab\DateTime\DateTime($value->sec);
        $date->setMilliseconds($value->usec / 1000);

        return $date;
    }

    public function closureToPHP()
    {
        return 'if ($value instanceof \MongoDate) { $date = new \Staffim\Crab\DateTime\DateTime($value->sec); $date->setMilliseconds($value->usec / 1000); $return = $date; } else { $return = new \Staffim\Crab\DateTime\DateTime($value); }';
    }
}
