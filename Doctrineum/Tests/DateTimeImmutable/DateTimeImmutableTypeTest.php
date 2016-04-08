<?php
namespace Doctrineum\Tests\DateTimeImmutable;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\DateTimeImmutable\DateTimeImmutableType;
use Doctrineum\Tests\DateTimeImmutable\Helpers\EnumTypes\IAmUsingOccupiedName;
use Granam\Tests\Tools\TestWithMockery;

class DateTimeImmutableTypeTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_register_it()
    {
        DateTimeImmutableType::registerSelf();
        self::assertTrue(Type::hasType(DateTimeImmutableType::getTypeName()));
        self::assertTrue(DateTimeImmutableType::isRegistered());
        self::assertFalse(DateTimeImmutableType::registerSelf(), 'Registering already registered should return false');
    }

    /**
     * @test
     * @depends I_can_register_it
     */
    public function I_can_get_it()
    {
        $instance = Type::getType(DateTimeImmutableType::getTypeName());
        self::assertInstanceOf(DateTimeImmutableType::getClass(), $instance);

        return $instance;
    }

    /**
     * @test
     * @depends I_can_get_it
     */
    public function Its_type_name_is_as_expected()
    {
        $expectedTypeName = 'datetime_immutable';
        self::assertSame(DateTimeImmutableType::getTypeName(), $expectedTypeName);

        $constantName = strtoupper($expectedTypeName);
        $enumTypeClass = DateTimeImmutableType::getClass();
        self::assertTrue(defined("$enumTypeClass::$constantName"));
        self::assertSame($expectedTypeName, constant("$enumTypeClass::$constantName"));
    }

    /**
     * @test
     * @expectedException \Doctrineum\DateTimeImmutable\Exceptions\TypeNameOccupied
     */
    public function I_can_not_replace_already_registered_different_type_of_same_name()
    {
        DateTimeImmutableType::registerSelf();
        IAmUsingOccupiedName::registerSelf();
    }

    /**
     * @test
     */
    public function SQL_declaration_depends_on_platform()
    {
        DateTimeImmutableType::registerSelf();
        $dateTimeImmutable = DateTimeImmutableType::getType(DateTimeImmutableType::getTypeName());
        $platform = $this->createPlatform();
        $fieldDeclaration = [];
        $platform->shouldReceive('getDateTimeTypeDeclarationSQL')
            ->with($fieldDeclaration)
            ->once()
            ->andReturn($sqlDeclaration = 'foo');
        self::assertSame(
            $sqlDeclaration,
            $dateTimeImmutable->getSQLDeclaration($fieldDeclaration, $platform)
        );
    }

    /**
     * @return \Mockery\MockInterface|AbstractPlatform
     */
    private function createPlatform()
    {
        return $this->mockery(AbstractPlatform::class);
    }

    /**
     * @test
     */
    public function Conversion_to_database_value_depends_on_platform()
    {
        DateTimeImmutableType::registerSelf();
        $dateTimeImmutableType = DateTimeImmutableType::getType(DateTimeImmutableType::getTypeName());
        $platform = $this->createPlatform();
        $platform->shouldReceive('getDateTimeFormatString')
            ->once()
            ->andReturn($format = 'c');
        $dateTimeImmutable = new \DateTimeImmutable();
        self::assertSame(
            $dateTimeImmutable->format($format),
            $dateTimeImmutableType->convertToDatabaseValue($dateTimeImmutable, $platform)
        );

        $dateTimeImmutableType = DateTimeImmutableType::getType(DateTimeImmutableType::getTypeName());
        self::assertNull(
            $dateTimeImmutableType->convertToDatabaseValue(null, $this->createPlatform())
        );
    }

    /**
     * @test
     */
    public function I_can_let_convert_database_value_to_datetime_immutable()
    {
        DateTimeImmutableType::registerSelf();
        $dateTimeImmutableType = DateTimeImmutableType::getType(DateTimeImmutableType::getTypeName());
        self::assertSame(
            $dateTimeImmutable = new \DateTimeImmutable(),
            $dateTimeImmutableType->convertToPHPValue(
                $dateTimeImmutable,
                $this->createPlatform()
            )
        );

        self::assertNull($dateTimeImmutableType->convertToPHPValue(null, $this->createPlatform()));

        $platform = $this->createPlatform();
        $platform->shouldReceive('getDateTimeFormatString')
            ->andReturn($format = 'Y-m-d H:i:s');
        $dateTimeImmutable = \DateTimeImmutable::createFromFormat($format, $stringDate = '2016-01-01 01:02:03');
        self::assertEquals(
            $dateTimeImmutable,
            $dateTimeImmutableType->convertToPHPValue($stringDate, $platform)
        );
    }

    /**
     * @test
     */
    public function I_can_get_name_same_as_type_name()
    {
        DateTimeImmutableType::registerSelf();
        $dateTimeImmutableType = DateTimeImmutableType::getType(DateTimeImmutableType::getTypeName());
        self::assertSame(
            DateTimeImmutableType::getTypeName(),
            $dateTimeImmutableType->getName()
        );
    }

}
