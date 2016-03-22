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
        if (!($value instanceof \Staffim\DateTime\DateTime)) {
            return parent::convertToDatabaseValue($value);
        }

        // The 2nd argument to MongoDate is in µ-seconds
        return new \MongoDate($value->format('U'), $value->format('u'));
    }

    public function convertToPHPValue($value)
    {
        if (!($value instanceof \MongoDate)) {
            return parent::convertToPHPValue($value);
        }

        $date = \Staffim\DateTime\DateTime::createFromFormat('U.u', $value->sec . '.' . $value->usec);

        return $date;
    }

    public function closureToPHP()
    {
        return 'if ($value instanceof \MongoDate) { $return = \Staffim\DateTime\DateTime::createFromFormat(\'U.u\', $value->sec . \'.\' . $value->usec); } else { $return = new \Staffim\DateTime\DateTime($value); }';
    }
}
