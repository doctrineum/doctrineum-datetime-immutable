<?php
declare(strict_types = 1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\DateTimeImmutable;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\DateTimeImmutable\DateTimeImmutableType;
use Doctrineum\Tests\SelfRegisteringType\AbstractSelfRegisteringTypeTest;

class DateTimeImmutableTypeTest extends AbstractSelfRegisteringTypeTest
{

    protected function getExpectedTypeName(string $typeClass = null): string
    {
        return 'doctrineum_datetime_immutable';
    }

    /**
     * @test
     */
    public function SQL_declaration_depends_on_platform()
    {
        DateTimeImmutableType::registerSelf();
        $dateTimeImmutableType = Type::getType(DateTimeImmutableType::DOCTRINEUM_DATETIME_IMMUTABLE);
        $platform = $this->createPlatform();
        $fieldDeclaration = [];
        $platform->shouldReceive('getDateTimeTypeDeclarationSQL')
            ->with($fieldDeclaration)
            ->once()
            ->andReturn($sqlDeclaration = 'foo');
        self::assertSame(
            $sqlDeclaration,
            $dateTimeImmutableType->getSQLDeclaration($fieldDeclaration, $platform)
        );
    }

    /**
     * @return \Mockery\MockInterface|AbstractPlatform
     */
    private function createPlatform(): AbstractPlatform
    {
        return $this->mockery(AbstractPlatform::class);
    }

    /**
     * @test
     */
    public function Conversion_to_database_value_depends_on_platform()
    {
        DateTimeImmutableType::registerSelf();
        $dateTimeImmutableType = Type::getType(DateTimeImmutableType::DOCTRINEUM_DATETIME_IMMUTABLE);
        $platform = $this->createPlatform();
        $platform->shouldReceive('getDateTimeFormatString')
            ->once()
            ->andReturn($format = 'c');
        $dateTimeImmutable = new \DateTimeImmutable();
        self::assertSame(
            $dateTimeImmutable->format($format),
            $dateTimeImmutableType->convertToDatabaseValue($dateTimeImmutable, $platform)
        );

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
        $dateTimeImmutableType = Type::getType(DateTimeImmutableType::DOCTRINEUM_DATETIME_IMMUTABLE);
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
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function I_can_not_convert_invalid_value_to_date()
    {
        DateTimeImmutableType::registerSelf();
        $dateTimeImmutableType = Type::getType(DateTimeImmutableType::DOCTRINEUM_DATETIME_IMMUTABLE);
        $platform = $this->createPlatform();
        $platform->shouldReceive('getDateTimeFormatString')
            ->andReturn($format = 'Y-m-d H:i:s');
        $dateTimeImmutableType->convertToPHPValue('passed tomorrow', $platform);
    }

}