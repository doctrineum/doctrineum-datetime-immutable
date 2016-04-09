<?php
namespace Doctrineum\DateTimeImmutable;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\Type;
use Granam\Tools\ValueDescriber;
use Granam\Strict\Object\StrictObjectTrait;

/**
 * @method static DateTimeImmutableType getType($name),
 * @see Type::getType
 */
class DateTimeImmutableType extends Type
{
    use StrictObjectTrait;

    const DATETIME_IMMUTABLE = 'datetime_immutable';

    /**
     * @var DateTimeType
     */
    private $dateTimeType;

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

        return true;
    }

    /**
     * Gets the strongly recommended name of this type.
     * Its used at @see \Doctrine\DBAL\Platforms\AbstractPlatform::getDoctrineTypeComment
     *
     * @return string
     */
    public static function getTypeName()
    {
        return self::DATETIME_IMMUTABLE;
    }

    private static function checkRegisteredType()
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
     * @inheritdoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $this->getDateTimeType()->getSQLDeclaration($fieldDeclaration, $platform);
    }

    /**
     * @return DateTimeType
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getDateTimeType()
    {
        if ($this->dateTimeType === null) {
            $this->dateTimeType = parent::getType(Type::DATETIME);
        }

        return $this->dateTimeType;
    }

    /**
     * @inheritdoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $this->getDateTimeType()->convertToDatabaseValue($value, $platform);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTimeImmutable) {
            return $value;
        }

        $val = \DateTimeImmutable::createFromFormat($platform->getDateTimeFormatString(), $value);

        if (!$val) {
            try {
                $val = date_create_immutable($value);
            } catch (\Exception $exception) { // due to HHVM behavior
                $val = null; // exception will be thrown bellow
            }
        }

        if (!$val) {
            throw Exceptions\ConversionFailed::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $val;
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
