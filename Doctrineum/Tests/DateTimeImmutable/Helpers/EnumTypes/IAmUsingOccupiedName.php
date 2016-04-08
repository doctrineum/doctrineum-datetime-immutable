<?php
namespace Doctrineum\Tests\DateTimeImmutable\Helpers\EnumTypes;

use Doctrineum\DateTimeImmutable\DateTimeImmutableType;

class IAmUsingOccupiedName extends DateTimeImmutableType
{
    public static function getTypeName()
    {
        return parent::getTypeName();
    }

}
