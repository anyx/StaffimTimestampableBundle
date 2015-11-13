<?php

namespace Staffim\TimestampableBundle\Serializer;

use JMS\DiExtraBundle\Annotation as DI;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor as JmsJsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor as JmsJsonDeserializationVisitor;
use JMS\Serializer\Exception\RuntimeException;
use Staffim\TimestampableBundle\DateTime\DateTime;

class DateTimeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'Staffim\\TimestampableBundle\\DateTime\\DateTime',
                'method' => 'serializeDateTimeToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'Staffim\\TimestampableBundle\\DateTime\\DateTime',
                'method' => 'deserializeDateTimeFromJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'serializeDateTimeToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'deserializeDateTimeFromJson',
            ],
        ];
    }

    public function serializeDateTimeToJson(JmsJsonSerializationVisitor $visitor, \DateTime $date, array $type)
    {
        if (!($date instanceof DateTime)) {
            $date = new DateTime($date);
        }

        return $date->toIsoString();
    }

    public function deserializeDateTimeFromJson(JmsJsonDeserializationVisitor $visitor, $data, array $type)
    {
        try {
            return new DateTime((string) $data);
        } catch (\InvalidArgumentException $exception) {
            throw new RuntimeException(
                sprintf('Invalid datetime "%s", expected ISO format.', $data),
                0,
                $exception
            );
        }
    }
}
