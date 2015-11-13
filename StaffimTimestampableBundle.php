<?php

namespace Staffim\TimestampableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * @author Vyacheslav Salakhutdinov <megazoll@gmail.com>
 */
class StaffimTimestampableBundle extends Bundle
{
    public function __construct()
    {
        Type::registerType('date', 'Staffim\TimestampableBundle\MongoDB\Type\DateType');
    }
}
