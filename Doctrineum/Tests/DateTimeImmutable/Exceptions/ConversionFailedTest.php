<?php
namespace Doctrineum\Tests\Scalar\Exceptions;

use Doctrineum\DateTimeImmutable\Exceptions\ConversionFailed;
use PHPUnit\Framework\TestCase;

class ConversionFailedTest extends TestCase
{
    /**
     * @test
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function I_can_use_it_as_doctrine_conversion_exception()
    {
        throw new ConversionFailed;
    }
}