<?php
namespace Doctrineum\DateTimeImmutable;

use Doctrine\DBAL\Types\Type;
use Granam\Strict\Object\StrictObjectTrait;
use Granam\String\StringTools;
use Granam\Tools\ValueDescriber;

abstract class AbstractSelfRegisteringType extends Type
{
    use StrictObjectTrait;

    /**
     * @return bool If enum has not been registered before and was registered now
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function registerSelf()
    {
        if (static::hasType(static::getTypeName())) {
            static::checkRegisteredType();

            return false;
        }

        static::addType(static::getTypeName(), get_called_class());

        return static::hasType(static::getTypeName());
    }

    /**
     * Gets the strongly recommended name of this type.
     * Its used at @see \Doctrine\DBAL\Platforms\AbstractPlatform::getDoctrineTypeComment
     *
     * @return string
     */
    public static function getTypeName()
    {
        return preg_replace('~Type$~', '', StringTools::camelToSnakeCaseBasename(self::getClass()));
    }

    protected static function checkRegisteredType()
    {
        $alreadyRegisteredType = static::getType(static::getTypeName());
        if (get_class($alreadyRegisteredType) !== get_called_class()) {
            throw new Exceptions\TypeNameOccupied(
                'Under type of name ' . ValueDescriber::describe(static::getTypeName()) .
                ' is already registered different type ' . get_class($alreadyRegisteredType)
            );
        }
    }

    /**
     * Finds out if current type is already in registry
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function isRegistered()
    {
        return static::hasType(static::getTypeName());
    }

    /**
     * Gets the strongly recommended name of this type.
     * Its used at @see \Doctrine\DBAL\Platforms\AbstractPlatform::getDoctrineTypeComment
     *
     * @return string
     */
    public function getName()
    {
        return static::getTypeName();
    }

}